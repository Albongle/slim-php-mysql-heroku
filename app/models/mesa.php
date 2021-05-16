<?php

require_once "./db/AccesoDatos.php";
require_once "./interfaces/IMostraObjeto.php";
 class Mesa implements IMostrarObjeto{

    public $idMesas;
    public $nombre;
    public $estado;
    const ESTADO =  array("Cliente_Esperando","Cliente_Comiendo","Cliente_Pagando","Cerrada");

    public function __construct()
    {
        
    }
    public function SetNombre($value)
    {
        if(isset($value) && is_string($value))
        {
            $this->nombre =  $value;
            $this->SetEstado("Cerrada");
        }
    }
    public function SetEstado($value)
    {
        if (isset($value) && is_string($value) && in_array($value, self::ESTADO)) {
            $this->estado =  $value;
        } else {
            
            throw new Exception("Estado de mesa no permitido, solo se permite: Cliente_Esperando, Cliente_Comiendo ,Cliente_Pagando ,Cerrada");
        }
    }
    public function GetObjeto()
    {
        return array("nombre"=>$this->nombre, "estado"=>$this->estado);
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
    public function InsertarMesa()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO mesas (nombre, estado)VALUES(:nombre,:estado)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    public function ActulizarEstadoMesa($estado)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $this->SetEstado($estado);
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE mesas SET estado = :estado WHERE idMesas = :id");
        $consulta->bindValue(':estado', $this->$estado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->idMesas, PDO::PARAM_INT);
        $consulta->execute();
        return true;
    }
    public static function TraerTodasLasMesas()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM mesas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    public static function TraerUnaMesa($id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM mesas WHERE idMesas = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function BuscaMesaPorNombre($nombre)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM mesas WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }


 }



?>