<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realiza extends Model
{
    use HasFactory;

    protected $table = 'realiza';

    protected $fillable = [
        'id_habilitacion',
        'rut_empresa',
    ];

    // Relación con Prtut
    public function prtut()
    {
        return $this->belongsTo(Prtut::class, 'id_habilitacion', 'id_habilitacion');
    }

    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'rut_empresa', 'rut_empresa');
    }
}