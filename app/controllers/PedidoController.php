<?php
require_once "../models/Usuario.php";
require_once "../models/Pedido.php";
require_once "../models/Mesa.php";
require_once "../models/Producto.php";
require_once "../middlewares/middleware.php";
require_once "../interfaces/IApiUsable.php";
require_once "../models/AutentificadorJWT.php";


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


use AutentificadorJWT as AutentificadorJWT;
use App\Models\Pedido as Pedido;
use App\Models\Producto as Producto;
use App\Models\Usuario as Usuario;
use App\Models\Mesa as Mesa;
use Middleware as Middleware;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Support\Facades\Date;

class PedidoController implements IApiUsable
{
    public function TraerUno(Request $request, Response $response, $args)
    {
        $url =  $request->getUri();
        $url =$url->getPath();
        $parametros =  $request->getQueryParams();
        
        try {
            switch ($url) {
                case "/LaComanda/pedidos/usuario/buscar/":
                    {
                        if (isset($parametros['codigomesa'])) {
                            $pedidos =  Pedido::join('mesas', 'pedidos.idMesa', '=', 'mesas.idMesas')->where('mesas.codigo', '=', $parametros['codigomesa'])->join('productos', 'pedidos.idProducto', '=', 'productos.idProductos')->select('pedidos.horaInicio', 'pedidos.horaEstFin', 'pedidos.estado', 'productos.tipo', 'productos.nombre')->get();
                            $payload = json_encode(array("Pedido"=>$pedidos));
                        } else {
                            $payload = json_encode(array("mensaje"=> "No se recibio ninguno de los parametros necesarios: codigomesa"));
                        }
                        break;
                    }
                case "/LaComanda/pedidos/empleados/buscar/":
                    {
                        $datosUsr =  Middleware::GetDatosUsuario($request);
                        $pedidos =  Pedido::join('mesas', 'pedidos.idMesa', '=', 'mesas.idMesas')->join('productos', 'pedidos.idProducto', '=', 'productos.idProductos')->join('usuarios', 'usuarios.idUsuarios', '=', 'pedidos.idSolicitante')->where('productos.sector', '=', $datosUsr->sector)->select('pedidos.idPedidos as NumeroDePedido', 'usuarios.apellido as Solicitante', 'mesas.idMesas as NumeroDeMesa', 'pedidos.horaInicio', 'pedidos.horaEstFin', 'pedidos.horaFin', 'pedidos.estado as Estado', 'productos.tipo as Tipo', 'productos.nombre as Nombre', 'pedidos.cantidad as Cantidad')->get();
                        $payload = json_encode(array("Pedido"=>$pedidos));
                        break;
                    }
                default:
                    {

                        $payload = json_encode(array("Mensaje"=>"Solicitud no reconocida"));
                        break;
                    }
            }
        } catch (Exception $ex) {
            $payload = json_encode(array("mensaje" => "Se produjo un error ". $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $parametrosFoto = $request->getUploadedFiles();

        try {
            $datosUsr =  Middleware::GetDatosUsuario($request);
            if (isset($parametros['idmesa']) && isset($parametros['idproducto']) && isset($parametros['cantidad'])) {
                $producto = Producto::find($parametros['idproducto']);
                if ($producto) {
                    $mesa =  Mesa::find($parametros['idmesa']);
                    if ($mesa) {
                        $mesa->update(['estado'=>'Esperando']);
                        $mesa->save();
                        $pedido =  new Pedido();
                        
                        if (isset($parametrosFoto['foto'])) {
                            $nombreFoto = $parametrosFoto['foto']->getClientFileName();
                            $extension = explode(".", $nombreFoto);
                            $extension = array_reverse($extension)[0];
                            $destino="./img/FotosPedidos/Mesa-".$mesa->IdMesas.".".$extension;
                            $parametrosFoto['foto']->moveTo($destino);
                            $pedido->foto =  $destino;
                        }
                        
                        $pedido->idSolicitante =  $datosUsr->idUsuario;
                        $pedido->idMesa = $mesa->idMesas ;
                        $pedido->estado =  "Iniciado";
                        $pedido->idProducto= $producto->idProductos;
                        $pedido->horaInicio = date('H:i:s');
                        $pedido->cantidad = $parametros['cantidad'];
                        $pedido->save();
                        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
                    } else {
                        $payload = json_encode(array("mensaje" => "Mesa no econtrada"));
                    }
                } else {
                    $payload = json_encode(array("mensaje" => "Producto no encontrado"));
                }
            } else {
                $payload = json_encode(array("mensaje" => "No se recibieron los parametros necesarios para el alta del pedido", "Datos"=>"idmesa, idproducto, cantidad"));
            }
        } catch (Exception $ex) {
            $payload = json_encode(array("mensaje" => "Se produjo un error ". $ex->getMessage()));
        }
                
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }



    public function TraerTodos(Request $request, Response $response, $args)
    {
        try {
            $pedidos =  Pedido::join('mesas', 'pedidos.idMesa', '=', 'mesas.idMesas')->join('productos', 'pedidos.idProducto', '=', 'productos.idProductos')->select('pedidos.idPedidos', 'pedidos.horaInicio', 'pedidos.horaEstFin', 'pedidos.horaFin', 'pedidos.estado', 'productos.tipo', 'productos.nombre', 'pedidos.cantidad')->get();
            $payload = json_encode(array("Pedido"=>$pedidos));
        } catch (Exception $ex) {
            $payload = json_encode(array("mensaje" => "Se produjo un error ". $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno(Request $request, Response $response, $args)
    {
        try {
            $parametros =  $request->getParsedBody();
            if (isset($parametros['idpedido']) && isset($parametros['estado']) && in_array($parametros['estado'], Pedido::ESTADO)) {
                $datosUsr =  Middleware::GetDatosUsuario($request);
                switch ($parametros['estado']) {
                    case "Preparacion":{
                        $pedidos =  Pedido::join('productos', 'pedidos.idProducto', 'productos.idProductos')->where('productos.sector', '=', $datosUsr->sector)->where('pedidos.estado', '=', 'Iniciado')->where('pedidos.idPedidos', '=', $parametros['idpedido'])->select('pedidos.*', 'productos.nombre', 'productos.sector')->first();

                        if ($pedidos) {
                            if (isset($parametros['horaestfin'])) {
                                $pedidos->idEncargado =  $datosUsr->idUsuario;
                                $pedidos->horaEstFin =  $parametros['horaestfin'];
                                $pedidos->estado = $parametros['estado'];
                                $pedidos->save();
                                $payload =  json_encode(array("mensaje"=>"Se modifico el estado del Pedido a En Preparacion"));
                            } else {
                                $payload =  json_encode(array("mensaje"=>"No se recibio la hora estimada de fin: horaestfin"));
                            }
                        } else {
                            $payload =  json_encode(array("mensaje"=>"No se encontro un pedido correspondiente al sector del usuarios con posibilidades de ser modificado"));
                        }
                        break;
                    }
                    case "Completado":{

                        $pedidos =  Pedido::join('productos', 'pedidos.idProducto', 'productos.idProductos')->where('productos.sector', '=', $datosUsr->sector)->where('pedidos.estado', '=', 'Preparacion')->where('pedidos.idPedidos', '=', $parametros['idpedido'])->where('pedidos.idEncargado', '=', $datosUsr->idUsuario)->select('pedidos.*', 'productos.nombre', 'productos.sector')->first();
                        if ($pedidos) {
                            $pedidos->estado = $parametros['estado'];
                            $pedidos->horaFin =  Date("H:i:s");
                            $pedidos->save();
                            $payload =  json_encode(array("mensaje"=>"Se modifico el estado del Pedido a Completado"));

                        } else {
                            $payload =  json_encode(array("mensaje"=>"No se encontro un pedido correspondiente al sector del usuarios con posibilidades de ser modificado"));
                        }
         
                        break;
                    }
                    default:{

                        $payload =  json_encode(array("mensaje"=>"Modificacion no permitida"));
                        break;
                    }
                }
            } else {
                $payload =  json_encode(array("mensaje"=>"No se recibio algunos de los parametros necesarios para la modificacion de un pedido o bien no se reconoce el estado","parametros"=>"idpedido, estado","estados"=>"Preparacion, Completado, Cancelado"));
            }
        } catch (Exception $ex) {
            $payload = json_encode(array("mensaje" => "Se produjo un error ". $ex->getMessage()));
        }
    
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno(Request $request, Response $response, $args)
    {
        $parametros =  $request->getParsedBody();

        try {
            if (isset($parametros['idpedido'])) {
                $pedido =  Pedido::find($parametros['idpedido']);
                if ($pedido) {
                    if ($pedido->estado != "Completado") {
                        $pedido->update(['estado'=>'Cancelado']);
                        $pedido->delete();
                        $payload = json_encode(array("mensaje" => "Pedido cancelado con exito"));
                    } else {
                        $payload = json_encode(array("mensaje" => "El pedido ya ha sido completado, no puede ser cancelado"));
                    }
                } else {
                    $payload = json_encode(array("mensaje" => "Pedido no encontrado"));
                }
            } else {
                $payload = json_encode(array("mensaje" => "No se recibidio algunos de los parametros necesarios para la cancelacion del pedido: idpedido"));
            }
        } catch (Exception $ex) {
            $payload = json_encode(array("mensaje" => "Se produjo un error ". $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function BuscarBaja(Request $request, Response $response, $args)
    {
        try {
            $pedidos = Pedido::get();

            //Pedido::onlyTrashed()->where('estado','=','Cancelado')->get();

            $payload =  json_encode(array("Bajas"=> $pedidos));
        } catch (Exception $ex) {
            $payload = json_encode(array("mensaje" => "Se produjo un error ". $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
