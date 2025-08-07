<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nome',
        'identificador',
    ];

    //(1:N) uma empresa tem muitos usuários
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
