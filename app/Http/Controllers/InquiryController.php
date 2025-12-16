<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\inquiry_history;
use App\Models\Module4\Notification;
use App\Models\Agency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Models_Module3\VerificationProcess;

class InquiryController extends Controller
{
    /**
     * Display the inquiry submission form
     */
    public function create()
    {
        return view('inquiry.User.user_create_inq');
    }

    /**
     * Store a newly created inquiry
     */    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:50',
            'evidence_url' => 'nullable|url',
            'media_attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240' // 10MB max
        ]);

        try {
            $userId = Auth::id();
            \Log::info('Creating inquiry for user', [
                'user_id' => $userId,
                'title' => $validated['title']
            ]);

            // Handle file upload
            $mediaPath = null;
            if ($request->hasFile('media_attachment')) {
                $mediaPath = $request->file('media_attachment')->store('inquiry_attachments', 'public');
            }

            // Create a new inquiry record
            $inquiry = new Inquiry();
            $inquiry->public_user_id = $userId;
            $inquiry->title = $validated['title'];
            $inquiry->content = $validated['content'] ?? null;
            $inquiry->category = $validated['category'] ?? null;
            $inquiry->evidence_url = $validated['evidence_url'] ?? null;
            $inquiry->media_attachment = $mediaPath;
            $inquiry->status = 'Pending';
            $inquiry->date_submitted = now();
            $inquiry->save();

            \Log::info('Created inquiry successfully', [
                'inquiry_id' => $inquiry->inquiry_id,
                'title' => $inquiry->title
            ]);

            // Log status history
            $history = new inquiry_history();
            $history->inquiry_id = $inquiry->inquiry_id;
            $history->new_status = 'Pending';
            $history->user_id = $userId;
            $history->timestamp = now();
            $history->agency_id = 1; // Use existing agency ID
            $history->save();

            \Log::info('Creating notification for new inquiry', [
                'user_id' => $userId,
                'inquiry_id' => $inquiry->inquiry_id,
                'title' => $inquiry->title
            ]);

            try {
                // Create a new notification record
                $notification = new Notification();
                $notification->user_id = $userId;
                $notification->inquiry_id = $inquiry->inquiry_id;
                $notification->message = "Your new inquiry '{$inquiry->title}' has been submitted and is pending review.";
                $notification->date_sent = now();
                $notification->is_read = false;
                $notification->save();

                \Log::info('Notification created successfully', ['notification_id' => $notification->notification_id]);
            } catch (\Exception $e) {
                \Log::error('Failed to create notification', [
                    'error' => $e->getMessage(),
                    'user_id' => $userId,
                    'inquiry_id' => $inquiry->inquiry_id
                ]);
            }

            return redirect()->back()->with('success', 'Your inquiry has been submitted successfully! You will receive updates on your registered email.');
        } catch (\Exception $e) {
            \Log::error('Error creating inquiry or notification: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->with('error', 'Failed to submit inquiry. Please try again.')->withInput();
        }
    }

    /**
     * Display public inquiries with search and filter options
     */
    public function publicInquiries(Request $request)
    {
        $query = Inquiry::query();
        
        // Only show inquiries that are not private (you can add a privacy column later if needed)
        // For now, we'll show all inquiries but hide personal information
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('date_submitted', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date_submitted', '<=', $request->input('date_to'));
        }
        
        // Order by newest first
        $query->orderBy('date_submitted', 'desc');
        
        // Paginate results
        $inquiries = $query->paginate(10)->withQueryString();
        
        return view('inquiry.User.user_public_inq', compact('inquiries'));
    }

    /**
     * Display a specific inquiry with role-based content
     */
    public function show(Request $request, $id)
    {
        $inquiry = Inquiry::with(['user', 'history.changedBy'])
                         ->findOrFail($id);
        
        // Get current user and determine their role
        $user = auth()->user();
        $userRole = 'public'; // default
          // Determine user role based on user model methods
        if ($user) {
            if ($user->isAdmin()) {
                $userRole = 'admin';
            } elseif ($user->isAgency()) {
                $userRole = 'agency';
            } elseif ($user->user_id === $inquiry->public_user_id) {
                $userRole = 'owner'; // The user who submitted the inquiry
            } else {
                $userRole = 'public'; // Regular public user viewing other inquiries
            }
        }
        
        // Check access permissions
        $canView = $this->canViewInquiry($inquiry, $user, $userRole);
        
        if (!$canView) {
            abort(403, 'You do not have permission to view this inquiry.');
        }
        
        return view('inquiry.inq_details', compact('inquiry', 'userRole'));
    }

    /**
     * Check if user can view the inquiry based on their role
     */
    private function canViewInquiry($inquiry, $user, $userRole)
    {
        switch ($userRole) {
            case 'admin':
                return true; // Admin can view all inquiries
                
            case 'agency':
                // Agency can view inquiries assigned to them or in public view
                return true; // For now, allow all agencies to view
                
            case 'owner':
                return true; // User can view their own inquiries
                
            case 'public':
                // Public users can view inquiries that are not private
                // You can add privacy logic here if needed
                return true;
                
            default:
                return false;
        }
    }

    /**
     * Display user's own inquiries dashboard
     */
    public function userDashboard(Request $request)
    {
        $user = auth()->user();
        
        // Get user's inquiries with pagination
        $query = Inquiry::with(['history'])
                       ->where('public_user_id', $user->user_id)
                       ->orderBy('date_submitted', 'desc');
        
        // Optional status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }
        
        $inquiries = $query->paginate(10)->withQueryString();
        
        // Get summary statistics
        $totalInquiries = Inquiry::where('public_user_id', $user->user_id)->count();
        $pendingInquiries = Inquiry::where('public_user_id', $user->user_id)
                                  ->where('status', 'Pending')->count();
        $inProgressInquiries = Inquiry::where('public_user_id', $user->user_id)
                                     ->where('status', 'In Progress')->count();
        $resolvedInquiries = Inquiry::where('public_user_id', $user->user_id)
                                   ->where('status', 'Resolved')->count();
        
        return view('inquiry.User.user_dashboard', compact(
            'inquiries', 
            'totalInquiries', 
            'pendingInquiries', 
            'inProgressInquiries', 
            'resolvedInquiries'
        ));
    }

    /**
     * Show comprehensive agency inquiry list with advanced filtering and search
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showAgencyInquiryList(Request $request)
    {
        // Check if user is authenticated as agency
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Get current agency user info
        $user = Auth::user();
        $agency = null;
        
        // Try to get agency information from the user
        if (method_exists($user, 'agency')) {
            $agency = $user->agency;
        } elseif (property_exists($user, 'agency_id')) {
            $agency = Agency::find($user->agency_id);
        }

        // Get filter parameters
        $search = $request->get('search', '');
        $status = $request->get('status', 'all');
        $category = $request->get('category', 'all');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $dateRange = $request->get('date_range', 'all');
        $sortBy = $request->get('sort_by', 'date_submitted');
        $sortOrder = $request->get('sort_order', 'desc');

        // Build query for all inquiries assigned to this agency
        $query = inquiry::with(['user', 'verificationProcesses']);

        // Filter by assigned agency
        if ($agency) {
            $inquiryIds = VerificationProcess::where('staff_agency_id', $agency->agency_id)
                ->pluck('inquiry_id');
            $query->whereIn('inquiry_id', $inquiryIds);
        }

        // Apply search filter (search in title, content, citizen name, inquiry ID)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('inquiry_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($status !== 'all') {
            if ($status === 'under_investigation') {
                $query->where('status', 'Under Investigation');
            } elseif ($status === 'verified_true') {
                $query->where('status', 'Verified as True');
            } elseif ($status === 'identified_fake') {
                $query->where('status', 'Identified as Fake');
            } elseif ($status === 'rejected') {
                $query->where('status', 'Rejected');
            } else {
                $query->where('status', $status);
            }
        }

        // Apply category filter
        if ($category !== 'all') {
            $query->where('category', $category);
        }

        // Apply date range filters
        if ($dateRange !== 'all') {
            $now = now();
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('date_submitted', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('date_submitted', [
                        $now->copy()->startOfWeek()->toDateString(),
                        $now->copy()->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'month':
                    $query->whereYear('date_submitted', $now->year)
                          ->whereMonth('date_submitted', $now->month);
                    break;
                case 'year':
                    $query->whereYear('date_submitted', $now->year);
                    break;
                case 'last_30_days':
                    $query->whereDate('date_submitted', '>=', $now->copy()->subDays(30));
                    break;
                case 'last_90_days':
                    $query->whereDate('date_submitted', '>=', $now->copy()->subDays(90));
                    break;
            }
        }

        // Apply custom date range filter
        if ($dateFrom) {
            $query->whereDate('date_submitted', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date_submitted', '<=', $dateTo);
        }

        // Apply sorting
        if ($sortBy === 'citizen_name') {
            $query->join('users', 'inquiries.public_user_id', '=', 'users.user_id')
                  ->orderBy('users.name', $sortOrder);
        } elseif ($sortBy === 'status') {
            $query->orderBy('status', $sortOrder);
        } elseif ($sortBy === 'category') {
            $query->orderBy('category', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Get paginated results
        $inquiries = $query->paginate(20)->withQueryString();

        // Get comprehensive statistics
        $stats = $this->getComprehensiveInquiryStatistics($agency);

        // Get available categories for filter dropdown
        $categories = inquiry::whereIn('inquiry_id', function($query) use ($agency) {
            if ($agency) {
                $query->select('inquiry_id')
                    ->from('verification_processes')
                    ->where('staff_agency_id', $agency->agency_id);
            }
        })
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category')
        ->filter()
        ->sort();

        // Get available statuses for filter dropdown
        $statuses = inquiry::whereIn('inquiry_id', function($query) use ($agency) {
            if ($agency) {
                $query->select('inquiry_id')
                    ->from('verification_processes')
                    ->where('staff_agency_id', $agency->agency_id);
            }
        })
        ->distinct()
        ->pluck('status')
        ->filter()
        ->sort();

        return view('inquiry.agency.agency_inq_list', compact(
            'inquiries', 
            'stats', 
            'categories', 
            'statuses',
            'agency',
            'search',
            'status',
            'category',
            'dateFrom',
            'dateTo',
            'dateRange',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Get comprehensive inquiry statistics for agency
     *
     * @param mixed $agency
     * @return array
     */
    private function getComprehensiveInquiryStatistics($agency)
    {
        $baseQuery = inquiry::query();
        
        if ($agency) {
            $inquiryIds = VerificationProcess::where('staff_agency_id', $agency->agency_id)
                ->pluck('inquiry_id');
            $baseQuery->whereIn('inquiry_id', $inquiryIds);
        }

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $underInvestigation = (clone $baseQuery)->where('status', 'Under Investigation')->count();
        $verifiedTrue = (clone $baseQuery)->where('status', 'Verified as True')->count();
        $identifiedFake = (clone $baseQuery)->where('status', 'Identified as Fake')->count();
        $rejected = (clone $baseQuery)->where('status', 'Rejected')->count();
        $inProgress = (clone $baseQuery)->where('status', 'In Progress')->count();
        $resolved = (clone $baseQuery)->where('status', 'Resolved')->count();

        // Calculate overdue (more than 30 days old and not resolved/closed)
        $overdue = (clone $baseQuery)->whereNotIn('status', ['Resolved', 'Closed', 'Verified as True', 'Identified as Fake'])
                         ->where('date_submitted', '<', now()->subDays(30))
                         ->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'under_investigation' => $underInvestigation,
            'verified_true' => $verifiedTrue,
            'identified_fake' => $identifiedFake,
            'rejected' => $rejected,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'overdue' => $overdue
        ];
    }
}