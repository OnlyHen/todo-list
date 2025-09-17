<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TodosExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $todos;

    public function __construct(Collection $todos)
    {
        $this->todos = $todos;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->todos;
    }

    // Setting untuk heading kolom
    public function headings(): array
    {
        return [
            'Title',
            'Assignee',
            'Due Date',
            'Time Tracked (minutes)',
            'Status',
            'Priority',
        ];
    }

    // Mapping data untuk setiap heading
    public function map($todo): array
    {
        return [
            $todo->title,
            $todo->assignee,
            $todo->due_date,
            $todo->time_tracked,
            $todo->status,
            $todo->priority,
        ];
    }

    // Setelah Sheet dibuat, menambahkan baris ringkasan dan styling
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Menghitung total
                $totalTodos = $this->todos->count();
                $totalTimeTracked = $this->todos->sum('time_tracked');

                // Menentukan baris setelah data terakhir
                $summaryRowNumber = $this->todos->count() + 3;

                $event->sheet->append([' ']);

                // Menambahkan baris ringkasan
                $event->sheet->append([
                    'Summary'
                ]);
                $event->sheet->append([
                    'Total number of todos:', $totalTodos,
                ]);
                $event->sheet->append([
                    'Total time_tracked across all todos:', $totalTimeTracked,
                ]);

                // Buat heading jadi Bold
                $event->sheet->getStyle('A1:F1')->getFont()->setBold(true);
                $event->sheet->getStyle('A' . ($summaryRowNumber))->getFont()->setBold(true);
            },
        ];
    }
}
