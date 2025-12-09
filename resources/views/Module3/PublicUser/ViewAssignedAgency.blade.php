@extends('components.side_headr')

@section('title', 'My Inquiries - Inquira')

@section('additional_css')
    <style>
        /* Enhanced styles for agency assignment visibility */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #eaf0fa 0%, #f4f8fb 100%);
            min-height: 100vh;
            display: flex;
        }

        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 32px 24px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        }

        .header {
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .inquiry-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #3498db;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .inquiry-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .inquiry-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .inquiry-status {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            text-transform: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-under-investigation {
            background: #e3f2fd;
            color: #1565c0;
        }

        .status-verified-true {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .status-identified-fake {
            background: #ffebee;
            color: #c62828;
        }

        .status-rejected {
            background: #fff3e0;
            color: #ef6c00;
        }

        .status-investigation-complete {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .status-icon {
            font-size: 14px;
        }

        .inquiry-content {
            color: #555;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.6;
        }

        .assignment-info {
            background: #f8fffe;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #e1f5fe;
        }

        .assignment-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .assignment-icon {
            width: 20px;
            height: 20px;
            background: #3498db;
            border-radius: 50%;
            margin-right: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .assignment-title {
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .agency-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 12px;
        }

        .agency-info {
            background: #fff;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .agency-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .agency-contact {
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
        }

        .assignment-date {
            background: #fff;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            text-align: center;
        }

        .date-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }

        .date-value {
            font-weight: 600;
            color: #2c3e50;
        }

        .no-inquiries {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-inquiries-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px 16px;
            }

            .agency-details {
                grid-template-columns: 1fr;
            }

            .inquiry-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .inquiry-status {
                margin-top: 8px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h1 class="page-title">My Inquiry Status</h1>
            <p style="color: #666; margin: 0;">Track the investigation progress of your submitted inquiries</p>
        </div>

        <div class="inquiries-list">
            @if($userInquiries->isEmpty())
                <div class="no-inquiries">
                    <div class="no-inquiries-icon">📋</div>
                    <h3>No inquiries found</h3>
                    <p>You haven't submitted any inquiries yet.</p>
                </div>
            @else
                @foreach($userInquiries as $inquiry)
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <h3 class="inquiry-title">{{ $inquiry->title }}</h3>
                            <span class="inquiry-status status-{{ strtolower(str_replace(' ', '-', $inquiry->public_status)) }}">
                                @switch($inquiry->public_status)
                                    @case('Under Investigation')
                                        <span class="status-icon">🔍</span>
                                        @break
                                    @case('Verified as True')
                                        <span class="status-icon">✅</span>
                                        @break
                                    @case('Identified as Fake')
                                        <span class="status-icon">❌</span>
                                        @break
                                    @case('Rejected')
                                        <span class="status-icon">⚠️</span>
                                        @break
                                    @default
                                        <span class="status-icon">📋</span>
                                @endswitch
                                {{ $inquiry->public_status }}
                            </span>
                        </div>

                        <div class="inquiry-content">
                            <p><strong>Submitted:</strong> {{ $inquiry->date_submitted->format('M d, Y \a\t h:i A') }}</p>
                            <p><strong>Status:</strong> {{ $inquiry->public_status_description }}</p>
                            <p>{{ Str::limit($inquiry->content, 150) }}</p>
                        </div>

                        @if($inquiry->verificationProcesses->isNotEmpty() && $inquiry->verificationProcesses->first()->agency)
                            <div class="assignment-info">
                                <div class="assignment-header">
                                    <div class="assignment-icon">
                                        <span style="color: white; font-size: 12px;">👥</span>
                                    </div>
                                    <h4 class="assignment-title">Investigation Agency</h4>
                                </div>

                                <div class="agency-details">
                                    <div class="agency-info">
                                        <div class="agency-name">{{ $inquiry->verificationProcesses->first()->agency->agency_name ?? $inquiry->verificationProcesses->first()->agency->name }}</div>
                                        <div class="agency-contact">
                                            <strong>Contact Person:</strong> {{ $inquiry->verificationProcesses->first()->agency->contact_person ?? 'N/A' }}
                                        </div>
                                        <div class="agency-contact">
                                            <strong>Email:</strong> {{ $inquiry->verificationProcesses->first()->agency->agency_email ?? $inquiry->verificationProcesses->first()->agency->email ?? 'N/A' }}
                                        </div>
                                        <div class="agency-contact">
                                            <strong>Phone:</strong> {{ $inquiry->verificationProcesses->first()->agency->agency_phone ?? $inquiry->verificationProcesses->first()->agency->phone ?? 'N/A' }}
                                        </div>
                                    </div>

                                    <div class="assignment-date">
                                        <div class="date-label">Investigation Started</div>
                                        <div class="date-value">
                                            {{ $inquiry->verificationProcesses->first()->created_at ? $inquiry->verificationProcesses->first()->created_at->format('M d, Y') : $inquiry->updated_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>

                                @if($inquiry->public_status === 'Verified as True')
                                    <div style="margin-top: 12px; padding: 12px; background: #e8f5e8; border-radius: 6px; border-left: 4px solid #4caf50;">
                                        <strong style="color: #2e7d32;">✅ Investigation Result:</strong>
                                        <p style="margin: 4px 0 0 0; color: #2e7d32; font-size: 14px;">
                                            This information has been verified as genuine and accurate news.
                                        </p>
                                    </div>
                                @elseif($inquiry->public_status === 'Identified as Fake')
                                    <div style="margin-top: 12px; padding: 12px; background: #ffebee; border-radius: 6px; border-left: 4px solid #f44336;">
                                        <strong style="color: #c62828;">❌ Investigation Result:</strong>
                                        <p style="margin: 4px 0 0 0; color: #c62828; font-size: 14px;">
                                            This information has been identified as false or misleading.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="assignment-info" style="background: #fff3e0; border-color: #ffb74d;">
                                <div class="assignment-header">
                                    <div class="assignment-icon" style="background: #ff9800;">
                                        <span style="color: white; font-size: 12px;">⏳</span>
                                    </div>
                                    <h4 class="assignment-title">Awaiting Investigation Assignment</h4>
                                </div>
                                <p style="margin: 8px 0 0 28px; color: #666; font-size: 14px;">
                                    Your inquiry is being reviewed and will be assigned to the appropriate investigative agency soon.
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        // Optional: Add refresh functionality or real-time updates
        document.addEventListener('DOMContentLoaded', function () {
            // You can add JavaScript for enhanced functionality here
            console.log('Agency assignment visibility loaded');
        });
    </script>
@endsection
