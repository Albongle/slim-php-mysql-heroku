<?php

namespace App\Models;
require_once "./vendor/illuminate/database/Eloquent/Model.php";
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletes;

class Pedido extends Model{
    use SoftDeletes;
    const ESTADO =  array("Iniciado","Preparacion", "Completado", "Cancelado");
    protected $primaryKey = 'idPedidos';
    protected $table = 'pedidos';
    public $incrementing = true;
    public $timestamps = true;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    const DELETED_AT = 'fechaBaja';

    protected $fillable = [
        'idSolicitante','idEncargado','idMesa','idProducto','cantidad','horaInicio','horaEstFin','horaFin','estado','foto'
    ];
}
?> 