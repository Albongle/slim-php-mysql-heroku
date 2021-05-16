<?php
require_once "./models/mesa.php";
require_once "./interfaces/IApiUsable.php";

class MesasApi extends Mesa implements IApiUsable{

    public function TraerTodos($request, $response, $args)
    {
        $listaMesas = Mesa::TraerTodasLasMesas();
        $jsonMesas = json_encode(array("listaMesas" => $listaMesas));
        $response->getBody()->write($jsonMesas);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $arrayDeParametros = $request->getParsedBody();
        $mesa =  new Mesa();
        $mesa->SetNombre($arrayDeParametros['nombre']);
        if($mesa->InsertarMesa()>0)
        {
            $response->getBody()->write("se guardo la Mesa, ". $mesa->MostrarDatos());
        }
        else{
            $response->getBody()->write("No se guardo nada");
        }
        return $response;
    }

    public function TraerUno($request, $response, $args)
    {
        $id=$args['id'];
    	$laMesa=Mesa::TraerUnaMesa($id);
        $jsonMesas = json_encode(array("Empleado" => $laMesa));
        $response->getBody()->write($jsonMesas);
    	return $response;

    }


}


?>