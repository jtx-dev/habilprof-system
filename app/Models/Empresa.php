<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'empresa';

    // Clave primaria
    protected $primaryKey = 'rut_empresa';

    // Tipo de clave primaria (no auto-incremental)
    public $incrementing = false;

    // Tipo de dato de la clave primaria
    protected $keyType = 'integer';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'rut_empresa',
        'nombre_empresa',
        'supervi_empresa',
    ];

    // Campos de fecha automáticos
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Relación con Realiza
    public function realizas()
    {
        return $this->hasMany(Realiza::class, 'rut_empresa', 'rut_empresa');
    }
}