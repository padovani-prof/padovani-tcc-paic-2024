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
$usuario_agendador='';
$vereficar = false;
$vere = '';

$id_retorno = 'danger';
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

function data_em_dia_semana($data){
    $dias = [
        'Sunday' => 1,
        'Monday' => 2,
        'Tuesday' => 3,
        'Wednesday' => 4,
        'Thursday' => 5,
        'Friday' => 6,
        'Saturday' => 7
    ];

    $diaSemana = $dias[date('l', strtotime($data))];
    return $diaSemana;
    


}

function verificar_permiçoes($listar_datas, $usuario_utilizador, $recurso){
    $str = '';
    foreach($listar_datas as $data){
        if(verificar_permicao_recurso($data['data'], $data['hora_inicial'], $data['hora_final'], $recurso, $usuario_utilizador, data_em_dia_semana($data['data']))){
            $str.='s';

        }else{
            $str.='n';
        }
    }
    return $str;

}


function verificar_disponibilidade($listar_datas, $recurso){
    $str = '';
    foreach($listar_datas as $data){
        if(count(Disponibilidade([$data['data'], $data['hora_inicial'], $data['hora_final']], [], [$recurso]))>0){
            $str.='s';

        }else{
            $str.='n';
        }
    }
    
    return $str;

}

function str_ini($lista_datas){
    $inf = 's';
    foreach($lista_datas as $t){
        $inf .='s';
    }
    return $inf;
}




function mostra_periodos_1($lista_datas, $inf){
    $data_reserva = '';
    foreach ($lista_datas as $key => $periodo) {
        
        $data = (is_string($periodo['data']) && !empty($periodo['data'])) ? explode('-', $periodo['data']) : [];
        
        if (count($data) == 3) {
            $data_reserva .= "<tr>
                <td>" .(($inf[$key] == 's')?"{$data[2]}/{$data[1]}/{$data[0]} de {$periodo['hora_inicial']} até {$periodo['hora_final']}": "<mark> {$data[2]}/{$data[1]}/{$data[0]} de {$periodo['hora_inicial']} até {$periodo['hora_final']}  </mark>").
                "</td><td><input type='submit' class='btn btn-outline-danger' name='remover_{$key}' value='Remover'></td>
            </tr>";
        } else {
            $data_reserva .= "<tr><td>Data inválida</td></tr>";
        }
    }
    return $data_reserva;

}




function mostra_periodos_2($lista_datas, $inf_disp, $inf_per, $html, $verificar){

    // não esquecer de colocar o n para s
    $data_reserva = '';
    $ht_dispo_e = '<td><span title="Este recurso já tem reserva para este periodo.">❌</span> </td>';
    $ht_dispo_v = '<td><span title="Recurso disponivel ok.">✅</span> </td> ';

    $ht_perm_e= '<td><span title="O usuario ultilizador não possui permissão para reservar este recurso neste periodo.">❌</span> </td>';
    $ht_perm_v= '<td><span title="Permição de recurso ok">✅</span>  </td>';
    foreach ($lista_datas as $key => $periodo) {
        
        $data = (is_string($periodo['data']) && !empty($periodo['data'])) ? explode('-', $periodo['data']) : [];
        
        if (count($data) == 3) {
            $data_reserva .= "
               " .(($verificar == true)?"".(($inf_disp[$key] == 's')?$ht_dispo_v:$ht_dispo_e). (($inf_per[$key] == 's')?$ht_perm_v:$ht_perm_e)."</td>":'')." <td> {$data[2]}/{$data[1]}/{$data[0]} de {$periodo['hora_inicial']} até {$periodo['hora_final']}".
                "</td><td><input type='submit' class='btn btn-outline-danger' name='remover_{$key}' value='Remover'></td>
            </tr>";
        } else {
            $data_reserva .= "<tr><td>Data inválida</td></tr>";
        }
    }

    $html = str_replace('{{Datas Reservas}}', $data_reserva, $html);
    
    

    return $html;

}


$id = remover_periodo($lista_datas);
if ($id != -1) {
    $justificativa = $_GET['justificativa'];
    $recurso = $_GET['recurso'];
    $usuario_utilizador = $_GET['usuario_utilizador'];
    $usuario_agendador = ($_GET['usuario_agendador']!='NULL')? $_GET['usuario_agendador'] : null;
    unset($lista_datas[$id]);
    $lista_datas = array_values($lista_datas);
    $mensagem = 'Período removido com sucesso!';
}


$inf = str_ini($lista_datas);

$inf_dispo = str_ini($lista_datas);

if (isset($_GET['btnSalvar'])) {
    $justificativa = $_GET['justificativa'];
    $recurso = $_GET['recurso'];
    $usuario_utilizador = $_GET['usuario_utilizador'];
    $usuario_agendador = ($_GET['usuario_agendador']!='NULL')? $_GET['usuario_agendador'] : null;
    if(($recurso=='NULL')){
        $mensagem = 'Selecione um recurso.';
        $recurso = null;
    }elseif ($usuario_agendador==null) {
        $mensagem = 'Selecione um agendador.';
    }elseif (empty( $_GET['justificativa'])) {
        $mensagem = 'Justificativa é obrigatória!';
    }elseif (count($lista_datas) == 0) {
        $mensagem = 'Adicione um Período';
    }
    elseif ($usuario_utilizador=='NULL') {
        $mensagem = 'Selecione um usuário ultilizador.';
        $usuario_utilizador = null;
    }
    else {
        // verifica se tem permição para reservar
        $inf =  verificar_permiçoes($lista_datas, $usuario_utilizador, $recurso);
        // consulta de disponibilidade
        $inf_dispo = verificar_disponibilidade($lista_datas, $recurso);

        if((mb_substr_count($inf_dispo, 'n')>0) or (mb_substr_count($inf, 'n')>0)){
            $vere = "<th>Disponivel</th><th>Permitido</th>";// tabelas de verificação
            $vereficar = true;
            $mensagem = "O ultilizador não pode reserva por não ter passado em alguma das verificações.";
        }else{
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
                'Reserva cadastrada com sucesso!',
            ];
            $mensagem = $mensagens[$resultado];
            
            if ($resultado == 5) {
                $id_retorno = 'success';
                $justificativa = '';
                $recurso = '';
                $usuario_utilizador = '';
                $usuario_agendador='';
                $lista_datas = [];
            }

        }
    
        
        
    }
    
   
}




if (isset($_GET['btnAdicionar']) ) {
    $justificativa = $_GET['justificativa'];
    $recurso = $_GET['recurso'];
    $usuario_utilizador = $_GET['usuario_utilizador'];
    $usuario_agendador = ($_GET['usuario_agendador']!='NULL')? $_GET['usuario_agendador'] : null;
    $data = $_GET['data'] ?? '';
    $hora_inicial = $_GET['hora_inicial'] ?? '';
    $hora_final = $_GET['hora_final'] ?? '';

    
    $mensagem = 'Preencha todos os campos do período!';
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
                $data = '';
                $hora_inicial = '';
                $hora_final = '';

            } else {
                $mensagem = 'Conflito de horário detectado!';
            }
        } else {
            $mensagem = 'Período inválido!';
        }
    }

    $html = str_replace('{{data}}', $data, $html);
    $html = str_replace('{{hora_inicial}}', $hora_inicial, $html);
    $html = str_replace('{{hora_final}}', $hora_final, $html);
}


$html = mostra_periodos_2($lista_datas, $inf_dispo, $inf, $html, $vereficar);


$ldatas = '<input type="hidden" name="lista_datas" value="' . urlencode(json_encode($lista_datas)) . '">';
$html = str_replace('{{Lista Data}}', $ldatas, $html);


$sel_recursos =  mandar_options($recursos, $recurso);
$sel_usuarios =  mandar_options($usuarios, $usuario_utilizador);
$sel_usuarios_agenda =  mandar_options($usuarios, $usuario_agendador);

$html = str_replace('{{verifica}}', $vere, $html);

$html = str_replace('{{UsuariosAgendador}}', $sel_usuarios_agenda, $html);
$html = str_replace('{{Campojustificativa}}', $justificativa, $html);
$html = str_replace('{{Recursos}}', $sel_recursos, $html);
$html = str_replace('{{Usuarios}}', $sel_usuarios, $html);

$html = str_replace('{{mensagem}}', $mensagem, $html);

$html = str_replace('{{retorno}}', $id_retorno, $html);


echo $html;
?>


