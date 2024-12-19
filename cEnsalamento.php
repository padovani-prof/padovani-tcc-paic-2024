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

$lista_de_periodos = carrega_periodo();
$lista_de_disciplina = carrega_disciplina();
$lista_de_salas = carregar_salas();

$peri = '';
$disc = '';
$sala = '';
$categoria = '';
$filtra = '';

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
                    <td>' . $controle['hora_inicial'] . ' ' .$controle['hora_final'] . '</td>
                    <td>'.'</td>
                    
                  </tr>';
        }
        $categoria .= '<tbody/>';
    }


if (isset($_GET['filtrar']))
{
   
    $peri = $_GET['periodo'];
    $disc = $_GET['disciplina'];
    $sala = $_GET['sala'];

    // fazer o dinamico 
	

 
}

$op_p = gerarOpcoes($lista_de_periodos, $peri);
$op_d = gerarOpcoes($lista_de_disciplina, $disc);
$op_s = gerarOpcoes($lista_de_salas, $sala);

$html = file_get_contents('View/vEnsalamento.php');
$html = str_replace('{{periodo}}', $op_p, $html);
$html = str_replace('{{disciplina}}', $op_d, $html);
$html = str_replace('{{sala}}', $op_s, $html);
$html = str_replace('{{Categoria}}', $categoria, $html);

echo $html;