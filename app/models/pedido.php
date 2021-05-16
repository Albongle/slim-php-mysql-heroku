<?php
require_once "./db/AccesoDatos.php";
require_once "./interfaces/IMostraObjeto.php";
class Pedido implements IMostrarObjeto
{
    public $idPedidos;
    public $idProducto;
    public $idMesa;
    public $horaInicio;
    public $horaEstimada;
    public $horaFin;
    public $cantidad;
    public $estado;
    const ESTADOS = array("Iniciado", "Terminado", "Cancelado");
    

    public function __construct()
    {
    }
    public function SetDatos($idProducto, $idMesa, $horaEstimada, $cantidad)
    {
        $this->idProducto =  $idProducto;
        $this->idMesa = $idMesa;
        $this->cantidad = $cantidad;
        $this->SetHoraInicio(date("H:i:s"));
        $this->horaEstimada =  $horaEstimada;
        $this->SetEstado("Iniciado");
    }
    public function SetHoraInicio($value)
    {
        if (isset($value) && is_a($value, "DateTime")) {
            if ($value < $this->horaFin) {
                $this->horaInicio = $value;
            }
        }
    }
    public function SetHoraFin($value)
    {
        if (isset($value) && is_a($value, "DateTime")) {
            if ($value > $this->horaInicio) {
                $this->horaFin = $value;
            }
        }
    }
    public function SetEstado($value)
    {
        if (isset($value) && is_string($value) && in_array($value, self::ESTADOS)) {
            $this->estado =  $value;
        } else {
            throw new Exception("Estado no permitido, solo se permite: Iniciado,Terminado, Cancelado");
        }
    }
    public function GetObjeto()
    {
        return array("idPedidos"=>$this->idPedidos,"idProducto"=>$this->idProducto,"idMesa"=>$this->idMesa,"cantidad"=>$this->cantidad,"estado"=>$this->estado,"horaInicio"=>$this->horaInicio,"horaEstimada"=>$this->horaEstimada,"horaFin"=>$this->horaFin);
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
    public function InsertarPedido()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO pedidos (idProducto,idMesa,horaInicio,horaEstimada,cantidad,estado)VALUES()");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    public function ModificarEstadoPedido($estado)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $this->SetEstado($estado);
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos SET estado = :estado, horaFin = :horaFin WHERE idPedidos = :id");
        $consulta->bindValue(':estado', $this->$estado, PDO::PARAM_STR);
        $consulta->bindValue(':horaFin', date("H:i:s"), PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->idPedidos, PDO::PARAM_INT);
        $consulta->execute();
        return true;
    }
    public static function TraerTodoLosPedidos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function TraerUnPedido($id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM pedidos WHERE idPedidos = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
}
