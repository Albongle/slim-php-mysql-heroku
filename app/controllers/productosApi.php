<?php
require_once "./models/producto.php";
require_once "./interfaces/IApiUsable.php";
class ProductosApi extends Producto implements IApiUsable{

    public function TraerTodos($request, $response, $args)
    {
        $listaProductos = Producto::TraerTodoLosProductos();
        $jsonProdcuto = json_encode(array("listaProductos" => $listaProductos));
        $response->getBody()->write($jsonProdcuto);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $arrayDeParametros = $request->getParsedBody();
        $elProducto =  new Producto();
        $elProducto->SetDatos($arrayDeParametros['tipo'],$arrayDeParametros['nombre'],$arrayDeParametros['stock'],$arrayDeParametros['precio']);
        $productoAux = Producto::BuscaUnProductoPorNombreYTipo($arrayDeParametros['nombre'],$arrayDeParametros['tipo']);
        
        if($elProducto->ValidarProducto($productoAux))
        {
            if($productoAux[0]->ActualizaStockProdcuto($elProducto->stock)){
                $response->getBody()->write("se Actulizo el stock del Producto, ". $productoAux[0]->MostrarDatos()."Nuevo stock ".$elProducto->stock);
            }
            else
            {
                $response->getBody()->write("Producto existente pero no se hizo nada");
            }
        }else if($elProducto->InsertarProducto()>0)
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