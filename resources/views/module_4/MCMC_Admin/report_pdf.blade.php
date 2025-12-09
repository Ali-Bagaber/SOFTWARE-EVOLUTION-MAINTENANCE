<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agency Performance Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; }
        h1 { text-align: center; margin-bottom: 10px; }
        .date-range { text-align: center; margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #bbb; padding: 8px 10px; font-size: 13px; }
        th { background: #f2f2f2; }
        tr:nth-child(even) { background: #f9f9f9; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 30px; }
    </style>
</head>
<body>
    <h1>Agency Performance Report</h1>
    <div class="date-range">
        Date Range: {{ $dateFrom }} to {{ $dateTo }}
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Agency Name</th>
                <th>Assigned</th>
                <th>Resolved</th>
                <th>Avg. Resolution Time (days)</th>
                <th>Pending</th>
                <th>Delayed</th>
            </tr>
        </thead>
        <tbody>
            @forelse($performance as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['agency_name'] }}</td>
                <td>{{ $row['assigned'] }}</td>
                <td>{{ $row['resolved'] }}</td>
                <td>{{ $row['avg_resolution'] !== null ? $row['avg_resolution'] : '-' }}</td>
                <td>{{ $row['pending'] }}</td>
                <td>{{ $row['delayed'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No data found for the selected criteria</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">
        Generated on {{ now()->format('Y-m-d H:i') }} by MCMC System
    </div>
</body>
</html> 