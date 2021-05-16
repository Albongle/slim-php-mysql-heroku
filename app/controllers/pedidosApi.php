<?php
require_once "./models/pedido.php";
require_once "./models/producto.php";
require_once "./interfaces/IApiUsable.php";

class PedidoApi extends Pedido implements IApiUsable{
    
    public function TraerTodos($request, $response, $args)
    {
        $listraPedidos = Pedido::TraerTodoLosPedidos();
        $jsonPedidos = json_encode(array("listaEmpleados" => $listraPedidos));
        $response->getBody()->write($jsonPedidos);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $arrayDeParametros = $request->getParsedBody();
        $producto =  Producto::BuscaUnProductoPorNombreYTipo($arrayDeParametros['nombreProducto'],$arrayDeParametros['tipoProducto']);
        $pedido =  new Pedido();
        $pedido->SetDatos($producto[0]->idProducto,$arrayDeParametros['idMesa'],$arrayDeParametros['horaEstimada'],$arrayDeParametros['cantidad']);
        if($pedido->InsertarPedido()>0)
        {
            $response->getBody()->write("se guardo el Pedido, ". $pedido->MostrarDatos());
        }
        else{
            $response->getBody()->write("No se guardo nada");
        }
        return $response;
    }

    public function TraerUno($request, $response, $args)
    {
        $id=$args['id'];
    	$elPedido=Pedido::TraerUnPedido($id);
        $jsonPedido = json_encode(array("Pedido" => $elPedido));
        $response->getBody()->write($jsonPedido);
    	return $response;

    }
}



?>