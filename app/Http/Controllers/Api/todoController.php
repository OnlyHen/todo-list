<?php

namespace App\Http\Controllers\Api;

use App\Exports\TodosExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Services\TodoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Maatwebsite\Excel\Facades\Excel;

class TodoController extends Controller
{
    protected $todoService;

    // Manggil service
    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    // Buat simpen todo
    public function store(StoreTodoRequest $request): JsonResponse
    {
        // Validasi data
        $todo = $this->todoService->createTodo($request->validated());

        return response()->json([
            'message' => 'Todo created successfully',
            'data' => $todo
        ], 201);
    }

    // Laporan Excel
    public function generateReport(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $todos = $this->todoService->getTodosForReport($request->all());
        return Excel::download(new TodosExport($todos), 'todos_report.xlsx');
    }

    // Data untuk grafik
    public function getChartData(Request $request): JsonResponse
    {
        $chartType = $request->query('type');

        if (!$chartType) {
            return response()->json(['message' => 'Chart type query parameter is required.'], 400);
        }

        try {
            $data = $this->todoService->getChartData($chartType);
            return response()->json($data);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
