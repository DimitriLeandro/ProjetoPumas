<?php
if (file_exists("install/index.php")) {
    //perform redirect if installer files exist
    //this if{} block may be deleted once installed
    header("Location: install/index.php");
}
require_once 'users/init.php';
require_once $abs_us_root . $us_url_root . 'users/includes/header.php';
//require_once $abs_us_root . $us_url_root . 'users/includes/navigation.php';
$db = DB::getInstance();
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}
?>

<?php
//essa pagina precisa do codigo da triagem no metodo GET para conseguir os dados dessa triagem no banco. Aqui esta sendo feita uma verificaçao pra saber se esse get foi setado e se o valor setado realmente ´e uma triagem existente no banco. Caso contrario, o usuario volta para o index

if (isset($_GET['cd_triagem']) && $_GET['cd_triagem'] != '') {
    //verificando se o valor existe no banco
    require_once('php/classes/triagem.Class.php');
    $triagem = new Triagem();

    $triagem->selecionar($_GET['cd_triagem']);

    if ($triagem->getCdTriagem() == '' || $triagem->getCdTriagem() == 0) {
        unset($triagem);
        header("location: index.php");
    } else {
        //pegando os dados do paciente só pra exibir o nome pelo menos
        require_once('php/classes/paciente.Class.php');
        $paciente = new Paciente();

        $paciente->selecionar($triagem->getCdPaciente());
    }
} else {
    unset($triagem);
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <title>Dados da Triagem</title>
        <meta charset="utf-8" />
        <link href="css/formulario.css" rel="stylesheet">
        <script src="users/js/jquery.js"></script>
        <script>
            function imprimir_triagem()
            {
                $("#frame_triagem").show();
                window.frames["frame_triagem"].focus();
                window.frames["frame_triagem"].print();
                $("#frame_triagem").hide();
            }
        </script>
    </head>
    <body>
        <?php require_once 'php/div_header.php'; ?>
        <iframe id="frame_triagem" name="frame_triagem" src="php/prontuario/prontuario.php?cd_triagem=<?php echo $_GET['cd_triagem']; ?>" hidden></iframe>
        <form method="post" class="form-style">
            <h1><?php echo $paciente->getNmPaciente(); ?></h1>
            <h4>Dados da Triagem</h4>
            <fieldset style="border: solid 1px; padding: 15px; height: 700px;">
                <div>
                    <p>
                        <?php
                        if ($triagem->getIcFinalizada() == 1) {
                            echo "Um diagnóstico para essa triagem já foi realizado.";
                        } else {
                            echo "Essa triagem ainda não foi finalizada. É necesário que um médico realize diagnóstico específico.";
                        }
                        ?> 
                    </p>
                </div>
                <iframe src="php/prontuario/prontuario_modelo_2.php?cd_triagem=<?php echo $triagem->getCdTriagem(); ?>"  style="top:0px; left:0px; bottom:0px; right:0px; width:100%; height:93%; border:none; margin:0; padding:0; overflow:hidden;"></iframe>
            </fieldset>
            <br/>
            <div>
                <?php
//VERIFICANDO SE H´A ALGUM DIAGNOSTICO PRA ESSA TRIAGEM
//SE N~AO TIVER, MOSTRA O BOT~AO PRA FAZER O DIAGNOSTICO
//SE TIVER, MOSTRA OS DADOS DO DIAGNOSTICO
                require_once 'php/classes/usuario.Class.php';
                $obj_usuario = new Usuario();
                if ($triagem->getIcFinalizada() == 0) {
                    if ($obj_usuario->getPermission() == "Secretario" || $obj_usuario->getPermission() == "Administrator") {
                        $redirectBtnDiagnostico = "cadastrar_diagnostico.php?cd_triagem=".$triagem->getCdTriagem();
                        ?>
                        <button type="button" id="<?php echo $redirectBtnDiagnostico; ?>" onclick="window.location = '<?php echo $redirectBtnDiagnostico; ?>';">Diagnóstico</button><br/>
                        <?php
                    }
                } else {
                    //instanciando um objeto da classe Dignostico para pegar as informaç~oes sobre o diagnostico dessa triagem
                    require_once("php/classes/diagnostico.Class.php");
                    $obj_diagnostico = new Diagnostico();

                    //inciando uma conex~ao com o banco
                    require_once("php/classes/conexao.Class.php");
                    $conexao = new Conexao();
                    $db_maua = $conexao->get_db_maua();

                    //pegando o id do diagnostico dessa triagem
                    if ($stmt = $db_maua->prepare("SELECT cd_diagnostico FROM tb_diagnostico WHERE cd_triagem = ?")) {
                        $stmt->bind_param("i", $_GET['cd_triagem']);
                        $stmt->execute();
                        $stmt->bind_result($codigo_diagnostico);
                        while ($stmt->fetch()) {
                            $obj_diagnostico->selecionar($codigo_diagnostico);
                        }
                        $stmt->close();
                    }
                    ?>
                    <h4>Dados do Diagnóstico</h4>
                    <fieldset style="border: solid 1px; padding: 15px;">
                        <p>UBS: <?php echo $obj_diagnostico->getCdUbs(); ?></p>
                        <p>Avaliaçao: <?php echo $obj_diagnostico->getDsAvaliacao(); ?></p>
                        <p>CID: <?php echo $obj_diagnostico->getCdCid(); ?></p>
                        <p>Prescriçao: <?php echo $obj_diagnostico->getDsPrescricao(); ?></p>
                        <p>Data: <?php echo $obj_diagnostico->getDtRegistro(); ?></p>
                        <p>Hora: <?php echo $obj_diagnostico->getHrRegistro(); ?></p>
                        <p>Situaçao: <?php echo $obj_diagnostico->getIcSituacao(); ?></p>
                        <p>Profissional que Realizou o diagnóstico: <?php echo $obj_diagnostico->getCdUsuarioRegistro(); ?></p>
                    </fieldset>
                    <?php
                }
                ?>
                <br/>
                <button type="button" onclick="javascript:history.back()">Voltar</button>
                <button type="button" onclick="imprimir_triagem()">Imprimir Triagem</button>
            </div>
        </form>
        <?php
        if (isset($_GET['printLayout'])) {
            //o GET printLayout serve para mostrar apenas os dados da triagem quando a página de cadastrar triagem for imprimir uma nova triagem.
            //nesse caso, o javascript abaixo esconderá os botões, cabeçalho etc etc
            ?>
            <script>
                $("div").each(function () {
                    $(this).hide();
                });
            </script>
            <?php
        }
        ?>
    </body>
</html>