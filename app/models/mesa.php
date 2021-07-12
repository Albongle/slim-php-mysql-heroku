<?php

namespace App\Models;
require_once '/../vendor/autoload.php';
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mesa extends Model{

    use SoftDeletes;
    const ESTADO = array("Esperando", "Pagando", "Comiendo");
    protected $primaryKey = 'idMesas';
    protected $table = 'mesas';
    public $incrementing = false;
    public $timestamps = true;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    const DELETED_AT = 'fechaBaja';

    protected $fillable = [
        'cliente','codigo','estado'
    ];
}
?>