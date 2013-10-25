<?php

require_once("config/conexao.php");

class Dao extends Conexao {
	
	public function listaEstados(){
		$sql = "SELECT * FROM uf";
		$con = parent::__construct();
		$stmt = $con->prepare($sql);
		
		$stmt->execute();
		$resultado = $stmt;
		$con = NULL;
		
		return $resultado;
	}
	
	public function mediaOcorrenciaPorEstado($uf){
		
		$sql = "select ANO, QTD from ocorrencia_ano where ano between '2007' and '2013' and uf = :uf group by ANO";
		$con = parent::__construct();
		$stmt = $con->prepare($sql);
		
		$stmt->bindParam(":uf", $uf, PDO::PARAM_STR);
		
		$stmt->execute();
		$resultado = $stmt;
		$con = NULL;
		
		return $resultado;
	}
	
	public function acidentesAnualPorSexo ($uf, $sexo){
		
		$sql = "Select ocoano as ANO, pessexo as Sexo, QTD from ocorrencia_sexo where lbruf = :uf and pessexo = :sexo";
		$con = parent::__construct();
		$stmt = $con->prepare($sql);
		
		$stmt->bindParam(":sexo", $sexo, PDO::PARAM_STR);
		$stmt->bindParam(":uf", $uf, PDO::PARAM_STR);
		
		$stmt->execute();
		$resultado = $stmt;
		$con = NULL;
		
		return $resultado;
	}			
}

?>