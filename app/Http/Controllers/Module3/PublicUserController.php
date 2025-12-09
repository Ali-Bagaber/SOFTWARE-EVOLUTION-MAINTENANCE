<?php

namespace App\Http\Controllers\Module3;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\VerificationProcess;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicUserController extends Controller
{
    /**
     * Display assigned agency information for the user
     */
    public function viewAssignedAgency()
    {
        $user = Auth::user();
        
        // Get user's inquiries with verification processes and agencies
        $userInquiries = Inquiry::where('public_user_id', $user->user_id)
            ->with([
                'verificationProcesses' => function($query) {
                    $query->with('agency');
                },
                'user'
            ])
            ->orderBy('date_submitted', 'desc')
            ->get();

        return view('Module3.PublicUser.ViewAssignedAgency', compact('userInquiries'));
    }

    /**
     * Display detailed agency information
     */
    public function displayAgencyInfo($agencyId = null)
    {
        if ($agencyId) {
            $agency = Agency::findOrFail($agencyId);
            $relatedInquiries = Inquiry::where('user_id', Auth::id())
                ->where('assigned_agency_id', $agencyId)
                ->with('verificationProcesses')
                ->get();
        } else {
            $agency = null;
            $relatedInquiries = collect();
        }

        // Get all agencies for the dropdown/selection
        $agencies = Agency::where('status', 'active')->get();

        return view('Module3.PublicUser.DisplayAgencyInfo', compact('agency', 'agencies', 'relatedInquiries'));
    }

    /**
     * Get agency details via AJAX
     */
    public function getAgencyDetails($agencyId)
    {
        $agency = Agency::findOrFail($agencyId);
        
        return response()->json([
            'success' => true,
            'agency' => [
                'id' => $agency->id,
                'name' => $agency->name,
                'description' => $agency->description,
                'contact_email' => $agency->contact_email,
                'contact_phone' => $agency->contact_phone,
                'address' => $agency->address,
                'website' => $agency->website,
                'services' => $agency->services,
                'status' => $agency->status
            ]
        ]);
    }

    /**
     * Search agencies by criteria
     */
    public function searchAgencies(Request $request)
    {
        $query = Agency::where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('services', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $location = $request->input('location');
            $query->where('address', 'like', "%{$location}%");
        }

        $agencies = $query->paginate(10);

        return response()->json([
            'success' => true,
            'agencies' => $agencies->items(),
            'pagination' => [
                'current_page' => $agencies->currentPage(),
                'last_page' => $agencies->lastPage(),
                'total' => $agencies->total()
            ]
        ]);
    }
}
