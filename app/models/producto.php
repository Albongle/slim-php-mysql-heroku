<?php

namespace App\Models;
require_once '/../vendor/autoload.php';
use Illuminate\Database\Eloquent\Model;


class Producto extends Model
{
    const TIPO = array("Comida","Bebidas","Postre");
    protected $primaryKey = 'idProductos';
    protected $table = 'productos';
    public $incrementing = true;
    public $timestamps = true;
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    protected $fillable = [
        'tipo','nombre','precio', 'sector'
    ];

    //tragos, cocina, postre , cerveza
}


?>