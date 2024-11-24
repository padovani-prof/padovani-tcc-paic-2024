<?php


session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}


//include once 'mEnsalamento.php';
$html = file_get_contents('View/vEnsalamento.php');
include_once 'Model/mPeriodo.php';
include_once 'Model/mDisciplina.php';
include_once 'Model/mCategoriaRecurso.php';

$lista_de_periodos = carrega_periodo();
$lista_de_disciplina = carrega_disciplina();
$lista_de_salas = carrega_categorias_recurso();

$peri = '';
$disc = '';
$sala = '';
$categoria = '';


if (isset($_GET['Filtra']))
{
    if (isset($_GET['Periodo']) and isset($_GET['disciplina']) and isset($_GET['sala']))
    {
        // insira
    }
    else
    {
        echo 'tá vazio';
    }
}

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
}// falta fzaer para sala.

$html = str_replace('{{Periodo}}', $op_p, $html);
$html = str_replace('{{Disciplina}}', $op_d, $html);
$html = str_replace('{{Sala}}', $op_s, $html);
// insira aqui pra sala.
$html = str_replace('{{Categoria}}', $categoria, $html);

echo $html;
