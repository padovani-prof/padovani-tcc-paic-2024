<?php

function Tabela_chacklist($dados, $marcado){
    $aux = "<tr>"; 
    $cont = 1;
    foreach ($dados as $linha) {
        $mar =  in_array($linha['codigo'], $marcado) ? ' checked ' : '';
        $aux .= '
        <td><input '.$mar.' type="checkbox" name="dados[]" value="'. $linha['codigo'] .'">'.$linha['item'].($cont % 3 == 0 ? '<br>&nbsp;</td></tr>' : '<br>&nbsp;</td>');
        $cont ++;
    }
    $aux .= '<input type="hidden" name="nada_marcado" value="nada">';
    return $aux;
} 

function optios($dados){
    $opt = '<option value="NULL">...</option>';
    foreach($dados as $dado) {
        $opt .= '<option title="'.'" value="'. $dado['codigo'].'">'.mb_strtoupper($dado['nome']).'</option>';
    }
    return $opt;
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
    return $dias[date('l', strtotime($data))];
}

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();

include 'Model/mFormulariorRetirada.php';
include 'Model/mDisponibilidade.php';
include 'Model/mChecklist.php';

if (isset($_GET['cancela'])){

    verificação_acesso($_SESSION['codigo_usuario'], 'cancela_retirada', 2);

    $checklist = '';
    $recursos_emprestados = carrega_recursos_emprestados();
    $usuarios = listar_usuarios();
    $tela = 'Cancelar Retirada';
    $id_msg = 'danger';
    $msg = (count($recursos_emprestados) > 0)
        ? 'Selecione o recurso e o usuário que realizou a retirada para cancelá-la.'
        : 'No momento, não há nenhum recurso retirado.';
    $recurso = '';
    $devolvente = '';

    if (isset($_GET['btnConfirmar'])) {
        $recurso = $_GET['recurso'];
        $devolvente = $_GET['devolvente'];
        if ($_GET['recurso'] != 'NULL' && $_GET['devolvente'] != 'NULL') {
            $resposta = cancelaRetirada($recurso, $devolvente);
            if ($resposta) {
                $msg = 'Retirada cancelada com sucesso!';
                $id_msg = 'success';
                $recursos_emprestados = carrega_recursos_emprestados();
                $recurso = '';
                $devolvente = '';
            } else {
                $msg = 'Retirada não encontrada. Selecione corretamente as informações da retirada.';
            }
        } else {
            $msg = 'Selecione todas as informações.';
        }
    }

    $recursos_emprestados = mandar_options($recursos_emprestados, $recurso);
    $usuarios = mandar_options($usuarios, $devolvente);

    $html = file_get_contents('View/vFormularioDevolucao.php');
    $html = str_replace('Devolvente: ', 'Retirante: ', $html);
    $html = str_replace('{{tela}}', $tela, $html);
    $html = str_replace('{{mensagem}}', $msg, $html);
    $html = str_replace('{{retorno}}', $id_msg, $html);
    $html = str_replace('{{chechlistRecurso}}', $checklist, $html);
    $html = str_replace('{{texto}}', 'Retirada', $html);
    $html = str_replace('{{link}}', 'cFormularioRetirada.php', $html);
    $html = str_replace('{{recursos}}', $recursos_emprestados, $html);
    $html = str_replace('{{devolvente}}', $usuarios, $html);
    $html = str_replace('{{mandar}}', 'cFormularioRetirada.php', $html);
    $html = str_replace('{{cancelaRetirada}}', '<input type="hidden" name="cancela" value="true">', $html);
    $html = cabecalho($html, 'Cancelar Retirada');
    echo $html;

} else {
    verificação_acesso($_SESSION['codigo_usuario'], 'cad_retir_devoluc', 2);

    $html = file_get_contents('View/vFormulariorRetirada.php');
    $senha_usuario = '';
    $msg = '';
    $id_msg = 'danger'; 
    $marca_recu = '';
    $marca_reti = '';
    $mar_hora = '';
    $checklistRecurso = '';

    if (isset($_POST['btnConfirmar'])) {
        $recurso = $_POST['recurso'];
        $retirante = $_POST['retirante'];
        $hora_fim = $_POST['hora_final'];
        $marca_recu = $recurso;
        $marca_reti = $retirante;
        $mar_hora = $hora_fim;

        date_default_timezone_set('America/Manaus'); 
        $data_atual = new DateTime();
        $data = (new DateTime())->format('Y/m/d');
        $data_devolucao = new DateTime("$data $hora_fim");

        if (mb_strlen($hora_fim) == 5 && $data_atual < $data_devolucao && $recurso != 'NULL' && $retirante != 'NULL') {
            $hora_ini = $data_atual->format('H:i:s');
            $hora_fim .= ':00';
            $tem_permissao = verificar_permicao_recurso($data, $hora_ini, $hora_fim, $recurso, $retirante, data_em_dia_semana($data));

            if ($tem_permissao) {
                $disponiveis = Disponibilidade([$data, $hora_ini, $hora_fim], [], [$recurso]);
                $disponiveis = count($disponiveis) > 0;
                $sua_reserva = verificar_reserva_do_retirante([$data, $hora_ini, $hora_fim], $retirante, $recurso);

                if ($disponiveis || $sua_reserva) {
                    $data_hora = $data.' '.$hora_ini;
                    $checklistRecurso = carrega_dados($recurso);
                    $checklistMarcado = isset($_POST['dados']) ? $_POST['dados'] : [];

                    $msg = (count($checklistRecurso) > 0) 
                        ? 'Selecione apenas os itens do checklist correspondentes ao recurso que será retirado e '
                        : '';
                    $msg .= 'solicite ao Retirante que informe sua senha.';

                    $focar_senha = '';
                    $confirmar_senha = false;

                    if (isset($_POST['senha_usuario'])) {
                        $focar_senha = ' autofocus ';
                        $senha_usuario = hash('sha256', $_POST['senha_usuario']);
                        $confirmar_senha = Confirmar_usuario_retirada($retirante, $senha_usuario);
                        $msg = $confirmar_senha ? '' : 'Senha inválida. Digite novamente.';
                    }

                    if ((isset($_POST['nada_marcado']) || count($checklistRecurso) == 0) && $confirmar_senha) {
                        $senha_usuario = '';
                        $id_reserva = $disponiveis ? criar_reserva_retirada($retirante, $recurso, $data, $hora_ini, $hora_fim) : null;
                        $id_retirada = insere_reserva_devolucao($retirante, $recurso, $data_hora, $hora_fim, 'R', $id_reserva);
                        retirada_checklist($checklistRecurso, $checklistMarcado, $id_retirada);

                        $msg = 'Recurso retirado com sucesso!';
                        $id_msg = 'success';
                        $marca_recu = '';
                        $marca_reti = '';
                        $mar_hora = '';
                        $checklistRecurso = '';

                    } else {
                        $checklistRecurso = Tabela_chacklist($checklistRecurso, $checklistMarcado);   
                        $senha_usuario = '<label for="">Senha do Retirante</label>
                                          <input type="password" name="senha_usuario"'.$focar_senha.'>';
                    }  
                } else {
                    $msg = 'Este recurso está reservado para outro usuário.';
                }

            } else {
                $msg = 'O Retirante não possui permissão para retirar este recurso.';
            }

        } else {
            $msg = (mb_strlen($hora_fim) != 5 || $recurso == 'NULL' || $retirante == 'NULL')
                ? 'Por favor, preencha todos os campos corretamente.'
                : 'Horário de devolução inválido.';
        }
    }

    $recursos_reserva = carrega_retirada_disponivel();
    $retirantes = listar_usuarios();

    $opicoes_recurso = mandar_options($recursos_reserva, $marca_recu);
    $opicoes_retirantes = mandar_options($retirantes, $marca_reti);

    $html = str_replace('{{chechlistRecurso}}', $checklistRecurso, $html); 
    $html = cabecalho($html, 'Retirada');
    $html = str_replace('{{retirante}}', $opicoes_retirantes, $html);
    $html = str_replace('{{recursos}}', $opicoes_recurso, $html);
    $html = str_replace('{{mensagem}}', $msg, $html);
    $html = str_replace('{{retorno}}', $id_msg, $html);
    $html = str_replace('{{hora_fim}}', $mar_hora, $html);
    $html = str_replace('{{senha_usuario}}', $senha_usuario, $html);

    echo $html;
}

?>
