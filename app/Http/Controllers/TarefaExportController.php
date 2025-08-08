<?php

namespace App\Http\Controllers;

use App\Exports\TarefasExport;
use Maatwebsite\Excel\Facades\Excel;

class TarefaExportController extends Controller
{
    public function export()
    {
        return Excel::download(new TarefasExport, 'tarefas.xlsx');
    }
}
