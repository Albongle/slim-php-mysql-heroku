<?php
require_once "./db/AccesoDatos.php";
require_once "./interfaces/IMostraObjeto.php";
class Producto implements IMostrarObjeto{

    public $idProductos;
    public $tipo;
    public $nombre;
    public $stock;
    public $precio;
    const TIPO = array("Comida","Bebida");

    public function __construct()
    {
        
    }
    public function SetDatos($tipo,$nombre, $stock,$precio)
    {
        $this->SetTipo($tipo);
        $this->nombre =  $nombre;
        $this->stock =  $stock;
        $this->precio =  $precio;
    }
    private function SetTipo($value)
    {
        if (isset($value) && is_string($value) && in_array($value, self::TIPO)) {
            $this->tipo =  $value;
        } else {
            
            throw new Exception("Funcion del producto no permitida, solo se permite: Comida o Bebida");
        }
    }
    public function GetObjeto()
    {
        return array("tipo"=>$this->tipo, "nombre"=>$this->nombre, "stock"=>$this->stock,"precio"=>$this->precio);
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


    public function Equals(Producto $producto)
    {

        return ($this->nombre == $producto->nombre) && ($this->tipo == $producto->tipo);
    }
    public Function ValidarProducto($array)
    {
        $returnAux = false;
        if(isset($array) && is_array($array))
        {
            foreach ($array as $key=> $value) 
            {
                if($this->Equals($value))
                {
                    $returnAux = true;
                    break;  
                }
            }
        }
        return $returnAux;
    }

    public static function TraerUnProducto($id)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM productos WHERE idProductos = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
    public static function BuscaUnProductoPorNombreYTipo($nombre, $tipo)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM productos WHERE nombre = :nombre AND tipo = :tipo");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
    public static function TraerTodoLosProductos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM productos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
    public function InsertarProdcuto()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO productos (tipo,nombre,stock,precio)VALUES(:tipo,:nombre,:stock,:precio)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    public static function ActualizaStockProdcuto($id, $stock)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE productos SET stock = :stock WHERE idProductos = :id");
        $consulta->bindValue(':stock', $stock, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return true;
    }

}




?>