<?php

class Conexao{
	
	static private $con;
	
	public function __construct(){
		if(!self::$con){
			try{
				self::$con = new PDO("mysql:host=localhost;dbname=branalise", "root", "123456") or die("Natureza do Erro: ".mysql_error);	
			} catch(PDOException $e) {
				die("PDO CONNECTION ERROR: ". $e->getMessage() ."<br /><br />");
			}
		}
		return self::$con;
	}	
}

?>