<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{

    /**
     * Return a collection of users for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with('company', 'role')->get();
    }

    /**
     * Define the headings for the columns.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Address',
            'Date of Birth',
            'Company Name',
            'Role Name',
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            $user->address,
            $user->date_of_birth ? $user->date_of_birth->format('d.m.Y') : null,
            $user->company->name ?? '', // Falls keine Company zugeordnet ist, wird ein leerer String zurückgegeben
            $user->role->name ?? '', // Falls keine Rolle zugeordnet ist, wird ein leerer String zurückgegeben
        ];
    }

    /**
     * Set the styles for the exported file.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Entfernen von Gitternetzlinien
        $sheet->setShowGridlines(false);

        // Alle Spalten automatisch breit setzen
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        return [];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,          // Phone als Text
        ];
    }
}
