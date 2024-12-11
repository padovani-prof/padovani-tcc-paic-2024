<?php

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}

include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_reserva');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}
include_once 'Model/mReserva.php'; 

$html = file_get_contents('View/vFormularioReserva.php');

$recursos = carregar_recurso();
$usuarios = carregar_usuario();

$justificativa = '';
$mensagem = '';
$recurso = '';
$usuario_utilizador = '';
$data_reserva = '';
$lista_datas = isset($_GET['lista_datas']) ? json_decode(urldecode($_GET['lista_datas']), true) : [];


if (!is_array($lista_datas)) {
    $lista_datas = []; 
}

function remover_periodo($lista_datas) {
    foreach ($lista_datas as $key => $data) {
        if (isset($_GET["remover_{$key}"])) {
            return $key;
        }
    }
    return -1;
}

if (isset($_GET['btnSalvar'])) {
    $justificativa = $_GET['justificativa'];
    $recurso = $_GET['recurso'];
    $usuario_utilizador = $_GET['usuario_utilizador'];
    $usuario_agendador = isset($_SESSION['usuario_agendador']) ? $_SESSION['usuario_agendador'] : null;

    if (isset($_SESSION['codigo_usuario'])) {
        $usuario_agendador = $_SESSION['codigo_usuario'];
    } else {
        return "Erro: Usuário não autenticado.";
    }

    $resultado = inserir_reserva($justificativa, $recurso, $usuario_utilizador, $lista_datas);

    $mensagens = [
        'Justificativa é obrigatória!',
        'Justificativa não pode ultrapassar 100 caracteres.',
        'Data e hora são obrigatórios!',
        'Data não pode ser no passado!',
        'Hora inicial deve ser antes da hora final!',
        'Reserva cadastrada com sucesso!'
    ];
    $mensagem = $mensagens[$resultado];

    if ($resultado == 5) {
        $justificativa = '';
        $recurso = '';
        $usuario_utilizador = '';
        $lista_datas = [];
    }
}

if (isset($_GET['btnAdicionar'])) {
    $data = $_GET['data'] ?? '';
    $hora_inicial = $_GET['hora_inicial'] ?? '';
    $hora_final = $_GET['hora_final'] ?? '';

    $mensagem = 'Preencha todos os campos!';

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
                
            
                if (
                    ($data_inicial < $fim_hr && $data_final > $inicio_hr)
                ) {
                    $conflito = true;
                    break; 
                }
            }

            if (!$conflito) {
                
                $lista_datas[] = ['data' => $data, 'hora_inicial' => $hora_inicial, 'hora_final' => $hora_final];
                $mensagem = 'Período adicionado com sucesso!';

            } else {
                $mensagem = 'Conflito de horário detectado!';
            }
        } else {
            $mensagem = 'Período inválido!';
        }
    }
}

$id = remover_periodo($lista_datas);
if ($id != -1) {
    unset($lista_datas[$id]);
    $lista_datas = array_values($lista_datas);
    $mensagem = 'Período removido com sucesso!';
}


$data_reserva = '';
foreach ($lista_datas as $key => $periodo) {
    
    $data = (is_string($periodo['data']) && !empty($periodo['data'])) ? explode('-', $periodo['data']) : [];
    
    if (count($data) == 3) {
        $data_reserva .= "<tr>
            <td>{$data[2]}/{$data[1]}/{$data[0]} de {$periodo['hora_inicial']} até {$periodo['hora_final']} </td>
            <td><input type='submit' name='remover_{$key}' value='Remover'></td>
        </tr>";
    } else {
        $data_reserva .= "<tr><td>Data inválida</td></tr>";
    }
}

$ldatas = '<input type="hidden" name="lista_datas" value="' . urlencode(json_encode($lista_datas)) . '">';
$html = str_replace('{{Lista Data}}', $ldatas, $html);


$sel_recursos = '';
foreach ($recursos as $rec) {
    $sel_recursos .= '<option value="'.$rec['codigo'].'"'.($rec['codigo'] == $recurso ? 'selected' : '').'>'.$rec['nome'].'</option>';
}

$sel_usuarios = '';
foreach ($usuarios as $us) {
    $sel_usuarios .= '<option value="'.$us['codigo'].'"'.($us['codigo'] == $usuario_utilizador ? 'selected' : '').'>'.$us['nome'].'</option>';
}


$html = str_replace('{{Campojustificativa}}', $justificativa, $html);
$html = str_replace('{{Recursos}}', $sel_recursos, $html);
$html = str_replace('{{Usuarios}}', $sel_usuarios, $html);
$html = str_replace('{{Datas Reservas}}', $data_reserva, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);

echo $html;
?>
