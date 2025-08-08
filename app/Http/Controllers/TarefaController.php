<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarefa;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Notifications\TarefaCriada;
use App\Jobs\SendTaskNotification;

class TarefaController extends Controller
{
        public function index()
        {
            return Tarefa::where('empresa_id', auth()->user()->empresa_id)->get();
        }
    
        public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'status' => 'required|in:pendente,em_andamento,concluida',
                'prioridade' => 'required|in:baixa,media,alta',
                'data_limite' => 'nullable|date',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['erros' => $validator->errors()], 422);
            }
    
            $tarefa = Tarefa::create([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'status' => $request->status,
                'prioridade' => $request->prioridade,
                'data_limite' => $request->data_limite,
                'empresa_id' => auth()->user()->empresa_id,
                'user_id' => auth()->id(),
            ]);

            SendTaskNotification::dispatch($tarefa);
    
            return response()->json($tarefa, 201);
        }
    
        public function show($id)
        {
            $tarefa = Tarefa::where('id', $id)
                ->where('empresa_id', auth()->user()->empresa_id)
                ->firstOrFail();
    
            return response()->json($tarefa);
        }
    
        public function update(Request $request, $id)
        {
            $tarefa = Tarefa::where('id', $id)
                ->where('empresa_id', auth()->user()->empresa_id)
                ->firstOrFail();
    
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'status' => 'required|in:pendente,em_andamento,concluida',
                'prioridade' => 'required|in:baixa,media,alta',
                'data_limite' => 'nullable|date',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['erros' => $validator->errors()], 422);
            }
    
            $tarefa->update($request->only([
                'titulo', 'descricao', 'status', 'prioridade', 'data_limite'
            ]));

            SendTaskNotification::dispatch($tarefa);
    
            return response()->json($tarefa);
        }
    
            public function destroy($id)
    {
        $tarefa = Tarefa::where('id', $id)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->firstOrFail();

        $tarefa->delete();

        return response()->json(['mensagem' => 'Tarefa excluída com sucesso.']);
    }

    public function filtrarPorStatus($status)
    {
        $statuses = ['pendente', 'em_andamento', 'concluida'];
        
        if (!in_array($status, $statuses)) {
            return response()->json(['error' => 'Status inválido'], 400);
        }

        $tarefas = Tarefa::where('empresa_id', auth()->user()->empresa_id)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tarefas);
    }

    public function filtrarPorPrioridade($prioridade)
    {
        $prioridades = ['baixa', 'media', 'alta'];
        
        if (!in_array($prioridade, $prioridades)) {
            return response()->json(['error' => 'Prioridade inválida'], 400);
        }

        $tarefas = Tarefa::where('empresa_id', auth()->user()->empresa_id)
            ->where('prioridade', $prioridade)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tarefas);
    }
}
