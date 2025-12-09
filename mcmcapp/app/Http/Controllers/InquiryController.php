<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\inquiry_history;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
     */
    public function store(Request $request)
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
            // Handle file upload
            $mediaPath = null;
            if ($request->hasFile('media_attachment')) {
                $mediaPath = $request->file('media_attachment')->store('inquiry_attachments', 'public');
            }

            // Create the inquiry record
            $inquiry = new Inquiry();
            $inquiry->public_user_id = Auth::id();
            $inquiry->title = $validated['title'];
            $inquiry->content = $validated['content'];
            $inquiry->category = $validated['category'];
            $inquiry->evidence_url = $validated['evidence_url'];
            $inquiry->media_attachment = $mediaPath;
            $inquiry->status = 'Pending';
            $inquiry->date_submitted = now();

            $inquiry->save();

            // Create initial history record
            $history = new inquiry_history();
            $history->inquiry_id = $inquiry->inquiry_id;
            $history->new_status = 'Pending';
            $history->user_id = Auth::id();
            $history->timestamp = now();
            $history->agency_id = 1; // Use existing agency ID
            $history->save();

            return redirect()->back()->with('success', 'Your inquiry has been submitted successfully! You will receive updates on your registered email.');
        } catch (\Exception $e) {
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
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('content', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('category', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
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

        // Get unique categories for filter dropdown
        $categories = Inquiry::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        return view('inquiry.User.user_public_inq', compact('inquiries', 'categories'));
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
}


