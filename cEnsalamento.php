<?php

function Dados_Ensalamento($dados){
    $categoria = '';
    $categoria = '<tbody>';
    foreach ($dados as $controle){
        $categoria .= '<tr>
                <td>' . $controle['nome_recurso'] . '</td>
                <td>' . $controle['nome_periodo'] . '</td>
                <td>' . $controle['nome_disciplina'] . '</td>
                <td>' . gerarDiasDaSemana($controle['dias_semana']) . '</td>
                <td>' . $controle['hora_inicial'] . ' até ' .$controle['hora_final'] . '</td>
                <td> 
                    <form action="cEnsalamento.php">   
                        <input type="hidden" name="codigo_ensalamento" value="' .$controle['codigo'].  '"> 
                        <input class="btn btn-outline-danger" type="submit" name="apagar" value="Apagar">
                    </form> 
                </td>
            </tr>';
    }
    $categoria .= '<tbody/>';
    return $categoria;
}



include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_ensalamento', 2);
include_once 'Model/mPeriodo.php';
include_once 'Model/mDisciplina.php';
include_once 'Model/mEnsalamento.php';

$lista_de_periodos = carrega_periodo();
$lista_de_disciplina = carrega_disciplina();
$lista_de_salas = carregar_salas();
$peri = isset($_GET['periodo'])? $_GET['periodo']:null;
$disc = isset($_GET['disciplina'])? $_GET['disciplina']:null;
$sala = isset($_GET['sala'])? $_GET['sala']:null;

$msg = '';
$cor = 'danger';


if(isset($_GET['apagar']))
{
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_ensalamento', 2);
    $cod_ensalamento = $_GET['codigo_ensalamento'];
    if ($cod_ensalamento != null){
        $teste = apagar($cod_ensalamento);
        $msg = ($teste==true)?'Ensalamento apagado com sucesso.':'O ensalamento não pode ser apagado pois, já possuem retiradas relacionadas as reservas deste ensalamento.';//(Teste) apagou True, não apagou NULL
        $cor = ($teste==true)?'success':'danger';
    }

}

$dados = filtrar($peri, $disc, $sala);
$msg =  (count($dados)>0 or mb_strlen($msg)>0)?$msg:'Nem um ensalamento encontrado.';

$dados = Dados_Ensalamento($dados);
$op_p = gerarOpcoes($lista_de_periodos, $peri);
$op_d = gerarOpcoesDisciplina($lista_de_disciplina, $disc);
$op_s = gerarOpcoes($lista_de_salas, $sala);

$html = file_get_contents('View/vEnsalamento.php');
$html = str_replace('{{periodo}}', $op_p, $html);
$html = str_replace('{{disciplina}}', $op_d, $html);
$html = str_replace('{{sala}}', $op_s, $html);
$html = str_replace('{{Categoria}}', $dados, $html);
$html = str_replace('{{msg}}', $msg, $html);

$html = str_replace('{{cor}}', $cor, $html);
echo $html;

?>

