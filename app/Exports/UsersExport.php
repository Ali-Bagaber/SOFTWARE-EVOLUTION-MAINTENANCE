<?php

namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpExcel\Worksheet;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

class UsersExport
{
    protected $userRole;
    protected $dateFrom;
    protected $dateTo;
    protected $reportType;
    protected $search;

    public function __construct($userRole, $dateFrom, $dateTo, $reportType = 'detailed', $search = null)
    {
        $this->userRole = $userRole;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->reportType = $reportType;
        $this->search = $search;
    }
      /**
     * Get the data for the Excel export
     *
     * @return array
     */
    public function collection()
    {
        // Query to get filtered users for the export
        $usersQuery = User::query();

        // Apply date filter
        if ($this->dateFrom && $this->dateTo) {
            $usersQuery->whereBetween('created_at', [
                $this->dateFrom . ' 00:00:00', 
                $this->dateTo . ' 23:59:59'
            ]);
        }

        // Apply user role filter if not 'all'
        if ($this->userRole && $this->userRole !== 'all') {
            $usersQuery->where('user_role', $this->userRole);
        }
        
        // Apply search filter if provided
        if ($this->search) {
            $usersQuery->where(function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            });
        }
          
        // For user profile report, we want to include more details and sort by name
        if ($this->reportType == 'user_profile') {
            // Order by name for better presentation in profile format
            $users = $usersQuery->orderBy('name')->get();
        } else {
            // Standard query for other report types
            $users = $usersQuery->get();
        }
        
        // Create base query for summary statistics with date filter only
        $baseQuery = User::query();
        
        if ($this->dateFrom && $this->dateTo) {
            $baseQuery->whereBetween('created_at', [
                $this->dateFrom . ' 00:00:00', 
                $this->dateTo . ' 23:59:59'
            ]);
        }        // Create queries for each user type count
        $publicQuery = clone $baseQuery;
        $agencyQuery = clone $baseQuery;
        $adminQuery = clone $baseQuery;
          // Calculate summary counts
        $summary = [
            'total_users' => $users->count(), // This shows count based on all active filters
            'public_users' => $publicQuery->where('user_role', 'publicuser')->count(),
            'agency_users' => $agencyQuery->where('user_role', 'agency')->count(),
            'admin_users' => $adminQuery->where('user_role', 'admin')->count(),
        ];
        
        // Format data for Excel export
        $data = [
            'summary' => $summary,
            'report_type' => $this->reportType,
            'user_role' => $this->userRole,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'generated_date' => now()->format('M d, Y'),
        ];
        
        // Add user data in appropriate format
        $userData = [];
        
        foreach ($users as $user) {
            $userData[] = [
                'id' => $user->user_id ?? $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->contact_number ?? 'N/A',
                'role' => $user->user_role,
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A',
                'last_login' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
            ];
        }
        
        $data['users'] = $userData;
        
        return $data;
    }
}
