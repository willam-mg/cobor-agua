<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarifa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'valor_m3',
        'tarifa',
    ];

    public function medidores(): HasMany
    {
        return $this->hasMany(Medidor::class, 'tarifa_id');
    }
}
