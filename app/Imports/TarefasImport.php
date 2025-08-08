<?php

namespace App\Imports;

use App\Models\Tarefa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Auth;

class TarefasImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        if (empty($row['titulo'])) {
            return null;
        }

        return new Tarefa([
            'user_id' => Auth::id(),
            'titulo' => $row['titulo'],
            'descricao' => $row['descricao'] ?? null,
            'status' => $row['status'] ?? 'pendente',
            'prioridade' => $row['prioridade'] ?? 'media',
            'data_limite' => $this->parseDate($row['data_limite'] ?? null),
            'empresa_id' => Auth::user()->empresa_id,
        ]);
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'nullable|in:pendente,em_andamento,concluida',
            'prioridade' => 'nullable|in:baixa,media,alta',
            'data_limite' => 'nullable|date',
            // 'empresa_id' => 'required|exists:empresas,id', // Remover
        ];
    }
    

    public function customValidationMessages()
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'status.in' => 'O status deve ser: pendente, em_andamento ou concluida.',
            'prioridade.in' => 'A prioridade deve ser: baixa, media ou alta.',
            'data_limite.date' => 'A data limite deve ser uma data válida.',
            // 'empresa_id.required' => 'A empresa é obrigatória.',
            // 'empresa_id.exists' => 'A empresa informada não existe.',
        ];
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        // Tentar diferentes formatos de data
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'Y-m-d H:i:s'];
        
        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $date);
            if ($parsed !== false) {
                return $parsed->format('Y-m-d');
            }
        }

        return null;
    }
}
