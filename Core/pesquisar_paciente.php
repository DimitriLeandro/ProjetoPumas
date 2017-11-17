<?php

if(file_exists("install/index.php")){
  //perform redirect if installer files exist
  //this if{} block may be deleted once installed
  header("Location: install/index.php");
}

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
?>



<?php
	require_once 'users/init.php';
	$db = DB::getInstance();
	if (!securePage($_SERVER['PHP_SELF'])){die();} 
?>

<html>
<head>
  	<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
  	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  	<script src="users/js/jquery.js"></script>
  	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  	<title>Pesquisar Paciente</title>
  	<meta charset="utf-8" />
  	<link href="css/formulario.css" rel="stylesheet">
</head>
<body>
  <div>
	<form method="post" class="form-style">
		<h1>PESQUISAR PACIENTE</h1>
	<fieldset>
		<label for="ncns" class="margem">Número CNS</label>
		<input type="number" name="cd_cns_paciente" id="cd_cns_paciente" /><br />
		
		<label for="nomep" class="margem">Nome</label>
		<input type="text" name="nm_paciente" id="nm_paciente" /><br />
	</fieldset><br />

	<p>
		<button type="button" class="botao" id="btn_pesquisar">Pesquisar</button>
		<button type="button" class="botao" id="btn_cadastrar">Cadastrar Novo Paciente</button>
	</p>
	<br />
	<div id="div_resultados">
	</div>
	</form>
  </div>
  	<script>
  		//funçao para completar o nome do paciente automaticamente
  		//exemplo usado desse link: https://www.devmedia.com.br/jquery-autocomplete-dica/28697
  		//primeiro ´e necess´ario buscar todos os nomes no banco
  		<?php
			require_once('php/model/conexao.Class.php');
			$conexao = new Conexao();
			$db_maua = $conexao -> conectar();

	  		$select = "SELECT nm_paciente FROM tb_paciente WHERE cd_paciente > ?;";
			if ($stmt = $db_maua->prepare($select))
			{
				$zero = 0; 
				$stmt -> bind_param('i', $zero);
				$stmt->execute();
				$stmt->bind_result($nome_paciente);
			}
  		?>
		$(function(){
			var nome_pacientes = [
				<?php
					while($stmt->fetch()) 
					{
						echo '"'.$nome_paciente.'",';
					}
				?>
		  	];
		  	$("#nm_paciente").autocomplete({
		    	source: nome_pacientes
		  	});
		});
	</script>
  	<script>
			//funçao de pesquisar
			$("#btn_pesquisar").on("click", function(){
				var txt_nome = $("#nm_paciente").val();
				var txt_cns = $("#cd_cns_paciente").val();
				var redirect = "php/div_pesquisar_paciente.php?nm_paciente="+txt_nome+"&cd_cns_paciente="+txt_cns;
				$("#div_resultados").load(redirect+"");
			});

			$("#btn_cadastrar").on("click", function(){
				window.location = "cadastrar_paciente.php";
			});

	</script>
</body>
</html>