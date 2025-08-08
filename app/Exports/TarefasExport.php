<?php

namespace App\Exports;

use App\Models\Tarefa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TarefasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Tarefa::select('id', 'titulo', 'descricao', 'status', 'prioridade', 'data_limite', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Descrição',
            'Status',
            'Prioridade',
            'Data Limite',
            'Criado em',
        ];
    }
}