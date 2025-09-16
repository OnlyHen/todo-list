<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TodoService
{
    /**
     * Membuat data todo baru dari data yang sudah divalidasi.
     */
    public function createTodo(array $validatedData): Todo
    {
        return Todo::create($validatedData);
    }

    /**
     * Mengambil data untuk laporan Excel dengan filter.
     */
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

    /**
     * Mengambil data agregat untuk grafik berdasarkan tipe.
     */
    public function getChartData(string $type)
    {
        if (!in_array($type, ['status', 'priority', 'assignee'])) {
            throw new InvalidArgumentException('Invalid chart type requested.');
        }

        return Todo::query()
            ->select($type, DB::raw('count(*) as total'))
            ->groupBy($type)
            ->pluck('total', $type);
    }
}
