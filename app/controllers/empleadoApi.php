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
        $empleado->SetDatos($ArrayDeParametros['nombre'],$ArrayDeParametros['apellido'],$ArrayDeParametros['funcion'],date("Y/m/d"),$ArrayDeParametros['horaIngreso'],$ArrayDeParametros['horaEgreso']);
        if($empleado->InsertarEmpleado()>0)
        {
            $response->getBody()->write("se guardo el Empleado");
        }
        else{
            $response->getBody()->write("No se guardo nada");
        }
        return $response;
    }
}



?>