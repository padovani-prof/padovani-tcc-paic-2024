<?php

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_reserva', 2);

include_once 'Model/mReserva.php'; 
include 'Model/mDisponibilidade.php';

$html = file_get_contents('View/vFormularioReserva.php');

$recursos = carregar_recurso();
$usuarios = carregar_usuario();

$justificativa = '';
$mensagem = '';
$recurso = '';
$usuario_utilizador = '';
$data_reserva = '';
$usuario_agendador = '';
$vereficar = false;
$vere = '';
$id_retorno = 'danger';

$lista_datas = isset($_GET['lista_datas']) ? json_decode(urldecode($_GET['lista_datas']), true) : [];
if (!is_array($lista_datas)) $lista_datas = [];

function remover_periodo($lista_datas) {
    foreach ($lista_datas as $key => $data) {
        if (isset($_GET["remover_{$key}"])) return $key;
    }
    return -1;
}

function data_em_dia_semana($data){
    $dias = [
        'Sunday' => 1, 'Monday' => 2, 'Tuesday' => 3, 'Wednesday' => 4,
        'Thursday' => 5, 'Friday' => 6, 'Saturday' => 7
    ];
    return $dias[date('l', strtotime($data))];
}

function verificar_permissoes($listar_datas, $usuario_utilizador, $recurso){
    $str = '';
    foreach ($listar_datas as $data) {
        $str .= verificar_permicao_recurso(
            $data['data'], $data['hora_inicial'], $data['hora_final'], 
            $recurso, $usuario_utilizador, data_em_dia_semana($data['data'])
        ) ? 's' : 'n';
    }
    return $str;
}

function verificar_disponibilidade($listar_datas, $recurso){
    $str = '';
    foreach ($listar_datas as $data) {
        $str .= (count(Disponibilidade([$data['data'], $data['hora_inicial'], $data['hora_final']], [], [$recurso])) > 0) ? 's' : 'n';
    }
    return $str;
}

function str_ini($lista_datas){
    return str_repeat('s', count($lista_datas) + 1);
}

function mostra_periodos_2($lista_datas, $inf_disp, $inf_per, $html, $verificar){
    $data_reserva = '';
    $ht_dispo_e = '<td><span title="Este recurso já possui reserva para este período.">❌</span></td>';
    $ht_dispo_v = '<td><span title="Recurso disponível.">✅</span></td>';
    $ht_perm_e = '<td><span title="O usuário não possui permissão para reservar este recurso neste período.">❌</span></td>';
    $ht_perm_v = '<td><span title="Permissão de recurso confirmada.">✅</span></td>';

    foreach ($lista_datas as $key => $periodo) {
        $data = (is_string($periodo['data']) && !empty($periodo['data'])) ? explode('-', $periodo['data']) : [];
        if (count($data) == 3) {
            $data_reserva .= "<tr>"
                .(($verificar) ? (($inf_disp[$key]=='s') ? $ht_dispo_v : $ht_dispo_e)
                    . (($inf_per[$key]=='s') ? $ht_perm_v : $ht_perm_e) : '')
                ." <td> {$data[2]}/{$data[1]}/{$data[0]} de {$periodo['hora_inicial']} até {$periodo['hora_final']}</td>
                <td><input type='submit' class='btn btn-outline-danger' name='remover_{$key}' value='Remover'></td>
            </tr>";
        } else {
            $data_reserva .= "<tr><td>Data inválida</td></tr>";
        }
    }
    return str_replace('{{Datas Reservas}}', $data_reserva, $html);
}

// Remover período
$id = remover_periodo($lista_datas);
if ($id != -1) {
    $justificativa = $_GET['justificativa'];
    $recurso = $_GET['recurso'];
    $usuario_utilizador = $_GET['usuario_utilizador'];
    $usuario_agendador = ($_GET['usuario_agendador']!='NULL')? $_GET['usuario_agendador'] : null;
    unset($lista_datas[$id]);
    $lista_datas = array_values($lista_datas);
    $mensagem = 'Período removido com sucesso.';
}

// Inicialização
$inf = str_ini($lista_datas);
$inf_dispo = str_ini($lista_datas);

// Adicionar reserva
if (isset($_GET['btnSalvar'])) {
    $justificativa = trim($_GET['justificativa']);
    $recurso = $_GET['recurso'];
    $usuario_utilizador = $_GET['usuario_utilizador'];
    $usuario_agendador = ($_GET['usuario_agendador']!='NULL') ? $_GET['usuario_agendador'] : null;

    if ($recurso=='NULL') $mensagem = 'Por favor, selecione um recurso.';
    elseif ($usuario_agendador==null) $mensagem = 'Por favor, selecione um agendador.';
    elseif (empty($justificativa)) $mensagem = 'Justificativa é obrigatória.';
    elseif (count($lista_datas) == 0) $mensagem = 'Adicione pelo menos um período.';
    elseif ($usuario_utilizador=='NULL') { 
        $mensagem = 'Por favor, selecione um usuário utilizador.';
        $usuario_utilizador = null;
    } else {
        $inf = verificar_permissoes($lista_datas, $usuario_utilizador, $recurso);
        $inf_dispo = verificar_disponibilidade($lista_datas, $recurso);

        if ((mb_substr_count($inf_dispo,'n')>0) || (mb_substr_count($inf,'n')>0)) {
            $vere = "<th>Disponível</th><th>Permitido</th>";
            $vereficar = true;
            $mensagem = "O usuário não possui permissão para reservar algum período.";
        } else {
            $usuario_agendador = $_SESSION['codigo_usuario'] ?? null;
            if (!$usuario_agendador) die("Erro: Usuário não autenticado.");

            $resultado = inserir_reserva($justificativa, $recurso, $usuario_utilizador, $lista_datas);
            $mensagens = [
                'Justificativa é obrigatória.',
                'Justificativa não pode ultrapassar 100 caracteres.',
                'Data e hora são obrigatórios.',
                'A data não pode estar no passado.',
                'A hora inicial deve ser anterior à hora final.',
                'Reserva cadastrada com sucesso.'
            ];
            $mensagem = $mensagens[$resultado];
            if ($resultado == 5) {
                $id_retorno = 'success';
                $justificativa = $recurso = $usuario_utilizador = $usuario_agendador = '';
                $lista_datas = [];
            }
        }
    }
}

// Adicionar período
if (isset($_GET['btnAdicionar'])) {
    $justificativa = trim($_GET['justificativa']);
    $recurso = $_GET['recurso'];
    $usuario_utilizador = $_GET['usuario_utilizador'];
    $usuario_agendador = ($_GET['usuario_agendador']!='NULL') ? $_GET['usuario_agendador'] : null;

    $data = $_GET['data'] ?? '';
    $hora_inicial = $_GET['hora_inicial'] ?? '';
    $hora_final = $_GET['hora_final'] ?? '';
    $mensagem = 'Preencha todos os campos do período.';

    if ($data && $hora_inicial && $hora_final) {
        date_default_timezone_set('America/Manaus');
        $data_atual = new DateTime();
        $data_inicial = new DateTime("$data $hora_inicial");
        $data_final = new DateTime("$data $hora_final");

        if ($data_atual <= $data_inicial && $data_inicial < $data_final) {
            $conflito = false;
            foreach ($lista_datas as $periodo) {
                $inicio_hr = new DateTime($periodo['data'] . ' ' . $periodo['hora_inicial']);
                $fim_hr = new DateTime($periodo['data'] . ' ' . $periodo['hora_final']);
                if ($data_inicial < $fim_hr && $data_final > $inicio_hr) {
                    $conflito = true; break;
                }
            }
            if (!$conflito) {
                $lista_datas[] = ['data'=>$data,'hora_inicial'=>$hora_inicial,'hora_final'=>$hora_final];
                $mensagem = 'Período adicionado com sucesso.';
                $data = $hora_inicial = $hora_final = '';
            } else $mensagem = 'Conflito de horário detectado.';
        } else $mensagem = 'Período inválido.';
    }

    $html = str_replace('{{data}}', $data, $html);
    $html = str_replace('{{hora_inicial}}', $hora_inicial, $html);
    $html = str_replace('{{hora_final}}', $hora_final, $html);
}

$html = mostra_periodos_2($lista_datas, $inf_dispo, $inf, $html, $vereficar);

$ldatas = '<input type="hidden" name="lista_datas" value="' . urlencode(json_encode($lista_datas)) . '">';
$html = str_replace('{{Lista Data}}', $ldatas, $html);

$sel_recursos = mandar_options($recursos, $recurso);
$sel_usuarios = mandar_options($usuarios, $usuario_utilizador);
$sel_usuarios_agenda = mandar_options($usuarios, $usuario_agendador);

$html = str_replace('{{verifica}}', $vere, $html);
$html = str_replace('{{UsuariosAgendador}}', $sel_usuarios_agenda, $html);
$html = str_replace('{{Campojustificativa}}', $justificativa, $html);
$html = str_replace('{{Recursos}}', $sel_recursos, $html);
$html = str_replace('{{Usuarios}}', $sel_usuarios, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);
$html = cabecalho($html, 'Reserva');
$html = str_replace('{{retorno}}', $id_retorno, $html);

echo $html;

?>
