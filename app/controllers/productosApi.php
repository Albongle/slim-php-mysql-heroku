<?php
require_once "./models/producto.php";
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
        $elProducto =  new Producto();
        $elProducto->SetDatos($ArrayDeParametros['tipo'],$ArrayDeParametros['nombre'],$ArrayDeParametros['stock'],$ArrayDeParametros['precio']);
        $productoAux = Producto::BuscaUnProductoPorNombreYTipo($ArrayDeParametros['nombre'],$ArrayDeParametros['tipo']);
        
        if($elProducto->ValidarProducto($productoAux))
        {
            if(Producto::ActualizaStockProdcuto($productoAux[0]->idProductos,$elProducto->stock)>0){
                $response->getBody()->write("se Actulizo el stock del Producto, ". $elProducto->MostrarDatos());
            }
            else
            {
                $response->getBody()->write("Prodcuto existente pero no se hizo nada");
            }
        }else if($elProducto->InsertarProdcuto()>0)
        {
            $response->getBody()->write("se guardo el Producto, ". $elProducto->MostrarDatos());
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
        $jsonProdcuto = json_encode(array("Producto" => $elProdcuto));
        $response->getBody()->write($jsonProdcuto);
    	return $response;

    }

}




?>