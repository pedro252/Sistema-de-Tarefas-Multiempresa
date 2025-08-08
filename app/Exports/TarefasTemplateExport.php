<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TarefasTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Exemplo de Tarefa',
                'Esta é uma descrição de exemplo',
                'pendente',
                'media',
                '2024-12-31'
            ],
            [
                'Outra Tarefa',
                'Outra descrição de exemplo',
                'em_andamento',
                'alta',
                '2024-11-30'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Título',
            'Descrição',
            'Status',
            'Prioridade',
            'Data Limite'
        ];
    }
}
