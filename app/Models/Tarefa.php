<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Empresa;

class Tarefa extends Model
{
    use HasFactory;
    protected $dates = ['data_limite', 'created_at', 'updated_at'];
    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'data_limite',
        'empresa_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
