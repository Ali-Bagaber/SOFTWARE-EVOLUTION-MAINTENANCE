<?php

namespace App\Http\Controllers\Module3_Controoler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;

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

        // Get available agencies - you can customize this logic as needed
        $agencies = collect();

        try {
            // Check if Agency model exists
            if (class_exists('App\Models\Agency')) {
                $agencies = \App\Models\Agency::all();
            }
        } catch (\Exception $e) {
            // Handle any errors gracefully
            $agencies = collect();
        }

        return view('Module3.PublicUser.DisplayAgencyInfo', [
            'user' => $user,
            'agencies' => $agencies
        ]);
    }
}
