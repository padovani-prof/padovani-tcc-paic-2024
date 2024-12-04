<?php
session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}
include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_disciplina');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}


include_once 'Model/mPeriodo.php';
$lista_de_periodos = carrega_periodo();

$peri = '';
$nome = '';
$curso = '';
$mens = '';


if(isset($_GET['salvar']))
{
    include 'Model/mDisciplina.php';
    $nome = $_GET['nome'];
    $curso = $_GET['curso'];
    $peri = $_GET['periodo'];
    $resp = insere_disciplina($nome, $curso, $peri);

    if($resp==0)
    {
        $peri = '';
        $nome = '';
        $curso = '';
    }
 

    $lMensa = ['Disciplina cadastrada com Sucesso!!', 'Nome do curso inválido','Nome inválido', 'Diciplina já cadastrada'];


    $mens = $lMensa[$resp];
}



$op = '';

foreach($lista_de_periodos as $periodo)
{
    $op = $op.'<option value="' .$periodo['codigo'].'"' . ($periodo['codigo'] == $peri ? ' selected' : '') . '> '.$periodo['nome'].'</option>';
}


$html = file_get_contents('View/vFormularioDisciplina.php');

$html = str_replace('{{Camponome}}', $nome, $html);
$html = str_replace('{{Campocurso}}', $curso, $html);
$html = str_replace('{{Periodo}}', $op, $html);
$html = str_replace('{{mensagem}}', $mens, $html);


echo $html;
?>






