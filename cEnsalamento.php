<?php

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



$peri = '';
$disc = '';
$sala = '';
$categoria = '';
$filtra = '';
$cod_ensalamento = '';


if(isset($_GET['apagar']))
{
    verificação_acesso($_SESSION['codigo_usuario'], 'apag_ensalamento', 2);
    $cod_ensalamento = $_GET['codigo_ensalamento'];
    
    if ($cod_ensalamento != null){
        $teste = apagar($cod_ensalamento);
    }

   
//     if(apagar($cod_ensalamento) === true)
//     {
//         //ver uma mensagem para usar
//         echo 'sucesso';
//     }
}


$filtra = filtrar();

if (!empty($filtra)) 
{
    $categoria = '<tbody>';
    foreach ($filtra as $controle){
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
}


if (isset($_GET['filtrar']))
{
   
    $peri = $_GET['periodo'];
    $disc = $_GET['disciplina'];
    $sala = $_GET['sala'];

	
    $filtra = filtrar($peri, $disc, $sala);

    if (!empty($filtra)) 
    {
        $categoria = '<tbody>';
        foreach ($filtra as $controle){
            $categoria .= '<tr>
                    <td>' . $controle['nome_recurso'] . '</td>
                    <td>' . $controle['nome_periodo'] . '</td>
                    <td>' . $controle['nome_disciplina'] . '</td>
                    <td>' . gerarDiasDaSemana($controle['dias_semana']) . '</td>
                    <td>' . $controle['hora_inicial'] . ' ' .$controle['hora_final'] . '</td>
                    <td> 
                        <form action="cEnsalamento.php">   
                            <input type="hidden" name="codigo_ensalamento" value="' .$controle['codigo'].  '"> 
                            <input class="btn btn-outline-danger" type="submit" name="apagar" value="Apagar">
                        </form> 
                    </td>
                </tr>';
        }
        $categoria .= '<tbody/>';
    }

 
}




$op_p = gerarOpcoes($lista_de_periodos, $peri);
$op_d = gerarOpcoesDisciplina($lista_de_disciplina, $disc);
$op_s = mandar_options($lista_de_salas, $sala);

$html = file_get_contents('View/vEnsalamento.php');
$html = str_replace('{{periodo}}', $op_p, $html);
$html = str_replace('{{disciplina}}', $op_d, $html);
$html = str_replace('{{sala}}', $op_s, $html);
$html = str_replace('{{Categoria}}', $categoria, $html);

echo $html;
