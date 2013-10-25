<?php

require_once("dao/dao.php");
$dao = new Dao;

#LISTANDO A TABELA uf
$estados = $dao->listaEstados();

#MONTAR GRAFICO DE ACORDO COM 
#O UF SELECIONADO
if(isset($_GET['uf'])){
	$resultado = $dao->mediaOcorrenciaPorEstado($_GET['uf']);
	$mediaAcidentesFeminino = $dao->acidentesAnualPorSexo($_GET['uf'], 'F');
	$mediaAcidentesMasculino = $dao->acidentesAnualPorSexo($_GET['uf'], 'M');
}

?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	<title>BR Análise</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="imagetoolbar" content="no" />
	<link rel="stylesheet" href="styles/layout.css" type="text/css" />
	<link rel="stylesheet" href="styles/style.css" type="text/css" />
	<script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
	<script type="text/javascript" src="scripts/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	
	
/* MENU LISTA DE ESTADOS */
	$(function(){
		$('table tr')
		.mouseover(function(){
			$(this).addClass('over');
		})
		.mouseout(function(){
			$(this).removeClass('over');
		});
	});

/* DEFINE UF NA URL */
	function listarPorEstado(uf){
		window.location = "index.php?uf="+uf;
	}

/* DESENHA GRAFICO DE BARRAS */
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(desenhaGraficoBarras);
function desenhaGraficoBarras() {
	var data = google.visualization.arrayToDataTable([
    ['Ano', 'Ocorrencias'],
    <?php
		if(isset($resultado)){
			while($r = $resultado->fetch(PDO::FETCH_OBJ)){
				echo "['$r->ANO', $r->QTD],";
			}
		}
	?>
	]);
	
	var options = {
		title: 'Estatistica dos Ascidentes nas BRs do Estado <?php if(isset($_GET['uf'])){echo $_GET['uf'];}; ?>',
		hAxis: {title: 'Período'}
	};

	var chart = new google.visualization.ColumnChart(document.getElementById('grafico-barras'));
	chart.draw(data, options);
}

/* DESENHA GRAFICO DE PIZZA - MEDIA DE ACIDENTES FEMININO */
google.setOnLoadCallback(desenhaGraficoPizzaFeminino);
function desenhaGraficoPizzaFeminino() {
	var data = google.visualization.arrayToDataTable([
    ['Ano', 'Ocorrencias'],
	<?php
		if(isset($mediaAcidentesFeminino)){
			while($r = $mediaAcidentesFeminino->fetch(PDO::FETCH_OBJ)){
				echo "['$r->ANO', $r->QTD],";
			}
		}
	?>
	]);
		
	var options = {
    	title: 'Percentual de Acidentes do Sexo Feminino <?php if(isset($_GET['uf'])){echo $_GET['uf'];}; ?>'
    };

    var chart = new google.visualization.PieChart(document.getElementById('acidentes-femininos'));
    chart.draw(data, options);
}

/* DESENHA GRAFICO DE PIZZA - MEDIA DE ACIDENTES MASCULINO */
google.setOnLoadCallback(desenhaGraficoPizzaMasculino);
function desenhaGraficoPizzaMasculino() {
	var data = google.visualization.arrayToDataTable([
    	['Ano', 'Ocorrencias'],
		<?php
		if(isset($mediaAcidentesMasculino)){
			while($r = $mediaAcidentesMasculino->fetch(PDO::FETCH_OBJ)){
				echo "['$r->ANO', $r->QTD],";
			}
		}
	?>
	]);
		
	var options = {
    	title: 'Percentual de Acidentes do Sexo Masculino <?php if(isset($_GET['uf'])){echo $_GET['uf'];}; ?>'
    };

    var chart = new google.visualization.PieChart(document.getElementById('acidentes-masculino'));
    chart.draw(data, options);
}

</script>

</head>
<body>
	<div class="wrapper col0">  
</div>
<!-- ####################################################################################################### -->

<div class="wrapper col1">
  <div id="header">
    <div id="logo">
      <a href="index.php" title="Home"><img src="images/logo.png" /></a>
    </div>
    
    <br class="clear" />
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col2">
  <div id="topnav">   
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col3">
  <div id="featured_slide">
    <div id="featured_wrap">    
      <ul id="featured_tabs">
        <li><a href="#fc1">Ministério da Justiça<br />
          <span></span></a></li>
        <li><a href="#fc2">Base de Dados Livre<br />
          <span></span></a></li>
        <li><a href="#fc3">Nossos Estados<br />
          <span></span></a></li>
        <li class="last"><a href="#fc4">Policia Rodoviaria Federal<br />
          <span></span></a></li>
      </ul>
      
      <div id="featured_content">
        <div class="featured_box" id="fc1"><a href="http://www.justica.gov.br/portal/ministerio-da-justica/" target="_blank"><img src="images/demo/1.gif" alt="" /></a>
          
        </div>
        <div class="featured_box" id="fc2"><a href="http://dados.gov.br/" target="_blank"><img src="images/demo/2.gif" alt="" /></a>
         
        </div>
        <div class="featured_box" id="fc3"><a href="http://www.ibge.gov.br/estadosat/index.php" target="_blank"><img src="images/demo/3.gif" alt="" />
         
        </div>
        <div class="featured_box" id="fc4"><a href="http://www.dprf.gov.br/PortalInternet/index.faces" target="_blank"><img src="images/demo/4.gif" alt="" /></a>
          
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col4">
  <div id="container">
    <div id="hpage">
    	<div id="table-uf">
          <table style="width:100%;">
            	<tr style="background-color:#333; color:#FFF">
                	<td id="table-uf-uf">UF</td>
                    <td>ESTADO</td>
                </tr>			
			<?php
                if(isset($estados)){
					while($estado = $estados->fetch(PDO::FETCH_OBJ)){
						echo "
							<tr style=\"cursor:pointer;\" onclick=\"listarPorEstado('$estado->tufuf')\">
								<td id='table-uf-uf'>$estado->tufuf</td>
								<td>$estado->tufdenominacao</td>
							</tr>
						";
					}
				}
			?>                                        
            </table>            
		</div>
        
        <div id="table-uf" style="margin-left:20px; ">
        	<?php
			if(isset($_GET['uf'])){
			echo '
        	<div id="area-grafico">
           		<div id="grafico-barras" style="width: 738px; height: 300px; border:0px solid #333;\"></div>				
            </div> 
            <div id="area-grafico">
            	<div class="pizza" style="float:left;">
            		<div id="acidentes-femininos" style="width: 365px; height: 200px;"></div>
            	</div> 
            	<div class="pizza" style="float:right;">
            		<div id="acidentes-masculino" style="width: 365px; height: 200px;"></div>
           		</div>
            </div>';
			} else {
				echo '<h2>Selecione um Estado ao lado!</h2>';	
			}
            ?>                                   				
        </div>             
      <br class="clear" />
    </div>
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col5">
  <div id="footer">
    <div class="footbox">
      <h2>Descrição do Projeto</h2>
      <p align="justify">
      Realizado para o 2º Concurso de Aplicativos para Dados Abertos realizado por uma cooperação entre o Ministério da Justiça(MJ) e o Ministério do Planejamento, Orçamento e Gestão.
      </p>
    </div>
    <div class="footbox">
      <h2>Links</h2>
      <ul>
        <li class="last"><a href="http://www.justica.gov.br/portal/ministerio-da-justica/" target="_blank" >Ministério da Justiça</a></li>
        <li class="last"><a href="http://www.dprf.gov.br/PortalInternet/index.faces" target="_blank" >Polícia Rodoviária Federal</a></li>
        <li class="last"><a href="http://dados.gov.br/" target="_blank">Base de Dados Livre</a></li>
        <li class="last"><a href="http://www.ibge.gov.br/estadosat/index.php" target="_blank">IBGE</a></li>
        <li class="last"><a href="http://www.hostinger.com.br/" target="_blank">Hostinger</a></li>        
        <li class="last"><a href="https://github.com/" target="_blank">GitHub</a></li>                
      </ul>
    </div>
    <div class="footboxe">
    	<h2>Equipe</h2>
        <div id="equip-member">
        	Jaime Félix Oliveira Filho
        	<div class="icon-social">
            	<a href="#" title="Facebook" target="_blank"> 
        			<img src="images/facebook.png"/>
        		</a>
            </div>
            <div class="icon-social">
            	<a href="https://twitter.com/jaimefelixof" title="Twiter" target="_blank">
        			<img src="images/twitter.png"/>
        		</a>
            </div>
        </div>
        <div id="equip-member">
        	Paulo André Freire
        	<div class="icon-social">
            	<a href="#" title="Facebook" target="_blank"> 
        			<img src="images/facebook.png"/>
        		</a>
            </div>
            <div class="icon-social">
            	<a href="#" title="Twiter" target="_blank">
        			<img src="images/twitter.png"/>
        		</a>
            </div>
        </div>       
    </div>
    <div class="footbox">
      <h2>Licensa</h2>
      <ul>
        <li class="last"><a href="#" target="_blank" >Licença Pública Geral GNU</a></li>        
      </ul>
    </div>    
    <br class="clear" />
  </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper col6">
  <div id="copyright">
    <p class="fl_left">Copyright &copy; 2013 - Todos os Direitos Reservados - ADS."</p>    
    <br class="clear" />
  </div>
</div>
</body>
</html>
