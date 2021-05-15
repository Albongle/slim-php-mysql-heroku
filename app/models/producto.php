<?php
require_once "./interfaces/IMostrarObjeto.php";
class Producto implements IMostrarObjeto{

    public $idProdcutos;
    public $descripcion;
    public $stock;
    public $precio;

    public function __construct()
    {
        
    }
    public function SetDatos($descripcion, $stock,$precio)
    {
        $this->descripcion =  $descripcion;
        $this->stock =  $stock;
        $this->precio =  $precio;
    }
    public function GetObjeto()
    {
        return array("idProductos"=>$this->idProdcutos, "descripcion"=>$this->descripcion, "stock"=>$this->stock,"precio"=>$this->precio);
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

}




?>