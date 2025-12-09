<?php

namespace App\Http\Controllers\Module4;

use App\Http\Controllers\Controller;
use App\Models\Module4\Inquiry;
use App\Models\Module4\VerificationProcess;
use App\Models\Module4\Inquiry_history;
use App\Models\Module4\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function show($inquiry_id)
    {
        $inquiry = Inquiry::with(['user', 'history', 'verificationProcess'])
            ->findOrFail($inquiry_id);

        return view('module_4.user_public.show', compact('inquiry'));
    }

    public function index(Request $request)
    {
        // Start with a base query
        $query = Inquiry::query();

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply date filter
        if ($request->filled('date')) {
            $date = Carbon::now();
            switch ($request->date) {
                case 'today':
                    $query->whereDate('created_at', $date);
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        $date->copy()->startOfWeek(),
                        $date->copy()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereYear('created_at', $date->year)
                          ->whereMonth('created_at', $date->month);
                    break;
                case 'year':
                    $query->whereYear('created_at', $date->year);
                    break;
            }
        }

        // Get counts for each status (only for current user's inquiries)
        $userId = Auth::id();
        $pending = Inquiry::where('public_user_id', $userId)->where('status', 'Pending')->count();
        $underInvestigation = Inquiry::where('public_user_id', $userId)->underInvestigation()->count();
        $verifiedTrue = Inquiry::where('public_user_id', $userId)->verifiedTrue()->count();
        $identifiedFake = Inquiry::where('public_user_id', $userId)->identifiedFake()->count();
        $rejected = Inquiry::where('public_user_id', $userId)->rejected()->count();

        // Get monthly data for trend chart
        $monthlyData = collect();
        $monthlyLabels = collect();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyLabels->push($date->format('M Y'));
            
            $monthlyData->push(
                Inquiry::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );
        }

        // Get filtered inquiries with pagination
        $inquiries = $query->with(['user', 'verificationProcess'])
            ->latest()
            ->paginate(10)
            ->withQueryString(); // This preserves the filter parameters in pagination links

        return view('module_4.user_public.dashboard', compact(
            'pending',
            'underInvestigation',
            'verifiedTrue',
            'identifiedFake',
            'rejected',
            'monthlyLabels',
            'monthlyData',
            'inquiries'
        ));
    }

    public function updateStatus(Request $request, $inquiry_id)
    {
        $inquiry = Inquiry::findOrFail($inquiry_id);
        $oldStatus = $inquiry->status;
        $newStatus = $request->status;

        // Update inquiry status
        $inquiry->update([
            'status' => $newStatus
        ]);

        // Always create a history record for any status change
        Inquiry_history::create([
            'inquiry_id' => $inquiry_id,
            'new_status' => $newStatus,
            'user_id' => Auth::id(),
            'timestamp' => now(),
            'agency_id' => 1 // Use existing agency ID or update as needed
        ]);

        // Create verification process record if needed
        if ($newStatus !== $oldStatus) {
            VerificationProcess::create([
                'inquiry_id' => $inquiry_id,
                'status' => $newStatus,
                'verified_by' => Auth::id(),
                'verification_date' => now(),
                'remarks' => $request->remarks
            ]);

            // Create notification for status change
            Notification::createNotification(
                $inquiry->user_id,
                $inquiry_id,
                "Your inquiry status has been updated to {$newStatus}"
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Inquiry status updated successfully'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // Add other validation rules as needed
        ]);

        $inquiry = Inquiry::create([
            'public_user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'status' => 'Under Investigation', // Default status
            'date_submitted' => now(),
            // Add other fields as needed
        ]);

        // Create notification for new inquiry submission
        Notification::createNotification(
            Auth::id(),
            $inquiry->inquiry_id,
            'Your inquiry has been submitted.'
        );

        return redirect()->route('module4.inquiry.show', ['inquiry_id' => $inquiry->inquiry_id])
            ->with('success', 'Inquiry submitted successfully!');
    }

    /**
     * Display the MCMC Admin dashboard with inquiry statistics.
     *
     * @return \Illuminate\View\View
     */
    public function mcmcDashboard()
    {
        // Get all possible statuses
        $statuses = ['Pending', 'Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'];
        
        // Count inquiries by status
        $statusCounts = [];
        foreach ($statuses as $status) {
            $statusCounts[$status] = Inquiry::where('status', $status)->count();
        }
        
        // Get recent inquiries
        $recentInquiries = Inquiry::orderBy('date_submitted', 'desc')
            ->take(10)
            ->get();
            
        // Generate monthly trends data for the last 6 months
        $monthlyTrends = [];
        $monthlyLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabel = $date->format('M Y'); // e.g., "Jun 2023"
            $monthlyLabels[] = $monthLabel;
            
            // Count inquiries for this month
            $count = Inquiry::whereYear('date_submitted', $date->year)
                ->whereMonth('date_submitted', $date->month)
                ->count();
                
            $monthlyTrends[] = $count;
        }
            
        return view('module_4.MCMC_Admin.dashboard', [
            'statuses' => $statuses,
            'statusCounts' => $statusCounts,
            'recentInquiries' => $recentInquiries,
            'monthlyLabels' => $monthlyLabels,
            'monthlyTrends' => $monthlyTrends
        ]);
    }
}
