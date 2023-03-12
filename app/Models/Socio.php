<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Socio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre_completo',
        'ci',
        'telefono',
        'src_foto',
    ];

    /**
     * the appends attributes for accesors.
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

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'socio_id');
    }

    public function propiedades(): HasMany {
        return $this->hasMany(Propiedad::class, 'propiedad_id');
    }
}
