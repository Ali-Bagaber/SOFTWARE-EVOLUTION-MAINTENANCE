<?php

namespace App\Exports;

use App\Models\Inquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InquiryReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $dateFrom;
    protected $dateTo;
    protected $statusFilter;
    protected $categoryFilter;

    public function __construct($dateFrom, $dateTo, $statusFilter, $categoryFilter)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->statusFilter = $statusFilter;
        $this->categoryFilter = $categoryFilter;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collection()
    {
        $query = Inquiry::with('user');

        // Apply date range filter
        $query->whereBetween('date_submitted', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply category filter
        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        return $query->orderBy('date_submitted', 'desc')->get();
    }

    /**
     * @return array
     */    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Category',
            'Status',
            'Days Open',
            'Submitter Name',
            'Submitter Email',
            'Date Submitted',
            'Content Preview'
        ];
    }

    /**
     * Map the data for each row
     *
     * @param $inquiry
     * @return array
     */    public function map($inquiry): array
    {
        return [
            $inquiry->inquiry_id,
            $inquiry->title,
            $inquiry->category ?? 'N/A',
            $inquiry->status,
            $inquiry->date_submitted ? $inquiry->date_submitted->diffInDays(now()) . ' days' : 'N/A',
            $inquiry->user->name ?? 'N/A',
            $inquiry->user->email ?? 'N/A',
            $inquiry->date_submitted ? $inquiry->date_submitted->format('Y-m-d H:i:s') : 'N/A',
            strlen($inquiry->content) > 100 ? substr($inquiry->content, 0, 100) . '...' : $inquiry->content
        ];
    }

    /**
     * Apply styles to the worksheet
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
