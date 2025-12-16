@extends('components.admin_side_headr')

@section('title', 'Your Page Title')

@section('page_title', 'All Inquiries')

@section('additional_styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8f9fa;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
            margin-left: -240px; 
            padding: 10;
        }

        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 280px;
            background: #f8f9fa;
            min-height: 100vh;
        }

      

        .dashboard-content {
            padding: 40px;
            /* Adjusted to match sidebar width */
        }


        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 5px solid;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.pending {
            border-left-color: #f39c12;
        }

        .stat-card.under-investigation {
            border-left-color: #3498db;
        }

        .stat-card.verified-as-true {
            border-left-color: #27ae60;
        }

        .stat-card.identified-as-fake {
            border-left-color: #e74c3c;
        }

        .stat-card.rejected {
            border-left-color: #95a5a6;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Search and Filter Section */
        .search-filter-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .search-filter-card h3 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .search-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-group select, .filter-group input {
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: white;
        }

        .filter-group select:focus, .filter-group input:focus {
            outline: none;
            border-color: #3498db;
        }

        /* Table Styles */
        .inquiries-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .card-header h3 {
            color: #2c3e50;
            font-size: 22px;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .count-badge {
            background: #3498db;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .table-container {
            overflow-x: auto;
        }

        .inquiries-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .inquiries-table th {
            background: #f8f9fa;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #e9ecef;
        }

        .inquiries-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .inquiries-table tr:hover {
            background: #f8f9fa;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.under-investigation {
            background: #cce5ff;
            color: #004085;
        }

        .status-badge.verified-as-true {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.identified-as-fake {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.rejected {
            background: #e2e3e5;
            color: #383d41;
        }

        /* Action Buttons */
        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            margin-right: 5px;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #3498db;
            color: white;
        }

        .btn-view:hover {
            background: #2980b9;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 20px 30px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }

            .search-row {
                grid-template-columns: 1fr;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Sidebar Navigation -->
        

        <!-- Main Content -->
        <main class="main-content">
            

            <div class="dashboard-content">
               
                <!-- Inquiries Table -->
                <div class="inquiries-card">
                    <div class="card-header">
                        <h3>Inquiry History</h3>
                        <div class="header-actions">
                            <span class="count-badge">{{ $inquiries->total() }} total inquiries</span>
                            <button class="btn btn-secondary" onclick="exportInquiries()">Export CSV</button>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="inquiries-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Submitter</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inquiries as $inquiry)
                                <tr>
                                    <td>#{{ str_pad($inquiry->inquiry_id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div style="max-width: 200px;">
                                            <strong>{{ Str::limit($inquiry->title, 40) }}</strong>
                                            @if($inquiry->content)
                                                <div style="font-size: 12px; color: #666; margin-top: 4px;">
                                                    {{ Str::limit($inquiry->content, 60) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $inquiry->user->name ?? 'Unknown User' }}</strong>
                                            <div style="font-size: 12px; color: #666;">
                                                {{ $inquiry->user->email ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                            {{ $inquiry->category ?: 'General' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                                            {{ $inquiry->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $inquiry->date_submitted->format('M d, Y') }}
                                            <div style="font-size: 12px; color: #666;">
                                                {{ $inquiry->date_submitted->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="viewInquiry('{{ $inquiry->inquiry_id }}')">
                                            👁️ View
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #7f8c8d;">
                                        <div style="font-size: 18px; margin-bottom: 10px;">📝</div>
                                        <div>No inquiries found matching your criteria</div>
                                        <div style="font-size: 14px; margin-top: 5px;">Try adjusting your search or filter options</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($inquiries->hasPages())
                        <div class="pagination-wrapper">
                            {{ $inquiries->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script>
        function viewInquiry(inquiryId) {
            // Redirect to admin inquiry details page
            window.location.href = `/admin/inquiries/${inquiryId}/details`;
        }

        function exportInquiries() {
            // Build URL with current filters
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'csv');
            
            // Create temporary link to download
            const link = document.createElement('a');
            link.href = `{{ route('admin.all.inquiries') }}?${params.toString()}`;
            link.click();
        }

        // Auto-submit form when filters change (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('#status, #category');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    // Optional: Auto-submit on change
                    // this.form.submit();
                });
            });
        });
    </script>
@endsection