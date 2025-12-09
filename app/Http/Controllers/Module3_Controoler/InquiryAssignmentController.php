<?php

namespace App\Http\Controllers\Module3_Controoler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;
use App\Models\Models_Module3\VerificationProcess;

class InquiryAssignmentController extends Controller
{
    /**
     * Show the view assigned agencies page for public users
     */
    public function showViewAssigned()
    {
        $user = Auth::user();

        if (!$user || $user->user_role !== 'publicuser') {
            return redirect()->route('publicuser.login')
                ->with('error', 'Unauthorized access.');
        }

        // Get user's inquiries with their assigned agencies through verification processes
        $userInquiries = collect();

        try {
            if (class_exists('App\Models\Inquiry')) {
                $userInquiries = Inquiry::where('public_user_id', $user->user_id)
                    ->with([
                        'verificationProcesses' => function($query) {
                            $query->with('agency');
                        },
                        'user'
                    ])
                    ->orderBy('date_submitted', 'desc')
                    ->get();
            }
        } catch (\Exception $e) {
            // Handle any errors gracefully
            $userInquiries = collect();
        }

        return view('Module3.PublicUser.ViewAssignedAgency', [
            'user' => $user,
            'userInquiries' => $userInquiries
        ]);
    }

    /**
     * Show the agency information page for public users
     */
    public function showAgencyInfo()
    {
        $user = Auth::user();

        if (!$user || $user->user_role !== 'publicuser') {
            return redirect()->route('publicuser.login')
                ->with('error', 'Unauthorized access.');
        }

        // Get agencies assigned to this user's inquiries through verification_processes
        $agencies = collect();
        $userInquiries = collect();

        try {
            if (class_exists('App\Models\Inquiry') && class_exists('App\Models\Agency')) {
                // Get the user's inquiries with verification processes and agencies
                $userInquiries = \App\Models\Inquiry::where('public_user_id', $user->user_id)
                    ->with([
                        'verificationProcesses' => function($query) {
                            $query->with('agency');
                        }
                    ])
                    ->get();

                // Extract unique agencies from verification processes
                $agencyIds = $userInquiries->flatMap(function($inquiry) {
                    return $inquiry->verificationProcesses->pluck('staff_agency_id');
                })->unique()->filter()->toArray();

                // Get agency details
                if (!empty($agencyIds)) {
                    $agencies = \App\Models\Agency::whereIn('agency_id', $agencyIds)->get();
                }
            }
        } catch (\Exception $e) {
            // Handle any errors gracefully
            $agencies = collect();
            $userInquiries = collect();
        }

        return view('Module3.PublicUser.DisplayAgencyInfo', [
            'user' => $user,
            'agencies' => $agencies,
            'userInquiries' => $userInquiries
        ]);
    }

    /**
     * Show the assignment notes page for admin users
     */
    public function showAdminAssignmentNotes()
    {
        $user = Auth::user();

        if (!$user || $user->user_role !== 'admin') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }

        // Get inquiries for admin notes management
        $inquiries = collect();

        try {
            if (class_exists('App\Models\Inquiry')) {
                $inquiries = Inquiry::with('user')
                    ->whereIn('status', ['assigned', 'in_progress'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } catch (\Exception $e) {
            // Handle any errors gracefully
            $inquiries = collect();
        }

        return view('Module3.MCMCStaff.AddAssignmentNotes', [
            'user' => $user,
            'inquiries' => $inquiries
        ]);
    }

    /**
     * Add or update assignment notes for an inquiry
     */
    public function addAssignmentNotes(Request $request)
    {
        $request->validate([
            'inquiry_id' => 'required|integer|exists:inquiries,inquiry_id',
            'assignment_notes' => 'required|string|max:1000'
        ]);

        try {
            $inquiry = Inquiry::findOrFail($request->inquiry_id);

            $inquiry->assignment_notes = $request->assignment_notes;
            $inquiry->updated_at = now();
            $inquiry->save();

            return redirect()->back()->with('success', 'Assignment notes added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add assignment notes. Please try again.');
        }
    }

    /**
     * Show the submitted inquiries page for admin users
     */
    public function showViewSubmittedInquiries()
    {
        $user = Auth::user();

        if (!$user || $user->user_role !== 'admin') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }

        // Get all inquiries for admin view with relationships
        $inquiries = collect();
        $agencies = collect();
        $statistics = [
            'total' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'closed' => 0
        ];

        try {
            if (class_exists('App\Models\Inquiry')) {
                $inquiries = Inquiry::with(['user', 'verificationProcesses.agency'])
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Calculate statistics
                $statistics = [
                    'total' => $inquiries->count(),
                    'pending' => $inquiries->where('status', 'Pending')->count(),
                    'in_progress' => $inquiries->where('status', 'In Progress')->count(),
                    'resolved' => $inquiries->where('status', 'Resolved')->count(),
                    'closed' => $inquiries->where('status', 'Closed')->count()
                ];
            }

            // Get all agencies for assignment dropdown
            if (class_exists('App\Models\Agency')) {
                $agencies = \App\Models\Agency::orderBy('agency_name')->get();
            }
        } catch (\Exception $e) {
            // Handle any errors gracefully
            $inquiries = collect();
            $agencies = collect();
        }

        return view('Module3.MCMCStaff.ViewSubmittedInquiries', [
            'user' => $user,
            'inquiries' => $inquiries,
            'agencies' => $agencies,
            'statistics' => $statistics
        ]);
    }

    /**
     * Show the review and assign inquiries page for admin users
     */
    public function showReviewAssignInquiries()
    {
        $user = Auth::user();

        if (!$user || $user->user_role !== 'admin') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }

        // Get pending inquiries that need to be reviewed and assigned
        $inquiries = collect();
        $statistics = [
            'total_pending' => 0,
            'unassigned' => 0,
            'high_priority' => 0,
            'overdue' => 0
        ];

        try {
            if (class_exists('App\Models\Inquiry')) {
                // Get pending inquiries for review and assignment
                $inquiries = Inquiry::with(['user', 'verificationProcesses'])
                    ->whereIn('status', ['Pending', 'Under Review'])
                    ->orderBy('created_at', 'asc') // Oldest first for FIFO processing
                    ->get();

                // Get assigned inquiry IDs
                $assignedInquiryIds = VerificationProcess::pluck('inquiry_id')->toArray();

                // Calculate statistics
                $statistics = [
                    'total_pending' => $inquiries->count(),
                    'unassigned' => $inquiries->whereNotIn('inquiry_id', $assignedInquiryIds)->count(),
                    'high_priority' => $inquiries->where('category', 'urgent')->count(),
                    'overdue' => $inquiries->where('created_at', '<', now()->subDays(7))->count()
                ];
            }
        } catch (\Exception $e) {
            // Handle any errors gracefully
            $inquiries = collect();
        }

        return view('Module3.MCMCStaff.ReviewAssignInquiries', [
            'user' => $user,
            'inquiries' => $inquiries,
            'statistics' => $statistics
        ]);
    }

    /**
     * Assign inquiry to agency
     */
    public function assignInquiry(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->user_role !== 'admin') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'inquiry_id' => 'required|integer',
            'agency_id' => 'required|integer',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            // Find inquiry by inquiry_id
            $inquiry = Inquiry::where('inquiry_id', $request->inquiry_id)
                ->firstOrFail();

            // Get agency name for logging
            $agency = null;
            if (class_exists('App\Models\Agency')) {
                $agency = \App\Models\Agency::find($request->agency_id);
            }

            // Validate agency_id is present
            if (!$request->agency_id) {
                return redirect()->back()->with('error', 'Assigned agency_id is missing!');
            }

            // Create verification process record
            $verificationProcess = new VerificationProcess();
            $verificationProcess->inquiry_id = $inquiry->inquiry_id;
            $verificationProcess->MCMC_ID = $user->user_id; // Set to the admin who is doing the assignment
            $verificationProcess->staff_agency_id = $request->agency_id; // Set to the agency being assigned
            $verificationProcess->priority = $request->priority;
            $verificationProcess->notes = $request->notes;
            $verificationProcess->assigned_at = now();
            $verificationProcess->save();

            // Update inquiry status only
            $inquiry->status = 'Under Investigation';
            $inquiry->updated_at = now();
            $inquiry->save();

            // Log status change in history
            \App\Models\Module4\Inquiry_history::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'new_status' => 'Under Investigation',
                'user_id' => $user->user_id,
                'timestamp' => now(),
                'agency_id' => $request->agency_id
            ]);

            // Notify the public user about the assignment
            $agencyName = $agency ? $agency->agency_name : ('Agency ID ' . $request->agency_id);
            $notificationMessage = "Your inquiry '{$inquiry->title}' has been assigned to the agency: {$agencyName} for review.";
            \App\Models\Module4\Notification::createNotification(
                $inquiry->public_user_id,
                $inquiry->inquiry_id,
                $notificationMessage
            );

            return redirect()->route('admin.inquiries')
                ->with('success', "Inquiry #{$inquiry->inquiry_id} successfully assigned to {$agencyName}!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to assign inquiry. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the assignment form for a specific inquiry
     */
    public function showAssignmentForm($inquiryId)
    {
        $user = Auth::user();

        if (!$user || $user->user_role !== 'admin') {
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }

        try {
            // Get the inquiry
            $inquiry = Inquiry::with('user')->findOrFail($inquiryId);

            // Get all agencies
            $agencies = collect();
            if (class_exists('App\Models\Agency')) {
                $agencies = \App\Models\Agency::orderBy('agency_name')->get();
            }

            return view('Module3.MCMCStaff.AssignAndAddNotes', [
                'user' => $user,
                'inquiry' => $inquiry,
                'agencies' => $agencies
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.inquiries')
                ->with('error', 'Inquiry not found or error loading assignment form.');
        }
    }
}
