<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TodoService
{
    // Membuat data todo baru dari data yang sudah divalidasi.
    public function createTodo(array $validatedData): Todo
    {
        return Todo::create($validatedData);
    }


    // Mengambil data untuk laporan Excel dengan filter.
    public function getTodosForReport(array $filters): Collection
    {
        $query = Todo::query();

        // Contoh implementasi filter sederhana
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['assignee'])) {
            $query->where('assignee', 'like', '%' . $filters['assignee'] . '%');
        }

        if (!empty($filters['due_date_from'])) {
            $query->whereDate('due_date', '>=', $filters['due_date_from']);
        }

        if (!empty($filters['due_date_to'])) {
            $query->whereDate('due_date', '<=', $filters['due_date_to']);
        }

        return $query->get();
    }

    // Mengambil data agregat untuk grafik berdasarkan tipe.
    public function getChartData(string $type)
    {
        if (!in_array($type, ['status', 'priority', 'assignee'])) {
            throw new InvalidArgumentException('Invalid chart type requested.');
        }

        if ($type == 'assignee') {
        return $this->getAssigneeChartData();
    }

        return Todo::query()
            ->select($type, DB::raw('count(*) as total'))
            ->groupBy($type)
            ->pluck('total', $type);
    }

    private function getAssigneeChartData()
    {
        $data = Todo::query()
            ->select(
                'assignee',
                DB::raw('count(*) as total_todos'),
                DB::raw("sum(case when status = 'pending' then 1 else 0 end) as total_pending_todos"),
                DB::raw("sum(case when status = 'completed' then 1 else 0 end) as total_completed_todos"),
                DB::raw("sum(case when status = 'completed' then time_tracked else 0 end) as total_timetracked_completed_todos")
            )
            ->groupBy('assignee')
            ->get();

        // Mengubah format agar sesuai dengan JSON yang dibutuhkan
        return $data->mapWithKeys(function ($item) {
            return [$item->assignee => [
                'total_todos' => (int) $item->total_todos,
                'total_pending_todos' => (int) $item->total_pending_todos,
                'total_completed_todos' => (int) $item->total_completed_todos,
                'total_timetracked_completed_todos' => (int) $item->total_timetracked_completed_todos,
            ]];
        });
    }

}
