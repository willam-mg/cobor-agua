<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operario extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre_completo',
        'ci',
        'telefono',
        'direccion',
        'rol',
        'cargo',
        'fecha_inicio',
        'fecha_fin',
        'src_foto',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
