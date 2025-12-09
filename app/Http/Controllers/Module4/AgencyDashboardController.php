<?php

namespace App\Http\Controllers\Module4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgencyDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $agencyId = $user->agency_id;

        // 1. Statuses
        $statuses = Inquiry::distinct()->pluck('status')->filter()->sort()->values();

        // 2. Status Counts (for this agency)
        $statusList = ['Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'];
        $statusCounts = [];
        foreach ($statusList as $status) {
            $statusCounts[$status] = Inquiry::join('verification_processes', 'inquiries.inquiry_id', '=', 'verification_processes.inquiry_id')
                ->where('verification_processes.staff_agency_id', $agencyId)
                ->where('inquiries.status', $status)
                ->count();
        }

        // 3. Recent Inquiries (for this agency)
        $recentInquiries = Inquiry::join('verification_processes', 'inquiries.inquiry_id', '=', 'verification_processes.inquiry_id')
            ->where('verification_processes.staff_agency_id', $agencyId)
            ->orderBy('inquiries.date_submitted', 'desc')
            ->take(10)
            ->get(['inquiries.*']);

        // 4. Monthly Trends (for this agency, last 6 months)
        $monthlyLabels = [];
        $monthlyData = [];
        $start = Carbon::now()->subMonths(5)->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $period = new \DatePeriod($start, new \DateInterval('P1M'), $end->addMonth());
        foreach ($period as $month) {
            $carbonMonth = \Carbon\Carbon::instance($month);
            $monthlyLabels[] = $carbonMonth->format('M');
            $monthlyData[] = Inquiry::join('verification_processes', 'inquiries.inquiry_id', '=', 'verification_processes.inquiry_id')
                ->where('verification_processes.staff_agency_id', $agencyId)
                ->whereMonth('inquiries.date_submitted', $carbonMonth->month)
                ->whereYear('inquiries.date_submitted', $carbonMonth->year)
                ->count();
        }

        return view('module_4.agency.dashboard', compact(
            'statuses', 'statusCounts', 'recentInquiries', 'monthlyLabels', 'monthlyData'
        ));
    }
} 