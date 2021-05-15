<?php



require_once "./models/persona.php";
require_once "./db/AccesoDatos.php";

class Empleado extends Persona
{
    public $idEmpleados;
    public $funcion;
    public $fechaAlta;
    public $estado;
    const FUNCIONES = array("Bartender","Mozo","Cervecero","Cocinero");
    const ESTADO = array("Activo","Suspendido");

    public function __construct()
    {
    }
    public function SetDatos($nombre, $apellido, $funcion, $fechaAlta, $estado)
    {
        parent::SetNombre($nombre);
        parent::SetApellido($apellido);
        $this->SetFuncion($funcion);
        $this->fechaAlta = $fechaAlta;
        $this->SetEstado($estado);

    }


    private function SetFuncion($value)
    {
        if (isset($value) && is_string($value) && in_array($value, self::FUNCIONES)) {
            $this->funcion =  $value;
        } else {
            
            throw new Exception("Funcion del empleado no permitida, solo se permite: Bartender, Mozo, Cervecero, Cocinero");
        }
    }
    private function SetEstado($value)
    {
        if (isset($value) && is_string($value) && in_array($value, self::ESTADO)) {
            $this->estado =  $value;
        } else {
            
            throw new Exception("Estado del empleado no permitida, solo se permite: Activo, Suspendido");
        }
    }
    private function GetObjeto()
    {
        return array("idEmpleados"=>$this->idEmpleados,"nombre"=>$this->nombre,"apellido"=>$this->apellido,"funcion"=>$this->funcion,"fechaAlta"=>$this->fechaAlta,"estado"=>$this->estado);
    }

    /**
    * @return string Una cadena de texto todos los datos del Objeto en formato texto
    */
    public function MostrarDatos()
    {
        $returnAux="";
        $flag = 0;
        foreach ($this->GetObjeto() as $key => $value) {
            $flag++;
            if ($flag>1) {
                $returnAux.=" ";
            }
            $returnAux.=$key . ": " . $value;
        }
        $returnAux.="\r\n";
        return $returnAux;
    }
    public static function TraerTodoLosEmpleados()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from empleados");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }
    public function InsertarEmpleado()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO empleados (nombre,apellido,funcion,fechaAlta,estado)VALUES(:nombre,:apellido,:funcion,:fechaAlta,:estado)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':funcion', $this->funcion, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->horaIngreso, PDO::PARAM_STR);

        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
}
