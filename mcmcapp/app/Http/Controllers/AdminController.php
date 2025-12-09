<?php
namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Usermodel;
use App\Models\Inquiry;
use App\Models\inquiry_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\InquiryReportsExport;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Register a new agency
    public function registerAgency($name, $email, $password)
    {
        try {
            // Create user first
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'user_role' => 'agency'
            ]);

            // Create agency record
            $agency = Agency::create([
                'agency_name' => $name,
                'agency_email' => $email
            ]);

            // Link user to agency
            $user->agency_id = $agency->agency_id;
            $user->save();

            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Failed to register agency: ' . $e->getMessage());
        }
    }

    // View all users (admin functionality)
    public function getAllUsers()
    {
        return \App\Models\User::all();
    }

    // Generate user report (basic example)
    public function generateReport(Request $request)
    {
        $query = User::query();

        // Apply filters based on user_role and date range
        if ($request->filled('user_role')) {
            $query->where('user_role', $request->input('user_role'));
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->input('date_from'), $request->input('date_to')]);
        }

        $users = $query->get();

        return view('ManageUser.MCMC_Admin.generate_report', [
            'users' => $users,
            'summary' => [
                'total_users' => $users->count(),
                'public_users' => $users->where('user_role', 'publicuser')->count(),
                'agency_users' => $users->where('user_role', 'agency')->count(),
                'admin_name' => Auth::user()->name,
            ],
        ]);
    }

    public function dashboard()
    {
        return view('AdminHomePage');
    }

    public function showRegisterAgencyForm()
    {
        return view('ManageUser.MCMC_Admin.register_agency');
    }

    public function showAdminHomePage()
    {
        $admin = Auth::user();
        return view('AdminHomePage', compact('admin'));
    }
    public function showSettings()
    {
        $admin = Auth::user();
        \Log::info('Admin accessing settings page', ['admin_id' => $admin->id ?? null, 'admin_email' => $admin->email ?? 'not authenticated']);
        return view('ManageUser.MCMC_Admin.Setting', compact('admin'));
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function showReportPage(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'user_role', 'report_type']);

        // Default date range: last 30 days to today if not specified
        if (empty($filters['date_from'])) {
            $filters['date_from'] = now()->subDays(30)->format('Y-m-d');
        }

        if (empty($filters['date_to'])) {
            $filters['date_to'] = now()->format('Y-m-d');
        }

        // Query to get filtered users for the table display
        $usersQuery = User::query();

        // Apply date filter for users table
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $usersQuery->whereBetween('created_at', [
                $filters['date_from'] . ' 00:00:00',
                $filters['date_to'] . ' 23:59:59'
            ]);
        }

        // Apply user role filter for users table
        if (!empty($filters['user_role']) && $filters['user_role'] !== 'all') {
            $usersQuery->where('user_role', $filters['user_role']);
        }

        // Check if this is a user profile report
        $isProfileReport = !empty($filters['report_type']) && $filters['report_type'] === 'user_profile';

        // For user profile report, include additional user data like last login
        if ($isProfileReport) {
            $users = $usersQuery->orderBy('name')->get();
        } else {
            // Get filtered users for display - Don't specify columns to avoid id column error
            $users = $usersQuery->get();
        }

        // Create base query for summary statistics with date filter only
        $baseQuery = User::query();

        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $baseQuery->whereBetween('created_at', [
                $filters['date_from'] . ' 00:00:00',
                $filters['date_to'] . ' 23:59:59'
            ]);
        }

        // Create queries for each user type count
        $publicQuery = clone $baseQuery;
        $agencyQuery = clone $baseQuery;
        $adminQuery = clone $baseQuery;

        // Calculate summary counts
        $summary = [
            'total_users' => $users->count(), // This shows count based on all active filters
            'public_users' => $publicQuery->where('user_role', 'publicuser')->count(),
            'agency_users' => $agencyQuery->where('user_role', 'agency')->count(),
            'admin_users' => $adminQuery->where('user_role', 'admin')->count(),
            'admin_name' => auth()->user()->name,
        ];

        return view('ManageUser.MCMC_Admin.generate_report', compact('users', 'summary', 'filters'));
    }    // Add admin login handler for redirection
    public function handleAdminLogin(Request $request)
    {
        \Log::info('Admin login attempt', ['email' => $request->email]);

        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $email = $credentials['email'];
        // First, check if this is an admin email
        $isAdminEmail = str_ends_with($email, '@admin.com');
        \Log::info('Is admin email?', ['result' => $isAdminEmail ? 'Yes' : 'No']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            \Log::info('Authentication successful', [
                'email' => $user->email,
                'user_role' => $user->user_role ?? 'unknown'
            ]);
            $user->last_login_at = now();
            $user->save();

            // First update the user role if needed for admin emails
            if (str_ends_with($user->email, '@admin.com') && $user->user_role != 'admin') {
                $user->user_role = 'admin';
                $user->save();
                \Log::info('Updated user role to admin');
            }

            // Redirect based on email - if it ends with @admin.com, go to admin home
            if (str_ends_with($user->email, '@admin.com')) {
                \Log::info('Redirecting to admin home page');
                return redirect()->route('admin.home')->with('success', 'Welcome to Admin Home Page!');
            }

            // If not an admin email, redirect to public dashboard
            \Log::info('Redirecting to public dashboard');
            return redirect()->route('publicuser.dashboard')->with('success', 'Welcome to Dashboard!');
        }

        \Log::warning('Authentication failed', ['email' => $email]);
        return back()->with('error', 'Invalid email or password. Please try again.');
    }
    public function handleRegisterAgency(Request $request)
    {
        $request->validate([
            'agencyName' => ['required', 'string', 'max:255'],
            'agencyEmail' => ['required', 'email', 'unique:users,email'],
            'agencyPassword' => ['required', 'string', 'min:6'],
        ]);

        if ($request->agencyPassword !== $request->confirmPassword) {
            return back()->withErrors(['confirmPassword' => 'Passwords do not match.']);
        }

        $user = new User();
        $user->name = $request->agencyName;
        $user->email = $request->agencyEmail;
        $user->password = Hash::make($request->agencyPassword);
        $user->user_role = 'agency';
        $user->save();

        return redirect()->route('admin.settings')->with('success', 'Agency registered successfully!');
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:15',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->contact_number = $request->contact_number;

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $admin->profile_picture = $path;
        }

        $admin->save();

        return redirect()->route('admin.settings')->with('success', 'Profile updated successfully');
    }
    public function exportReportToPDF(Request $request)
    {
        // Query to get filtered users for the PDF
        $usersQuery = User::query();

        // Apply date filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $usersQuery->whereBetween('created_at', [
                $request->input('date_from') . ' 00:00:00',
                $request->input('date_to') . ' 23:59:59'
            ]);
        }

        // Apply user role filter
        if ($request->filled('user_role') && $request->input('user_role') !== 'all') {
            $usersQuery->where('user_role', $request->input('user_role'));
        }

        // Check if this is a user profile report
        $reportType = $request->input('report_type', 'detailed');
        $isUserProfileReport = $reportType === 'user_profile';

        // For user profile reports, include additional user data and sort by name
        if ($isUserProfileReport) {
            $users = $usersQuery->orderBy('name')->get();
        } else {
            // Get users - Don't specify columns to avoid id column error
            $users = $usersQuery->get();
        }

        // Create base query for summary statistics with date filter only
        $baseQuery = User::query();

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $baseQuery->whereBetween('created_at', [
                $request->input('date_from') . ' 00:00:00',
                $request->input('date_to') . ' 23:59:59'
            ]);
        }

        // Create queries for each user type count
        $publicQuery = clone $baseQuery;
        $agencyQuery = clone $baseQuery;
        $adminQuery = clone $baseQuery;

        // Calculate summary counts
        $summary = [
            'total_users' => $users->count(),
            'public_users' => $publicQuery->where('user_role', 'publicuser')->count(),
            'agency_users' => $agencyQuery->where('user_role', 'agency')->count(),
            'admin_users' => $adminQuery->where('user_role', 'admin')->count(),
        ];

        $pdf = PDF::loadView('exports.users_pdf', [
            'users' => $users,
            'summary' => $summary,
            'report_type' => $reportType,
            'user_role' => $request->input('user_role'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'admin_name' => Auth::user()->name,
            'generated_date' => now()->format('M d, Y')
        ]);

        // Set filename based on report type
        $filename = $isUserProfileReport ? 'user_profiles_report.pdf' : 'users_report.pdf';

        return $pdf->download($filename);
    }
    public function exportToExcel(Request $request)
    {
        // Log incoming parameters for debugging
        \Log::info('Excel Export Request Parameters:', $request->all());

        $reportType = $request->input('report_type', 'detailed');
        $fileName = ($reportType === 'user_profile' ? 'user_profile' : 'users_report') . '.csv';

        // Create a new instance of UsersExport
        $export = new UsersExport(
            $request->input('user_role'),
            $request->input('date_from'),
            $request->input('date_to'),
            $reportType
        );

        // Get the data from the export
        $data = $export->collection();

        // Create CSV content manually (simpler approach than using problematic Excel package)
        $csv = $this->generateCsv($data);

        // Return the CSV data as a downloadable file
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return response($csv)->withHeaders($headers);
    }

    /**
     * Generate CSV content from the data array
     *
     * @param array $data
     * @return string
     */
    private function generateCsv($data)
    {
        $output = fopen('php://temp', 'r+');

        // Add report information
        fputcsv($output, ['Report Type', isset($data['report_type']) ? ucfirst(str_replace('_', ' ', $data['report_type'])) : 'Detailed']);
        fputcsv($output, ['Date Range', ($data['date_from'] ?? 'All time') . ' to ' . ($data['date_to'] ?? 'Present')]);
        fputcsv($output, ['Generated At', $data['generated_at']]);
        fputcsv($output, []); // Empty line

        // Add summary section
        fputcsv($output, ['Summary']);
        fputcsv($output, ['Total Users', $data['summary']['total_users'] ?? 0]);
        fputcsv($output, ['Public Users', $data['summary']['public_users'] ?? 0]);
        fputcsv($output, ['Agency Users', $data['summary']['agency_users'] ?? 0]);
        fputcsv($output, ['Admin Users', $data['summary']['admin_users'] ?? 0]);
        fputcsv($output, []); // Empty line

        // Add user data with headers
        if (!empty($data['users'])) {
            fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Created Date', 'Last Login']);

            foreach ($data['users'] as $user) {
                fputcsv($output, [
                    $user['id'] ?? '',
                    $user['name'] ?? '',
                    $user['email'] ?? '',
                    $user['phone'] ?? '',
                    $user['role'] ?? '',
                    $user['created_at'] ?? '',
                    $user['last_login'] ?? ''
                ]);
            }
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
    public function exportToPDF(Request $request)
    {
        // Log incoming parameters for debugging
        \Log::info('PDF Export Request Parameters:', $request->all());

        // Query to get filtered users for the PDF
        $usersQuery = User::query();

        // Apply date filter for users
        if ($request->input('date_from') && $request->input('date_to')) {
            $usersQuery->whereBetween('created_at', [
                $request->input('date_from') . ' 00:00:00',
                $request->input('date_to') . ' 23:59:59'
            ]);
        }

        // Apply user role filter for users
        if ($request->input('user_role') && $request->input('user_role') !== 'all') {
            $usersQuery->where('user_role', $request->input('user_role'));
        }

        // Check if this is a user profile report
        $reportType = $request->input('report_type', 'detailed');
        $isProfileReport = $reportType === 'user_profile';

        // For user profile report, include additional user data
        if ($isProfileReport) {
            $users = $usersQuery->orderBy('name')->get();
        } else {
            // Get users without specifying column list to avoid 'id' column issue
            $users = $usersQuery->get();
        }

        // Create base query for summary statistics with date filter only
        $baseQuery = User::query();

        if ($request->input('date_from') && $request->input('date_to')) {
            $baseQuery->whereBetween('created_at', [
                $request->input('date_from') . ' 00:00:00',
                $request->input('date_to') . ' 23:59:59'
            ]);
        }

        // Create queries for each user type count
        $publicQuery = clone $baseQuery;
        $agencyQuery = clone $baseQuery;
        $adminQuery = clone $baseQuery;

        // Calculate summary counts
        $summary = [
            'total_users' => $users->count(), // This shows count based on all active filters
            'public_users' => $publicQuery->where('user_role', 'publicuser')->count(),
            'agency_users' => $agencyQuery->where('user_role', 'agency')->count(),
            'admin_users' => $adminQuery->where('user_role', 'admin')->count(),
        ];

        $pdf = PDF::loadView('exports.users_pdf', [
            'users' => $users,
            'summary' => $summary,
            'report_type' => $reportType,
            'user_role' => $request->input('user_role'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'admin_name' => auth()->user()->name,
            'generated_date' => now()->format('M d, Y')
        ]);

        // Set a custom filename based on report type
        $filename = $isProfileReport ? 'user_profiles_report.pdf' : 'users_report.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Show the admin login form
     *
     * @return \Illuminate\View\View
     */
    public function showAdminLoginForm()
    {
        return view('LoginAlluser');
    }

    /**
     * Display admin inquiry management dashboard
     *
     * @return \Illuminate\View\View
     */
    public function showInquiriesPage()
    {
        // Get only pending inquiries with user information
        $inquiries = Inquiry::with('user')
            ->whereIn('status', ['Pending', 'Under Review'])
            ->orderBy('date_submitted', 'desc')
            ->get();

        // Calculate statistics (only for pending inquiries)
        $stats = [
            'pending' => $inquiries->where('status', 'Pending')->count(),
            'under_review' => $inquiries->where('status', 'Under Review')->count(),
            'total' => $inquiries->count()
        ];

        return view('inquiry.admin.admin_dashboard', compact('inquiries', 'stats'));
    }

    /**
     * Discard inquiry as non-serious
     *
     * @param int $inquiryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function discardAsNonSerious($inquiryId)
    {
        try {
            $inquiry = Inquiry::findOrFail($inquiryId);
            $previousStatus = $inquiry->status;

            // Update inquiry status
            $inquiry->status = 'rejected';
            $inquiry->save();

            // Log status change in inquiry history
            inquiry_history::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'new_status' => 'rejected',
                'user_id' => Auth::id(),
                'timestamp' => now(),
                'agency_id' => 1 // Use existing agency ID
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inquiry has been discarded as non-serious and marked as rejected.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to discard inquiry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Forward inquiry and mark as in progress
     *
     * @param int $inquiryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function forwardInquiry($inquiryId)
    {
        try {
            $inquiry = Inquiry::findOrFail($inquiryId);
            $previousStatus = $inquiry->status;

            // Update inquiry status
            $inquiry->status = 'under investigation';
            $inquiry->save();

            // Log status change in inquiry history
            inquiry_history::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'new_status' => 'under investigation',
                'user_id' => Auth::id(),
                'timestamp' => now(),
                'agency_id' => 1 // Use existing agency ID
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inquiry has been forwarded and marked as in progress.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to forward inquiry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show admin view of inquiry details
     *
     * @param int $inquiryId
     * @return \Illuminate\View\View
     */
    public function showInquiryDetails($inquiryId)
    {
        try {
            $inquiry = Inquiry::with(['user', 'history.changedBy'])->findOrFail($inquiryId);

            // Set user role as admin for admin view
            $userRole = 'admin';

            // Return the unified inquiry details view with admin role
            return view('inquiry.inq_details', compact('inquiry', 'userRole'));
        } catch (\Exception $e) {
            return redirect()->route('admin.inquiries')->with('error', 'Inquiry not found.');
        }
    }

    /**
     * Show admin inquiry reports page with filtering and statistics
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showInquiryReports(Request $request)
    {
        // Get filter parameters
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $statusFilter = $request->get('status_filter', 'all');
        $categoryFilter = $request->get('category_filter', 'all');

        // Build base query
        $query = Inquiry::with('user');

        // Apply date range filter
        $query->whereBetween('date_submitted', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        // Apply status filter
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Apply category filter
        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        // Get filtered inquiries
        $inquiries = $query->orderBy('date_submitted', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total' => $inquiries->count(),
            'pending' => $inquiries->whereIn('status', ['Pending', 'Under Review'])->count(),
            'in_progress' => $inquiries->where('status', 'under investigation')->count(),
            'resolved' => $inquiries->where('status', 'Verified as True')->count(),
            'closed' => $inquiries->where('status', 'rejected')->count(),
        ];

        // Status distribution for pie chart
        $statusDistribution = [
            'Pending' => $inquiries->where('status', 'Pending')->count(),
            'Under Review' => $inquiries->where('status', 'Under Review')->count(),
            'In Progress' => $inquiries->where('status', 'under investigation')->count(),
            'Resolved' => $inquiries->where('status', 'Verified as True')->count(),
            'Closed' => $inquiries->where('status', 'rejected')->count(),
        ];

        // Monthly trend data for line chart
        $monthlyTrends = [];
        $currentDate = now()->subMonths(11)->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $monthKey = $currentDate->format('Y-m');
            $monthName = $currentDate->format('M Y');

            $monthlyTrends[$monthName] = Inquiry::whereYear('date_submitted', $currentDate->year)
                ->whereMonth('date_submitted', $currentDate->month)
                ->count();

            $currentDate->addMonth();
        }

        // Get unique categories for filter dropdown
        $categories = Inquiry::distinct()->pluck('category')->filter()->sort()->values();

        // Get unique statuses for filter dropdown
        $statuses = Inquiry::distinct()->pluck('status')->filter()->sort()->values();

        return view('inquiry.admin.admin_report', compact(
            'inquiries',
            'stats',
            'statusDistribution',
            'monthlyTrends',
            'categories',
            'statuses',
            'dateFrom',
            'dateTo',
            'statusFilter',
            'categoryFilter'
        ));
    }

    /**
     * Export inquiry reports to PDF
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportInquiryReportToPDF(Request $request)
    {
        // Get the same filtered data as the reports page
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $statusFilter = $request->get('status_filter', 'all');
        $categoryFilter = $request->get('category_filter', 'all');

        $query = Inquiry::with('user');
        $query->whereBetween('date_submitted', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        $inquiries = $query->orderBy('date_submitted', 'desc')->get();

        $stats = [
            'total' => $inquiries->count(),
            'pending' => $inquiries->whereIn('status', ['Pending', 'Under Review'])->count(),
            'in_progress' => $inquiries->where('status', 'under investigation')->count(),
            'resolved' => $inquiries->where('status', 'Verified as True')->count(),
            'closed' => $inquiries->where('status', 'rejected')->count(),
        ];

        $pdf = PDF::loadView('inquiry.admin.reports_pdf', compact('inquiries', 'stats', 'dateFrom', 'dateTo'));

        return $pdf->download('inquiry_reports_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export inquiry reports to Excel
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportInquiryReportToExcel(Request $request)
    {
        // Get the same filtered data as the reports page
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $statusFilter = $request->get('status_filter', 'all');
        $categoryFilter = $request->get('category_filter', 'all');

        return Excel::download(
            new InquiryReportsExport($dateFrom, $dateTo, $statusFilter, $categoryFilter),
            'inquiry_reports_' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Display all inquiries except pending ones with search and filtering
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function showAllInquiries(Request $request)
    {
        // Build base query - exclude pending and under review inquiries
        $query = Inquiry::with('user')
            ->whereNotIn('status', ['Pending', 'Under Review']);

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('content', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('date_submitted', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_submitted', '<=', $request->input('date_to'));
        }

        // Handle export
        if ($request->has('export') && $request->input('export') === 'csv') {
            return $this->exportAllInquiriesToCSV($query);
        }

        // Order by newest first and paginate
        $inquiries = $query->orderBy('date_submitted', 'desc')->paginate(15);

        // Calculate statistics
        $allNonPendingInquiries = Inquiry::whereNotIn('status', ['Pending', 'Under Review'])->get();
        $stats = [
            'in_progress' => $allNonPendingInquiries->where('status', 'under investigation')->count(),
            'resolved' => $allNonPendingInquiries->where('status', 'Verified as True')->count(),
            'closed' => $allNonPendingInquiries->where('status', 'rejected')->count(),
            'rejected' => $allNonPendingInquiries->where('status', 'Rejected')->count(),
            'total' => $allNonPendingInquiries->count()
        ];

        // Get unique categories for filter dropdown
        $categories = Inquiry::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('inquiry.admin.admin_all_inq', compact('inquiries', 'stats', 'categories'));
    }

    /**
     * Export filtered inquiries to CSV
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Http\Response
     */
    private function exportAllInquiriesToCSV($query)
    {
        $inquiries = $query->get();

        $csvData = [];
        $csvData[] = ['ID', 'Title', 'Content', 'Submitter Name', 'Submitter Email', 'Category', 'Status', 'Date Submitted'];

        foreach ($inquiries as $inquiry) {
            $csvData[] = [
                '#' . str_pad($inquiry->inquiry_id, 3, '0', STR_PAD_LEFT),
                $inquiry->title,
                $inquiry->content,
                $inquiry->user->name ?? 'Unknown User',
                $inquiry->user->email ?? 'N/A',
                $inquiry->category ?: 'General',
                $inquiry->status,
                $inquiry->date_submitted->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'all_inquiries_' . date('Y-m-d_H-i-s') . '.csv';

        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}

?>