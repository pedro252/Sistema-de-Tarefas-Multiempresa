<?php

namespace App\Http\Controllers;

use App\Exports\TarefasTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class TarefaTemplateController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new TarefasTemplateExport, 'template_tarefas.xlsx');
    }
}
