<?php

namespace App\Exports;

use App\Models\todo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class TodosExport implements FromCollection
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
}
