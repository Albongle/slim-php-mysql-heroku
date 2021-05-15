<?php
require_once "./db/AccesoDatos.php";
require_once "./interfaces/IMostraObjeto.php";
class Producto implements IMostrarObjeto{

    public $idProdcutos;
    public $tipo;
    public $descripcion;
    public $stock;
    public $precio;
    const TIPO = array("Comida","Bebida");

    public function __construct()
    {
        
    }
    public function SetDatos($tipo,$descripcion, $stock,$precio)
    {
        $this->SetTipo($tipo);
        $this->descripcion =  $descripcion;
        $this->stock =  $stock;
        $this->precio =  $precio;
    }
    private function SetTipo($value)
    {
        if (isset($value) && is_string($value) && in_array($value, self::TIPO)) {
            $this->funcion =  $value;
        } else {
            
            throw new Exception("Funcion del producto no permitida, solo se permite: Comida o Bebida");
        }
    }
    public function GetObjeto()
    {
        return array("idProductos"=>$this->idProdcutos,"tipo"=>$this->tipo, "descripcion"=>$this->descripcion, "stock"=>$this->stock,"precio"=>$this->precio);
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

    public static function TraerUnProducto($id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from prodcutos where idEmpleados = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Prodcuto');
    }
    public static function TraerTodoLosEmpleados()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from productos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Prodcuto');
    }
    public function InsertarEmpleado()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO productos (tipo,descripcion,stock,precio)VALUES(:tipo,:descripcion,:stock,:precio)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

}




?>