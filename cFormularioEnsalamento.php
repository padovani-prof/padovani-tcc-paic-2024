<?php

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}

include_once 'Model/mPeriodo.php';
include_once 'Model/mDisciplina.php';
include_once 'Model/mEnsalamento.php';

$html = file_get_contents('View/vFormularioEnsalamento.php');
$lista_de_periodos = carrega_periodo();
$lista_de_disciplina = carrega_disciplina();
$lista_de_salas = carregar_salas();

$mensagem = '';
$peri = '';
$disc = '';
$sala = '';
$semana = '';
$hora_ini = '';
$hora_fin = '';
$dia_semana = "";

if (isset($_GET['salvar'])){

    $peri = $_GET['periodo'];
    $disc = $_GET['disciplina'];
    $sala = $_GET['sala'];
    $semana = (isset($_GET['DiaSemana']))?$_GET['DiaSemana']: [];
    $hora_ini = (isset($_GET['h_inicio']))?$_GET['h_inicio']: null;
    $hora_fin = (isset($_GET['h_fim']))?$_GET['h_fim']: null;

    if ($semana != null and $hora_ini != null and $hora_fin)
    {
        for ($y=1; $y <= 7; $y++)
        {
            $dia_semana .= in_array($y, $semana) ? 'S' : 'N';
        }
        $reserva = ensalamento($peri, $disc, $sala, $dia_semana, $hora_ini, $hora_fin);
        

    } else{
        $mensagem='Campo dias da semana ou hora inicial e final vazios';
    }


}


// procurar uma maneira de melhorar
$op_p = '';
foreach($lista_de_periodos as $periodo)
{
    $op_p = $op_p.'<option value="' .$periodo['codigo'].'"' . ($periodo['codigo'] == $peri ? ' selected' : '') . '> '.$periodo['nome'].'</option>';
}

$op_d = '';
foreach($lista_de_disciplina as $disciplina)
{
    $op_d = $op_d .'<option value="' .$disciplina['codigo'].'"' . ($disciplina['codigo'] == $disc ? ' selected' : '') . '> '.$disciplina['nome'].'</option>';
}

$op_s = '';
foreach($lista_de_salas as $salas)
{
    $op_s = $op_s.'<option value="' .$salas['codigo'].'"' . ($salas['codigo'] == $sala ? ' selected' : '') . '> '.$salas['nome'].'</option>';
}

$html = str_replace('{{Periodo}}', $op_p, $html);
$html = str_replace('{{Disciplina}}', $op_d, $html);
$html = str_replace('{{Sala}}', $op_s, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);

echo $html;