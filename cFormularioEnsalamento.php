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
$dia_semana = '';
$usuario_agendador = isset($_SESSION['codigo_usuario']) ? $_SESSION['codigo_usuario'] : null;
$reserva = '';
$cod_reserva = '';
$datas_de_aula = '';
$vet_mensagem = [
    'Ensalamento feito com sucesso',
    'Erro!!! disciplina não está cadastrada para esse período', 
    'Verifique se o campo dias da semana e hora inicial/final está preencido !!!'
];
$justificativa = 'Ensalamento de períodos';

if (isset($_GET['salvar'])){


    $peri = $_GET['periodo'];
    $disc = $_GET['disciplina'];
    $sala = $_GET['sala'];
    $semana = (isset($_GET['DiaSemana']))?$_GET['DiaSemana']: [];
    $hora_ini = (isset($_GET['h_inicio']))?$_GET['h_inicio']: null;
    $hora_fin = (isset($_GET['h_fim']))?$_GET['h_fim']: null;

    $mensagem = '';

    if ($semana != null and $hora_ini != null and $hora_fin)
    {
        for ($y=1; $y <= 7; $y++)
        {
            $dia_semana .= in_array($y, $semana) ? 'S' : 'N';
        }
        $reserva = ensalamento($peri, $disc, $sala, $dia_semana, $hora_ini, $hora_fin);

        $cod_reserva = cod_ensalamento($disc, $sala, $dia_semana, $hora_ini, $hora_fin, $usuario_agendador, $justificativa);

        $datas_de_aula = dias_aulas($peri, $dia_semana);
        
        // $test = dias_aulas($peri);
        // var_dump($test);
        
        
        var_dump($cod_reserva);
        $mensagem = $vet_mensagem[$reserva];

        $peri = '';
        $disc = '';
        $sala = '';
        $semana = '';
        $hora_ini = '';
        $hora_fin = '';
        $reserva = '';
        $cod_reserva = '';
        $datas_de_aula = '';

        
    } else
    {
        $mensagem = $vet_mensagem[2];
    }


}



$op_p = gerarOpcoes($lista_de_periodos, $peri);
$op_d = gerarOpcoes($lista_de_disciplina, $disc);
$op_s = gerarOpcoes($lista_de_salas, $sala);


$html = str_replace('{{Periodo}}', $op_p, $html);
$html = str_replace('{{Disciplina}}', $op_d, $html);
$html = str_replace('{{Sala}}', $op_s, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);

echo $html;