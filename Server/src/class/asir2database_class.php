<?php

define('DB_HOST'	,'db');
define('DB_NAME'	,'rafa');  
define('DB_USER'	,'root');  
define('DB_PASS'	,'123456');
define('DB_CHAR'	,'utf8'		);

class Asir2Database 
{
	private $con=null;

	public function __construct($dbHost, $dbName, $dbUser, $dbPass, $dbChar='utf8' )  
	{
		try  
		{  
		    $this->con = new PDO('mysql:dbname=' . $dbName .';host=' . $dbHost . ';charset=' . $dbChar, $dbUser, $dbPass);
		    $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
		    $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
		}  
		catch(PDOException $e)
		{  
			die ('Error: ' . $e->getMessage() ) ;  
		}
	}

	public function desconecta()
	{
		$this->con = null ; 
	}

	public function consulta($sql, $arrayParam=[])
	{
		$retorno = 0;
		$tipoConsulta = strtoupper(substr(trim($sql), 0, 6)); 

		try  
		{  
	    	$sentencia = $this->con->prepare($sql);
	    	$sentencia->execute($arrayParam);
	    	$registrosAfectados = $sentencia->rowCount() ;
	    	if ( $registrosAfectados > 0)
	    	{
	    		switch ($tipoConsulta) 
	    		{
					case 'SELECT':
						$retorno = $sentencia->fetchAll(PDO::FETCH_ASSOC);
						break;
					
					case 'INSERT':
						$retorno = $this->con->lastInsertId();  //Retornaria el id del registro insertado
			    		break;

					case 'UPDATE':
					case 'DELETE':
						$retorno = $registrosAfectados ;  //1;
						break;
				}
	    	}
		}
		catch(PDOException $e)
		{  
			die ('Error: ' . $e->getMessage() ) ;   
		}
		return $retorno;
	}
}

?>
