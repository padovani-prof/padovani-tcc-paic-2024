<?php


include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_ensalamento', 2);


include_once 'Model/mPeriodo.php';
include_once 'Model/mDisciplina.php';
include_once 'Model/mEnsalamento.php';

$html = file_get_contents('View/vFormularioEnsalamento.php');
$lista_de_periodos = carrega_periodo();
$lista_de_disciplina = carrega_disciplina();
$lista_de_salas = carregar_salas();

$mensagem = '';
$id_msg = 'danger';
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
    'Ensalamento feito com Sucesso',
    'Erro!!! disciplina não está Cadastrada para esse Período', 
    'Verifique se o campo dias da semana e hora inicial/final está preencido !!!'
];
$justificativa = 'Ensalamento de períodos';


if (isset($_GET['salvar'])){

    $disc = $_GET['disciplina'];
    $sala = $_GET['sala'];
    $semana = (isset($_GET['DiaSemana']))?$_GET['DiaSemana']: [];
    $hora_ini = (isset($_GET['h_inicio']))?$_GET['h_inicio']: null;
    $hora_fin = (isset($_GET['h_fim']))?$_GET['h_fim']: null;

    $mensagem = '';

    if ($semana != null and $hora_ini != null and $hora_fin != null)
    {
        for ($y=1; $y <= 7; $y++)
        {
            $dia_semana .= in_array($y, $semana) ? 'S' : 'N';
        }

        $datas_de_aula = dias_aulas($disc, $dia_semana);

        $consulta = cosultas ($disc, $sala, $dia_semana, $hora_ini, $hora_fin, $datas_de_aula);
        
        // var_dump($consulta);

        if ($consulta !== null){
            $reserva = ensalamento($disc, $sala, $dia_semana, $hora_ini, $hora_fin);

            $cod_reserva = cod_ensalamento($disc, $sala, $dia_semana, $hora_ini, $hora_fin, $usuario_agendador, $justificativa, $datas_de_aula);
            $mensagem = $vet_mensagem[$reserva];

        }

        

        $disc = '';
        $sala = '';
        $semana = '';
        $hora_ini = '';
        $hora_fin = '';
        $reserva = '';
        $cod_reserva = '';
        $datas_de_aula = '';
        $id_msg = 'success';

        
    } else
    {
        $mensagem = $vet_mensagem[2];
    }


}

$op_d = gerarOpcoesDisciplina($lista_de_disciplina, $disc);
$op_s = mandar_options($lista_de_salas, $sala);


$html = str_replace('{{Disciplina}}', $op_d, $html);
$html = str_replace('{{Sala}}', $op_s, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);

$html = str_replace('{{retorno}}', $id_msg, $html);

echo $html;
