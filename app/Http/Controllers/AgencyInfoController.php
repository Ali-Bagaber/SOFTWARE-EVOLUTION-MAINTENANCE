<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyInfoController extends Controller
{
    /**
     * Get agency details by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgencyDetails($id)
    {
        try {
            $agency = Agency::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'agency' => [
                    'agency_id' => $agency->agency_id,
                    'agency_name' => $agency->agency_name,
                    'description' => $agency->description,
                    'contact_number' => $agency->contact_number,
                    'email' => $agency->email,
                    'address' => $agency->address,
                    'website' => $agency->website,
                    'services' => $agency->services
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Agency not found'
            ], 404);
        }
    }

    /**
     * Search agencies by name or services
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAgencies(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $query = $request->input('query');

        try {
            $agencies = Agency::where('agency_name', 'LIKE', "%{$query}%")
                            ->orWhere('services', 'LIKE', "%{$query}%")
                            ->orWhere('description', 'LIKE', "%{$query}%")
                            ->select('agency_id', 'agency_name', 'description', 'services', 'contact_number', 'email')
                            ->limit(10)
                            ->get();

            return response()->json([
                'success' => true,
                'agencies' => $agencies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }
}