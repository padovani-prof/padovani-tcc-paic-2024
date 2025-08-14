<?php

function adaptar_disponibilidade_hora($datas_de_aula, $hora_ini, $hora_fin, $recurso){
    foreach ($datas_de_aula as $data) {
        $periodo = [$data, $hora_ini, $hora_fin];
        $disponivel = count(Disponibilidade($periodo, [], [$recurso])) > 0;
        if (!$disponivel){
            return false;
        }
    }
    return true;
}

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_ensalamento', 2);

include_once 'Model/mPeriodo.php';
include_once 'Model/mDisciplina.php';
include_once 'Model/mEnsalamento.php';
include_once 'Model/mDisponibilidade.php';

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
    'Ensalamento realizado com sucesso.',
    'Erro: a disciplina selecionada não está cadastrada para este período.', 
    'Erro: verifique se os campos "Dias da Semana" e "Horário Inicial/Final" foram preenchidos corretamente.'
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
        $disponivel = adaptar_disponibilidade_hora($datas_de_aula, $hora_ini, $hora_fin, $sala);
        if ($disponivel == true){
            $reserva = ensalamento($disc, $sala, $dia_semana, $hora_ini, $hora_fin);
            $cod_reserva = cod_ensalamento($disc, $sala, $dia_semana, $hora_ini, $hora_fin, $usuario_agendador, $justificativa, $datas_de_aula);
            $mensagem = $vet_mensagem[$reserva];
            $disc = '';
            $sala = '';
            $semana = '';
            $hora_ini = '';
            $hora_fin = '';
            $reserva = '';
            $cod_reserva = '';
            $datas_de_aula = '';
            $id_msg = 'success';

        }else{
            $id_msg = 'danger';
            $mensagem = 'Conflito de reserva detectado: o recurso selecionado não está disponível nos horários informados.';
        }

    } else {
        $mensagem = $vet_mensagem[2];
    }

}

$op_d = gerarOpcoesDisciplina($lista_de_disciplina, $disc);
$op_s = mandar_options($lista_de_salas, $sala);

$html = str_replace('{{Disciplina}}', $op_d, $html);
$html = str_replace('{{Sala}}', $op_s, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);
$html = cabecalho($html, 'Ensalamento');
$html = str_replace('{{retorno}}', $id_msg, $html);

echo $html;
?>
