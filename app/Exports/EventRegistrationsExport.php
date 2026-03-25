<?php
// app/Exports/EventRegistrationsExport.php

namespace App\Exports;

use App\Models\EventRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EventRegistrationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $registrations;

    public function __construct($registrations)
    {
        $this->registrations = $registrations;
    }

    public function collection()
    {
        return $this->registrations;
    }

    public function headings(): array
    {
        return [
            'Registration #',
            'Event',
            'Event Date',
            'Registrant Name',
            'Registrant Email',
            'User ID',
            'Guest Count',
            'Status',
            'Attended',
            'Check-in Time',
            'Registration Date',
            'Confirmation Date',
            'Additional Info',
            'Notes',
        ];
    }

    public function map($registration): array
    {
        return [
            $registration->registration_number,
            $registration->event->title,
            $registration->event->start_date->format('Y-m-d H:i'),
            $registration->user->name,
            $registration->user->email,
            $registration->user->id,
            $registration->guest_count,
            ucfirst($registration->status),
            $registration->attended ? 'Yes' : 'No',
            $registration->check_in_time ? $registration->check_in_time->format('Y-m-d H:i') : '',
            $registration->created_at->format('Y-m-d H:i'),
            $registration->confirmed_at ? $registration->confirmed_at->format('Y-m-d H:i') : '',
            $registration->additional_info ?? '',
            $registration->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '002789'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style the data rows
        $sheet->getStyle('A2:N' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Add borders
        $sheet->getStyle('A1:N' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        return [];
    }
}