<?php

namespace App\Http\Controllers;

use App\Imports\TarefasImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TarefaImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new TarefasImport, $request->file('file'));
            
            return response()->json([
                'success' => true,
                'message' => 'Tarefas importadas com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao importar tarefas: ' . $e->getMessage()
            ], 400);
        }
    }
}
