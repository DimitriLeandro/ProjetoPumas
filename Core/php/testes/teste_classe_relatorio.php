<pre>
    <?php
    require_once '../classes/relatorio.Class.php';
    $objRelatorio = new Relatorio();

    $data1 = '2017-01-01';
    $data2 = '2019-01-01';;
    
    //queixas recorrentes
    echo "<b><br/>Queixas Recorrentes</b>";
    $queixasRecorrentes = $objRelatorio->queixasRecorrentes($data1, $data2);
    foreach ($queixasRecorrentes as $row) {
        echo "<br/>" . $row["Queixa"] . "  " . $row["Qtd"];
    }

    //total de queixas
    echo "<br/><br/><br/><b>Total de Queixas: </b>" . $objRelatorio->totalAtendimentos($data1, $data2);

    //total de atendimentos por sexo
    $array_sexo = $objRelatorio->totalAtendimentosPorSexo($data1, $data2);
    echo "<br/><br/><br/><b>Total de atendimentos masculino: </b>" . $array_sexo["Masculino"] . "<br/><b>Total de atendimentos feminino: </b>" . $array_sexo["Feminino"];


    $array_idades = $objRelatorio->totalAtendimentosPorIdade($data1, $data2);
    echo "<br/><br/><br/><b>Atendimentos por Idade: </b>";
    echo "<br/>0 - 2:   " . $array_idades["zerodois"];
    echo "<br/>3 - 5:   " . $array_idades["trescinco"];
    echo "<br/>6 - 13:  " . $array_idades["seistreze"];
    echo "<br/>14 - 18: " . $array_idades["quatorzedezoito"];
    echo "<br/>19 - 40: " . $array_idades["dezenovequarenta"];
    echo "<br/>41 - 60: " . $array_idades["quarentaeumsessenta"];
    echo "<br/>60+: " . $array_idades["sessentamais"];
    ?>
</pre>