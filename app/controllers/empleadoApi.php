<?php
require_once "./models/empleado.php";
require_once "./interfaces/IApiUsable.php";
class EmpleadoApi extends Empleado implements IApiUsable{


    public function TraerTodos($request, $response, $args)
    {
        $listraEmpleados = Empleado::TraerTodoLosEmpleados();
        $jsonEmpleado = json_encode(array("listaEmpleados" => $listraEmpleados));
        $response->getBody()->write($jsonEmpleado);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $empleado =  new Empleado();
        $empleado->SetDatos($ArrayDeParametros['nombre'],$ArrayDeParametros['apellido'],$ArrayDeParametros['funcion'],date("Y/m/d"),$ArrayDeParametros['estado']);
        if($empleado->InsertarEmpleado()>0)
        {
            $response->getBody()->write("se guardo el Empleado, ". $empleado->MostrarDatos());
        }
        else{
            $response->getBody()->write("No se guardo nada");
        }
        return $response;
    }

    public function TraerUno($request, $response, $args)
    {
        $id=$args['id'];
    	$elEmpleado=Empleado::TraerUnEmpleado($id);
        $jsonEmpleado = json_encode(array("empleado" => $elEmpleado));
        $response->getBody()->write($jsonEmpleado);
    	return $response;

    }
}



?>