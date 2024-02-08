<?php

class TablaBase
{
	protected 	$db;  		//Objeto de la clase Asir2Database
	protected 	$tabla; 	//Nombre de la tabla sobre la que se ejecutarán las consultas

	public function __construct(Asir2Database $db, $tabla)
	{
		$this->db = $db;
		$this->tabla = $tabla;
	}

	public function getAll($listaCampos = '*')
	{
        $sql = "SELECT $listaCampos FROM {$this->tabla}" ;
        return $this->db->consulta($sql) ;
	}

	public function getById($id, $listaCampos = '*')
	{
	    $sql = "SELECT $listaCampos FROM {$this->tabla} WHERE id = ?";
		return $this->db->consulta($sql, [$id]) ;      
	}	

	public function deleteById($id)
	{
        $sql = "DELETE FROM {$this->tabla} WHERE id = ?";
        return $this->db->consulta($sql, [$id]) ;       
	}	
	
	public function insert($registro)  
	{	
		//$registro será un array asociativo con los campos y valores a insertar en la tabla

		$arrayCampos = [] ;	$arrayValores = [] ; $arrayInterrogaciones = [] ; 

		foreach ($registro as $campo => $valor) {
			$arrayCampos[] = $campo; 
			$arrayValores[] = $valor ;
			$arrayInterrogaciones[] = '?' ;
		}
		$listaCampos = implode(",", $arrayCampos);
		$listaInterrogaciones = implode(",", $arrayInterrogaciones);

	    //$sql = "INSERT INTO usuarios (usuario,clave) values (?,?) ";
	    $sql = "INSERT INTO {$this->tabla} ($listaCampos) values ($listaInterrogaciones) ";			

        //echo $sql . "<br>";
        return $this->db->consulta($sql, $arrayValores) ;       
	}	

	public function update($registro)  
	{
		//$registro será un array asociativo con los campos y valores que actualizar en la tabla

		$arraySet = [] ;	$arrayValores = [] ;	$arrayInterrogaciones = [] ; 
		 
		foreach ($registro as $campo => $valor) {
			$arraySet[] = "$campo = ?" ;
			$arrayValores[] = $valor ;
			$arrayInterrogaciones[] = '?' ;
		}

		$listaSet = implode(",", $arraySet);
		$listaInterrogaciones = implode(",", $arrayInterrogaciones);
		
		$arrayValores[] = $registro['id'] ;  //$registro siempre tiene que tener el campo id
		//$sql = "UPDATE usuarios SET usuario = ?, clave = ? WHERE id = ? " ;
		$sql = "UPDATE {$this->tabla} SET {$listaSet} WHERE id = ? " ;

        //echo $sql . "<br>";
        return $this->db->consulta($sql, $arrayValores) ;       
	}

}

class Usuarios extends TablaBase {
	public function __construct($db)
	{
		parent::__construct($db, 'usuarios') ;
	}	
}

class Categorias extends TablaBase {
	public function __construct($db)
	{
		parent::__construct($db, 'categorias') ;
	}	
}

class Productos extends TablaBase {
	public function __construct($db)
	{
		parent::__construct($db, 'productos') ;
	}	

	public function getWhereCategoria($idCategoria, $listaCampos = '*')
	{
        $sql = "SELECT $listaCampos FROM {$this->tabla} WHERE idcategoria = ?" ;
        return $this->db->consulta($sql, [$idCategoria]) ;
	}

}