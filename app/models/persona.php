<?php

abstract class Persona{
    public $nombre;
    public $apellido;

    public function __construct()
    {

    }
    protected function SetNombre($value)
    {
        if($this->ValidaNombreApellido($value))
        {
            $this->nombre =  $value;
        }
        else
        {
            throw new Exception("Nombre erroneo");
        }
    }
    protected function SetApellido($value)
    {
        if($this->ValidaNombreApellido($value))
        {
            $this->apellido =  $value;
        }
        else
        {
            throw new Exception("Apellido erroneo");
        }
    }
    private function ValidaNombreApellido($value)
    {
        $returnAux= false;
        if(isset($value) && is_string($value) && preg_match("/^[A-Za-z]{3,20}\ ?+[A-Za-z]{0,20}$/",$value))
        {
            $returnAux= true;
        }
        return $returnAux;
    }
}

?>