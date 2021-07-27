<?php

namespace App\Models;
require_once "../vendor/illuminate/database/Eloquent/Model.php";
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletes;

class Logs extends Model
{

    protected $primaryKey = 'idLogs';
    protected $table = 'logs';
    public $incrementing = true;
    public $timestamps = true;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = null;

    protected $fillable = [
        'idUsuario', 'horaRegistro','entidad','accion','detalle'
    ];

}


?>