<?php

namespace App\Models;
require_once '/../vendor/autoload.php';
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Login extends Model
{



    protected $primaryKey = 'idLogin';
    protected $table = 'login';
    public $incrementing = true;
    public $timestamps = true;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = null;

    protected $fillable = [
        'idUsuario', 'horaIng'
    ];

}


?>