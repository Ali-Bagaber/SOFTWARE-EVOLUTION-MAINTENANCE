<?php

namespace App\Http\Controllers\Module3_Controoler;

use App\Http\Controllers\Controller;
use App\Models\inquiry;
use App\Models\public_user;
use App\Models\Agency;
use App\Models\Models_Module3\VerificationProcess;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgencyReviewAndNotificationController extends Controller
{
    /**
     * Display the inquiry details page for agency staff
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showDisplayInquiryDetails(Request $request)
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

        // Get search parameters
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $category = $request->get('category', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Build query for inquiries
        $query = inquiry::with(['user', 'verificationProcesses']);

        // If agency is defined, filter by assigned inquiries
        if ($agency) {
            $inquiryIds = VerificationProcess::where('staff_agency_id', $agency->agency_id)
                ->pluck('inquiry_id');
            $query->whereIn('inquiry_id', $inquiryIds);
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%")
                    ->orWhere('inquiry_id', 'LIKE', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Apply category filter
        if ($category !== 'all') {
            $query->where('category', $category);
        }

        // Apply date range filter
        if ($dateFrom) {
            $query->whereDate('date_submitted', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date_submitted', '<=', $dateTo);
        }

        // Order by most recent first
        $query->orderBy('date_submitted', 'desc');

        // Handle export functionality - redirect to comprehensive inquiry list
        if ($request->has('export') && $request->input('export') === 'csv') {
            return redirect()->route('agency.inquiry.list', array_merge($request->all(), ['export' => 'csv']));
        }

        // Get paginated results
        $inquiries = $query->paginate(15)->withQueryString();

        // Get statistics for the dashboard
        $stats = $this->getInquiryStatistics($agency);

        // Get available categories for filter
        $categories = inquiry::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        // Get available statuses for filter
        $statuses = inquiry::distinct()->pluck('status')->filter()->sort();

        return view('Module3.AgencyStaff.DisplayInquriryDetails', compact(
            'inquiries',
            'stats',
            'categories',
            'statuses',
            'agency',
            'search',
            'status',
            'category',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Get inquiry statistics for the agency
     *
     * @param mixed $agency
     * @return array
     */
    private function getInquiryStatistics($agency)
    {
        $baseQuery = inquiry::query();

        if ($agency) {
            $inquiryIds = VerificationProcess::where('staff_agency_id', $agency->agency_id)
                ->pluck('inquiry_id');
            $baseQuery->whereIn('inquiry_id', $inquiryIds);
        }

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Under Investigation', 'Pending Review'])->count();
        $accepted = (clone $baseQuery)->where('status', 'Accepted')->count();
        $inProgress = (clone $baseQuery)->where('status', 'In Progress')->count();
        $resolved = (clone $baseQuery)->where('status', 'Resolved')->count();
        $rejected = (clone $baseQuery)->where('status', 'Rejected')->count();
        $assigned = (clone $baseQuery)->whereHas('verificationProcesses', function ($query) use ($agency) {
            if ($agency) {
                $query->where('staff_agency_id', $agency->agency_id);
            }
        })->count();

        // Calculate overdue (more than 7 days old and not resolved)
        $overdue = (clone $baseQuery)->where('status', '!=', 'Resolved')
            ->where('status', '!=', 'Closed')
            ->where('date_submitted', '<', now()->subDays(7))
            ->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'accepted' => $accepted,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'rejected' => $rejected,
            'assigned' => $assigned,
            'overdue' => $overdue,
            'completed' => $resolved // Add alias for completed
        ];
    }

    /**
     * Show individual inquiry details for agency review
     *
     * @param int $inquiryId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showInquiryDetail($inquiryId)
    {
        try {
            $inquiry = inquiry::with(['user', 'verificationProcesses'])->findOrFail($inquiryId);

            // Check if agency has access to this inquiry
            $user = Auth::user();
            $agency = null;

            if (method_exists($user, 'agency')) {
                $agency = $user->agency;
            } elseif (property_exists($user, 'agency_id')) {
                $agency = Agency::find($user->agency_id);
            }

            // Verify access using verification_processes
            $verificationProcess = null;
            if ($agency) {
                $hasAccess = VerificationProcess::where('inquiry_id', $inquiryId)
                    ->where('staff_agency_id', $agency->agency_id)
                    ->exists();
                if (!$hasAccess) {
                    // Check if it's an AJAX request
                    if (request()->wantsJson() || request()->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You do not have permission to view this inquiry.'
                        ], 403);
                    }
                    return redirect()->back()->with('error', 'You do not have permission to view this inquiry.');
                }
                // Get the latest verification_processes record for this agency/inquiry
                $verificationProcess = VerificationProcess::where('inquiry_id', $inquiryId)
                    ->where('staff_agency_id', $agency->agency_id)
                    ->latest('updated_at')
                    ->first();
            }

            // Check if it's an AJAX request
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'inquiry' => $inquiry->load('user'),
                    'verification_process' => $verificationProcess
                ]);
            }

            // Return view for browser requests
            return view('Module3.AgencyStaff.ViewInquiryDetail', compact('inquiry', 'verificationProcess', 'agency'));
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inquiry not found.'
                ], 404);
            }
            return redirect()->back()->with('error', 'Inquiry not found.');
        }
    }

    /**
     * Update inquiry status (agency action)
     *
     * @param \Illuminate\Http\Request $request
     * @param int $inquiryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInquiryStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in progress,resolved,closed,completed,accepted,rejected,Pending,In Progress,Resolved,Closed,Completed,Accepted,Rejected',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Normalize status to proper case
        $normalizedStatus = ucfirst(strtolower($request->status));
        if ($normalizedStatus === 'In progress') {
            $normalizedStatus = 'In Progress';
        }

        try {
            $inquiry = inquiry::findOrFail($id);

            // Check if agency has permission to update this inquiry
            $user = Auth::user();
            $agency = null;

            if (method_exists($user, 'agency')) {
                $agency = $user->agency;
            } elseif (property_exists($user, 'agency_id')) {
                $agency = Agency::find($user->agency_id);
            }

            // If we still don't have agency, try to get it from the verification_processes
            if (!$agency) {
                $verificationProcess = VerificationProcess::where('inquiry_id', $inquiry->inquiry_id)
                    ->first();
                if ($verificationProcess) {
                    $agency = Agency::find($verificationProcess->staff_agency_id);
                }
            }

            // Check access using verification_processes
            if ($agency) {
                $hasAccess = VerificationProcess::where('inquiry_id', $inquiry->inquiry_id)
                    ->where('staff_agency_id', $agency->agency_id)
                    ->exists();

                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to update this inquiry.'
                    ], 403);
                }
            }

            // Store old status for logging
            $oldStatus = $inquiry->status;

            // Update status
            $inquiry->status = $normalizedStatus;
            $inquiry->save();

            // Always create history record for status changes
            \App\Models\inquiry_history::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'new_status' => $normalizedStatus,
                'user_id' => $user->user_id ?? null,
                'timestamp' => now(),
                'agency_id' => $agency->agency_id ?? null
            ]);

            // Log the status change in VerificationProcess if it's a completion or important status change
            if (in_array(strtolower($normalizedStatus), ['completed', 'resolved', 'closed']) && $agency) {
                VerificationProcess::create([
                    'inquiry_id' => $inquiry->inquiry_id,
                    'staff_agency_id' => $agency->agency_id,
                    'MCMC_ID' => $user->user_id ?? null,
                    'assigned_date' => now(),
                    'status_update' => strtolower($normalizedStatus),
                    'note' => $request->notes,
                    'explanation_text' => "Status updated from '{$oldStatus}' to '{$normalizedStatus}' by agency staff.",
                    'confirmed_at' => now()
                ]);
            }

            // Send notification for important status changes
            if (in_array($normalizedStatus, ['Verified as True', 'Identified as Fake', 'Rejected'])) {
                $notificationMessage = match ($normalizedStatus) {
                    'Verified as True' => "Good news! Your inquiry '{$inquiry->title}' has been verified as TRUE. The information has been confirmed as genuine and accurate.",
                    'Identified as Fake' => "Your inquiry '{$inquiry->title}' has been investigated and IDENTIFIED AS FAKE NEWS. The information has been determined to be false or misleading.",
                    'Rejected' => "Your inquiry '{$inquiry->title}' has been REJECTED after investigation.",
                    default => "Your inquiry '{$inquiry->title}' status has been updated to: {$normalizedStatus}"
                };

                \App\Models\Module4\Notification::createNotification(
                    $inquiry->public_user_id,
                    $inquiry->inquiry_id,
                    $notificationMessage
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Inquiry status updated successfully.',
                'new_status' => $normalizedStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Update inquiry status error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update inquiry status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show Accept/Reject Inquiries page for agency staff
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showAcceptRejectInquiries(Request $request)
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

        // Build query for pending inquiries that need accept/reject decision
        $query = inquiry::with(['user']);

        // Filter for inquiries assigned to this agency that are pending review
        if ($agency) {
            $query->where('assigned_agency_id', $agency->agency_id);
        }

        // Only show inquiries that are pending review/acceptance
        $query->whereIn('status', ['Pending', 'Under Investigation', 'Pending Review']);

        // Apply search filter if provided
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%")
                    ->orWhere('inquiry_id', 'LIKE', "%{$search}%");
            });
        }

        // Apply category filter if provided
        $category = $request->get('category', 'all');
        if ($category !== 'all') {
            $query->where('category', $category);
        }

        // Order by most recent first
        $query->orderBy('date_submitted', 'desc');

        // Get paginated results
        $inquiries = $query->paginate(10)->withQueryString();

        // Get statistics for pending inquiries
        $stats = $this->getAcceptRejectStatistics($agency);

        // Get available categories for filter
        $categories = inquiry::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        return view('Module3.AgencyStaff.AcceptRejectInquiries', compact(
            'inquiries',
            'stats',
            'categories',
            'agency',
            'search',
            'category'
        ));
    }

    /**
     * Get statistics for accept/reject inquiries
     *
     * @param mixed $agency
     * @return array
     */
    private function getAcceptRejectStatistics($agency)
    {
        $baseQuery = inquiry::query();

        if ($agency) {
            $baseQuery->where('assigned_agency_id', $agency->agency_id);
        }

        $totalPending = (clone $baseQuery)->whereIn('status', ['Pending', 'Under Investigation', 'Pending Review'])->count();
        $totalAccepted = (clone $baseQuery)->where('status', 'Accepted')->count();
        $totalRejected = (clone $baseQuery)->where('status', 'Rejected')->count();
        $totalAssigned = (clone $baseQuery)->whereNotNull('assigned_agency_id')->count();

        return [
            'pending_review' => $totalPending,
            'accepted' => $totalAccepted,
            'rejected' => $totalRejected,
            'total_assigned' => $totalAssigned
        ];
    }

    /**
     * Accept an inquiry
     *
     * @param \Illuminate\Http\Request $request
     * @param int $inquiryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptInquiry(Request $request, $inquiryId)
    {
        $request->validate([
            'acceptance_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $inquiry = inquiry::findOrFail($inquiryId);

            // Check if agency has permission to accept this inquiry
            $user = Auth::user();
            $agency = null;

            if (method_exists($user, 'agency')) {
                $agency = $user->agency;
            } elseif (property_exists($user, 'agency_id')) {
                $agency = Agency::find($user->agency_id);
            }

            // Check access using verification_processes
            $hasAccess = false;
            if ($agency) {
                $hasAccess = VerificationProcess::where('inquiry_id', $inquiry->inquiry_id)
                    ->where('staff_agency_id', $agency->agency_id)
                    ->exists();
            }
            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to accept this inquiry.'
                ], 403);
            }

            // Update inquiry status only
            $inquiry->status = 'Verified as True';
            $inquiry->updated_at = now();
            $inquiry->save();

            // Create history record for status change
            \App\Models\inquiry_history::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'new_status' => 'Verified as True',
                'user_id' => $user->user_id ?? null,
                'timestamp' => now(),
                'agency_id' => $agency->agency_id ?? null
            ]);

            // Log acceptance in verification_processes
            VerificationProcess::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'staff_agency_id' => $agency->agency_id,
                'MCMC_ID' => $user->user_id ?? null,
                'assigned_at' => now(),
                'notes' => $request->acceptance_notes,
                'explanation_text' => 'Inquiry accepted by agency staff.',
                'updated_at' => now(),
            ]);

            // Notify the user about the verification result
            \App\Models\Module4\Notification::createNotification(
                $inquiry->public_user_id,
                $inquiry->inquiry_id,
                "Good news! Your inquiry '{$inquiry->title}' has been verified as TRUE. The information has been confirmed as genuine and accurate."
            );

            return response()->json([
                'success' => true,
                'message' => 'Inquiry has been accepted successfully.',
                'new_status' => 'Verified as True'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept inquiry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject an inquiry
     *
     * @param \Illuminate\Http\Request $request
     * @param int $inquiryId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectInquiry(Request $request, $inquiryId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
            'rejection_comments' => 'required|string|min:20|max:1000',
            'suggested_agency' => 'nullable|string|max:255',
            'priority_level' => 'nullable|string|in:normal,high,urgent',
            'additional_notes' => 'nullable|string|max:500'
        ]);

        try {
            $inquiry = inquiry::findOrFail($inquiryId);

            // Check if agency has permission to reject this inquiry
            $user = Auth::user();
            $agency = null;

            if (method_exists($user, 'agency')) {
                $agency = $user->agency;
            } elseif (property_exists($user, 'agency_id')) {
                $agency = Agency::find($user->agency_id);
            }

            // If we still don't have agency, try to get it from the inquiry's assigned agency
            if (!$agency && $inquiry->assigned_agency_id) {
                $agency = Agency::find($inquiry->assigned_agency_id);
            }

            if (!$agency) {
                return redirect()->back()
                    ->with('error', 'Unable to determine agency information.');
            }

            // Verify access
            $hasAccess = VerificationProcess::where('inquiry_id', $inquiry->inquiry_id)
                ->where('staff_agency_id', $agency->agency_id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->route('agency.display.inquiry')
                    ->with('error', 'You do not have permission to reject this inquiry.');
            }

            // Determine status based on rejection reason
            $statusToSet = 'Rejected';
            if ($request->rejection_reason === 'fake_news' || $request->rejection_reason === 'Identified as Fake News') {
                $statusToSet = 'Identified as Fake';
            }

            // Update inquiry status only
            $inquiry->status = $statusToSet;
            $inquiry->updated_at = now();
            $inquiry->save();

            // Create history record for status change
            \App\Models\inquiry_history::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'new_status' => $statusToSet,
                'user_id' => $user->user_id ?? null,
                'timestamp' => now(),
                'agency_id' => $agency->agency_id ?? null
            ]);

            // Store rejection details in verification_processes
            VerificationProcess::create([
                'inquiry_id' => $inquiry->inquiry_id,
                'MCMC_ID' => $user->user_id,
                'staff_agency_id' => $agency->agency_id,
                'rejection_reason' => $request->rejection_reason,
                'explanation_text' => $request->rejection_comments,
                'priority' => $request->priority_level ?? 'normal',
                'notes' => $request->additional_notes,
                'updated_at' => now(),
            ]);

            // Notify the user about the rejection result
            $notificationMessage = '';
            if ($statusToSet === 'Identified as Fake') {
                $notificationMessage = "Your inquiry '{$inquiry->title}' has been investigated and IDENTIFIED AS FAKE NEWS. The information has been determined to be false or misleading.";
            } else {
                $notificationMessage = "Your inquiry '{$inquiry->title}' has been REJECTED after investigation. Reason: {$request->rejection_reason}";
            }

            \App\Models\Module4\Notification::createNotification(
                $inquiry->public_user_id,
                $inquiry->inquiry_id,
                $notificationMessage
            );

            return redirect()->route('agency.display.inquiry')
                ->with('success', 'Inquiry has been rejected successfully. User has been notified.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject inquiry: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the Add Reject Comments page
     *
     * @param int $inquiryId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showAddRejectComments($inquiryId)
    {
        try {
            $inquiry = inquiry::with(['user'])->findOrFail($inquiryId);

            // Check if agency has access to this inquiry
            $user = Auth::user();
            $agency = null;

            if (method_exists($user, 'agency')) {
                $agency = $user->agency;
            } elseif (property_exists($user, 'agency_id')) {
                $agency = Agency::find($user->agency_id);
            }

            // If we still don't have agency, try to get it from the inquiry's assigned agency
            if (!$agency && $inquiry->assigned_agency_id) {
                $agency = Agency::find($inquiry->assigned_agency_id);
            }

            if (!$agency) {
                return redirect()->route('agency.display.inquiry')
                    ->with('error', 'Unable to determine agency information.');
            }

            // Verify access
            $hasAccess = VerificationProcess::where('inquiry_id', $inquiry->inquiry_id)
                ->where('staff_agency_id', $agency->agency_id)
                ->exists();

            if (!$hasAccess) {
                return redirect()->route('agency.display.inquiry')
                    ->with('error', 'You do not have permission to reject this inquiry.');
            }

            // Check if inquiry is in a state that can be rejected
            if (!in_array(strtolower($inquiry->status), ['pending', 'under investigation', 'pending review'])) {
                return redirect()->route('agency.display.inquiry')
                    ->with('error', 'This inquiry cannot be rejected in its current status.');
            }

            return view('Module3.AgencyStaff.AddRejectComments', compact('inquiry', 'agency'));
        } catch (\Exception $e) {
            return redirect()->route('agency.display.inquiry')
                ->with('error', 'Inquiry not found.');
        }
    }

    /**
     * Handle AJAX request to notify MCMC
     */
    public function notifyMCMC(Request $request)
    {
        Log::debug('notifyMCMC method called', ['request_data' => $request->all()]);

        // Validate the request
        $request->validate([
            'inquiry_id' => 'required|integer',
            'message' => 'required|string|max:1000'
        ]);

        try {
            // Get MCMC_ID from verification_processes table based on inquiry_id
            $verificationProcess = VerificationProcess::where('inquiry_id', $request->inquiry_id)->first();

            if (!$verificationProcess || !$verificationProcess->MCMC_ID) {
                Log::error('No MCMC_ID found for inquiry_id: ' . $request->inquiry_id);
                return response()->json(['success' => false, 'error' => 'MCMC not found for this inquiry'], 404);
            }

            $mcmcId = $verificationProcess->MCMC_ID;
            Log::debug('Found MCMC_ID:', ['mcmc_id' => $mcmcId, 'inquiry_id' => $request->inquiry_id]);

            // Create notification with MCMC_ID as user_id
            $notification = Notification::create([
                'user_id'    => $mcmcId,  // Use MCMC_ID from verification_processes
                'inquiry_id' => $request->inquiry_id,
                'message'    => $request->message,
                'date_sent'  => now(),
                'is_read'    => false
            ]);

            Log::debug('Notification created successfully:', [
                'notification_id' => $notification->notification_id,
                'mcmc_id' => $mcmcId,
                'inquiry_id' => $request->inquiry_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully!',
                'notification_id' => $notification->notification_id
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating notification:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Failed to send notification'], 500);
        }
    }
}
