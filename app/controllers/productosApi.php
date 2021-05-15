<?php
require_once "./models/prodcuto.php";
require_once "./interfaces/IApiUsable.php";
class ProductosApi extends Producto implements IApiUsable{

    public function TraerTodos($request, $response, $args)
    {
        $listraProductos = Empleado::TraerTodoLosEmpleados();
        $jsonProdcuto = json_encode(array("listaProductos" => $listraProductos));
        $response->getBody()->write($jsonProdcuto);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $producto =  new Producto();
        $producto->SetDatos($ArrayDeParametros['tipo'],$ArrayDeParametros['descripcion'],$ArrayDeParametros['stock'],$ArrayDeParametros['precio']);
        if($producto->InsertarEmpleado()>0)
        {
            $response->getBody()->write("se guardo el Producto, ". $producto->MostrarDatos());
        }
        else{
            $response->getBody()->write("No se guardo nada");
        }
        return $response;
    }

    public function TraerUno($request, $response, $args)
    {
        $id=$args['id'];
    	$elProdcuto=Producto::TraerUnProducto($id);
        $jsonProdcuto = json_encode(array("Prdocuto" => $elProdcuto));
        $response->getBody()->write($jsonProdcuto);
    	return $response;

    }

}




?>