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

    /**
     * the appends attributes.
     */
    protected $appends = [
        'foto',
        'foto_thumbnail',
        'foto_thumbnail_sm',
    ];

    /**
     * get foto attribute
     */
    public function getFotoAttribute()
    {
        return $this->src_foto ? url('/') . '/storage/uploads/' . $this->src_foto : null;
    }

    /**
     * Get accesor foto thumbnail.
     */
    public function getFotoThumbnailAttribute()
    {
        return $this->src_foto ? url('/') . '/storage/uploads/thumbnail/' . $this->src_foto : null;
    }
    /**
     * Get accesor foto small thumbnail.
     */
    public function getFotoThumbnailSmAttribute()
    {
        return $this->src_foto ? url('/') . '/storage/uploads/thumbnail-small/' . $this->src_foto : null;
    }

    /**
     * get User
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'operario_id');
    }
}
