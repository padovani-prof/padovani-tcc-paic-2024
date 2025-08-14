<?php

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';

Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_periodo', 2);

include_once 'Model/mPeriodo.php';

$mensagem = '';
$id_resposta = 'nada';

// Exclusão de período
if (isset($_GET['apagar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_periodo', 2);
    $chave = $_GET['codigo_do_periodo'];
    
    Existe_essa_chave_na_tabela($chave, "periodo", "cPeriodo.php");
    
    $resposta = apagar_periodo($chave);
    if ($resposta) {
        $mensagem = 'O período foi apagado com sucesso.';
        $id_resposta = 'success';
    } else {
        $mensagem = 'Este período não pode ser removido, pois está sendo referenciado em uma disciplina.';
        $id_resposta = 'danger';
    }
}

// Atualização de período
elseif (isset($_GET['atualizar'])) {
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_periodo', 2);
    $codigo = $_GET['codigo_do_periodo'];
    header("Location: cFormularioPeriodo.php?codigo=$codigo");
    exit();
}

// Listagem de períodos
$periodos_array = carrega_periodo();
$periodos = '';
foreach ($periodos_array as $periodo) {
    $periodos .= '<tr>
        <td>'. $periodo["nome"] .'</td>
        <td>
            <form action="cPeriodo.php">   
                <input type="hidden" name="codigo_do_periodo" value="'. $periodo['codigo'] .'"> 
                <input class="btn btn-outline-secondary" type="submit" value="Alterar" name="atualizar"> 
                <input class="btn btn-outline-danger" type="submit" name="apagar" value="Apagar">
            </form> 
        </td>
    </tr>';
}

// Substituição no template HTML
$html = file_get_contents('View/vPeriodo.php');
$html = str_replace('{{mensagem}}', $mensagem, $html);
$html = str_replace('{{retorno}}', $id_resposta, $html);
$html = cabecalho($html, 'Períodos');
$html = str_replace('{{Categoria}}', $periodos, $html);

echo $html;

?>
