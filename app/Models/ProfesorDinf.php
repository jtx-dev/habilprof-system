<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProfesorDinf extends Authenticatable
{
    use HasFactory, Notifiable;

    // Especificar explícitamente el nombre de la tabla
    protected $table = 'profesor_dinf';

    // Clave primaria
    protected $primaryKey = 'rut_profesor';

    // Tipo de clave primaria (no auto-incremental)
    public $incrementing = false;

    // Tipo de dato de la clave primaria
    protected $keyType = 'integer';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'rut_profesor',
        'nombre_profesor',
        'password',
    ];

    // Campos ocultos en arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'rut_profesor';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->rut_profesor;
    }

    // Relación con Supervisa
    public function supervisas()
    {
        return $this->hasMany(Supervisa::class, 'rut_profesor', 'rut_profesor');
    }
}