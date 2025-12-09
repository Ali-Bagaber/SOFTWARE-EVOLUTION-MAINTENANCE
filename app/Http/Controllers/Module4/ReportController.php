<?php

namespace App\Http\Controllers\Module4;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $agencyFilter = $request->get('agency_id', 'all');
        $categoryFilter = $request->get('category', 'all');

        // Agencies for dropdown
        $agenciesList = Agency::orderBy('agency_name')->get();

        // Categories for dropdown
        $categories = Inquiry::whereNotNull('category')->distinct()->pluck('category')->filter()->sort()->values();

        // Build base query for inquiries
        $inquiryQuery = Inquiry::with('verificationProcesses'); // Eager load verificationProcesses (plural)

        $inquiryQuery->where(function ($query) use ($dateFrom, $dateTo) {
            $query->whereDate('date_submitted', '>=', Carbon::parse($dateFrom))
                  ->whereDate('date_submitted', '<=', Carbon::parse($dateTo));
        });

        if ($categoryFilter !== 'all') {
            $inquiryQuery->where('category', $categoryFilter);
        }
        
        // Updated agency filter
        if ($agencyFilter !== 'all') {
            $inquiryQuery->whereHas('verificationProcesses', function ($q) use ($agencyFilter) { // Use plural
                $q->where('staff_agency_id', $agencyFilter);
                // If multiple verification processes can exist, you might need to specify which one
                // e.g., ->where('is_current_assignment', true) or order by date and take the latest.
            });
        }
        $inquiries = $inquiryQuery->get();

        // Define resolved statuses
        $resolvedStatuses = ['Rejected', 'Verified as True', 'Identified as Fake'];
        $pendingStatus = 'Under Investigation'; // Defined pending status

        // Prepare agency performance data
        $agencies = ($agencyFilter !== 'all')
            ? $agenciesList->where('agency_id', $agencyFilter)
            : $agenciesList;
            
        $performance = [];
        $chartLabels = [];
        $chartAssigned = [];
        $chartResolved = [];
        $chartPending = [];

        foreach ($agencies as $agency) {
            $currentAgencyId = $agency->agency_id;
            $agencyInquiries = $inquiries->filter(function ($inq) use ($currentAgencyId) {
                // Access the first verification process, assuming it holds the relevant assignment info
                $verificationProcess = $inq->verificationProcesses->first();
                return $verificationProcess && $verificationProcess->staff_agency_id == $currentAgencyId;
            });

            $assignedCount = $agencyInquiries->count();
            $resolvedCount = $agencyInquiries->whereIn('status', $resolvedStatuses)->count();
            
            // Updated pending count
            $pendingCount = $agencyInquiries->where('status', $pendingStatus)->count();
            
            // Updated delayed count
            $delayedCount = $agencyInquiries->filter(function ($inq) use ($pendingStatus) { // Removed $resolvedStatuses from here
                // Check if status is the defined pending status
                $isPending = $inq->status === $pendingStatus;
                // Ensure date_submitted is a Carbon instance
                $dateSubmitted = $inq->date_submitted instanceof Carbon ? $inq->date_submitted : Carbon::parse($inq->date_submitted);
                return $isPending && $dateSubmitted && $dateSubmitted->lt(now()->subDays(7));
            })->count();

            $resolutionTimes = $agencyInquiries->whereIn('status', $resolvedStatuses)->map(function ($inq) {
                $verificationProcess = $inq->verificationProcesses->first(); // Get the first VP record
                $assignedAt = $verificationProcess && $verificationProcess->assigned_at 
                                ? ($verificationProcess->assigned_at instanceof Carbon ? $verificationProcess->assigned_at : Carbon::parse($verificationProcess->assigned_at))
                                : null;
                $updatedAt = $inq->updated_at instanceof Carbon ? $inq->updated_at : Carbon::parse($inq->updated_at);
                if ($assignedAt && $updatedAt) {
                    return $assignedAt->diffInDays($updatedAt);
                }
                return null;
            })->filter();
            $avgResolution = $resolutionTimes->count() ? round($resolutionTimes->avg(), 2) : null;
            
            $performance[] = [
                'agency_name' => $agency->agency_name,
                'assigned' => $assignedCount,
                'resolved' => $resolvedCount,
                'avg_resolution' => $avgResolution,
                'pending' => $pendingCount,
                'delayed' => $delayedCount,
            ];
            $chartLabels[] = $agency->agency_name;
            $chartAssigned[] = $assignedCount;
            $chartResolved[] = $resolvedCount;
            $chartPending[] = $pendingCount;
        }

        return view('module_4.MCMC_Admin.report', [
            'performance' => $performance,
            'agenciesList' => $agenciesList,
            'categories' => $categories,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'agencyFilter' => $agencyFilter,
            'categoryFilter' => $categoryFilter,
            'chartLabels' => $chartLabels,
            'chartAssigned' => $chartAssigned,
            'chartResolved' => $chartResolved,
            'chartPending' => $chartPending,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = Pdf::loadView('module_4.MCMC_Admin.report_pdf', $data);
        return $pdf->download('agency_performance_report_' . now()->format('Ymd') . '.pdf');
    }

    // Refactor the data logic from index() into this helper
    private function getReportData(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $agencyFilter = $request->get('agency_id', 'all');
        $categoryFilter = $request->get('category', 'all');

        $agenciesList = Agency::orderBy('agency_name')->get(); 
        $categories = Inquiry::whereNotNull('category')->distinct()->pluck('category')->filter()->sort()->values();

        $inquiryQuery = Inquiry::with('verificationProcesses'); // Eager load verificationProcesses (plural)

         $inquiryQuery->where(function ($query) use ($dateFrom, $dateTo) {
            $query->whereDate('date_submitted', '>=', Carbon::parse($dateFrom))
                  ->whereDate('date_submitted', '<=', Carbon::parse($dateTo));
        });

        if ($categoryFilter !== 'all') {
            $inquiryQuery->where('category', $categoryFilter);
        }
        
        if ($agencyFilter !== 'all') {
            $inquiryQuery->whereHas('verificationProcesses', function ($q) use ($agencyFilter) { // Use plural
                $q->where('staff_agency_id', $agencyFilter);
            });
        }
        $inquiries = $inquiryQuery->get();

        $resolvedStatuses = ['Rejected', 'Verified as True', 'Identified as Fake'];
        $pendingStatus = 'Under Investigation'; // Defined pending status

        $agencies = ($agencyFilter !== 'all')
            ? $agenciesList->where('agency_id', $agencyFilter)
            : $agenciesList;
        $performance = [];
        $chartLabels = [];
        $chartAssigned = [];
        $chartResolved = [];
        $chartPending = [];
        foreach ($agencies as $agency) {
            $currentAgencyId = $agency->agency_id;
            $agencyInquiries = $inquiries->filter(function ($inq) use ($currentAgencyId) {
                $verificationProcess = $inq->verificationProcesses->first();
                return $verificationProcess && $verificationProcess->staff_agency_id == $currentAgencyId;
            });

            $assignedCount = $agencyInquiries->count();
            $resolvedCount = $agencyInquiries->whereIn('status', $resolvedStatuses)->count();

            // Updated pending count
            $pendingCount = $agencyInquiries->where('status', $pendingStatus)->count();

            // Updated delayed count
            $delayedCount = $agencyInquiries->filter(function ($inq) use ($pendingStatus) { // Removed $resolvedStatuses from here
                $isPending = $inq->status === $pendingStatus;
                // Ensure date_submitted is a Carbon instance
                $dateSubmitted = $inq->date_submitted instanceof Carbon ? $inq->date_submitted : Carbon::parse($inq->date_submitted);
                return $isPending && $dateSubmitted && $dateSubmitted->lt(now()->subDays(7));
            })->count();
            
            $resolutionTimes = $agencyInquiries->whereIn('status', $resolvedStatuses)->map(function ($inq) {
                $verificationProcess = $inq->verificationProcesses->first();
                $assignedAt = $verificationProcess && $verificationProcess->assigned_at 
                                ? ($verificationProcess->assigned_at instanceof Carbon ? $verificationProcess->assigned_at : Carbon::parse($verificationProcess->assigned_at))
                                : null;
                $updatedAt = $inq->updated_at instanceof Carbon ? $inq->updated_at : Carbon::parse($inq->updated_at);
                if ($assignedAt && $updatedAt) {
                    return $assignedAt->diffInDays($updatedAt);
                }
                return null;
            })->filter();
            $avgResolution = $resolutionTimes->count() ? round($resolutionTimes->avg(), 2) : null;
            
            $performance[] = [
                'agency_name' => $agency->agency_name,
                'assigned' => $assignedCount,
                'resolved' => $resolvedCount,
                'avg_resolution' => $avgResolution,
                'pending' => $pendingCount,
                'delayed' => $delayedCount,
            ];
            $chartLabels[] = $agency->agency_name;
            $chartAssigned[] = $assignedCount;
            $chartResolved[] = $resolvedCount;
            $chartPending[] = $pendingCount;
        }
        return [
            'performance' => $performance,
            'agenciesList' => $agenciesList,
            'categories' => $categories,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'agencyFilter' => $agencyFilter,
            'categoryFilter' => $categoryFilter,
            'chartLabels' => $chartLabels,
            'chartAssigned' => $chartAssigned,
            'chartResolved' => $chartResolved,
            'chartPending' => $chartPending,
        ];
    }
}
