<?php 

function opition($perfis){
    $lista = carrega_opition();
    $usua = '<option value="NULL">...</option>';
    foreach($lista as $dados){
        $selected = ($dados['codigo'] == $perfis) ? ' selected' : '';
        $usua .= '<option title="'.$dados['descricao'].'" value="'.$dados['codigo'].'"'.$selected.'>'.$dados['nome'].'</option>';
    }
    return $usua;
}

function Tabela_acesso_recurso_carrega($codigo){
    $lista = recurso_carrega($codigo);
    $informa = '';
    foreach($lista as $dados){
        $informa .= '<tr>';
        $informa .= '<td>'. $dados["perfil"] .'</td>'; // coluna nome
        $informa .= '<td>'. $dados['ini'] .' - '. $dados['fim'] .'</td>'; // coluna horários
        $informa .= '<td>
                        <form action="cPermissaoRecurso.php">   
                            <input type="hidden" name="codigo_recurso" value="'. $codigo .'"> 
                            <input type="hidden" name="codigo_acesso_ao_recurso" value="'. $dados['cod'] .'"> 
                            <input class="btn btn-outline-danger" type="submit" name="apagar" value="Remover">
                        </form>
                      </td>';
        $informa .= '</tr>';
    }
    return $informa;
}

function marcar_semana($semanas, $html){
    $semanas = explode(',', $semanas);
    for ($i = 0; $i < 7; $i++) { 
        if($semanas[$i] == 'S'){
            $html = str_replace("{{{$i}}}", "checked", $html);
        }
    }
    return $html;
}

function dias_da_semana(){
    $semana = '';
    $cont = 0;
    for ($i = 0; $i < 7; $i++){
        if (isset($_GET["dia$i"])) {
            $semana .= 'S,';
        } else {
            $cont++;
            $semana .= 'N,';
        }
    }
    return ($cont >= 7) ? '' : $semana;
}

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'adm_perm_recurso', 2);

include_once 'Model/mPermissao.php';

$recurso_codigo  = $_GET['codigo_recurso'];
Existe_essa_chave_na_tabela($recurso_codigo, 'recurso', 'cRecursos.php');

$html = file_get_contents('View/vPermissao.php');

$marcar = '';
$id_msg = (isset($_GET['msg_id'])) ? $_GET['msg_id'] : 'danger';
$msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
$hora_ini = '';
$hora_fim = '';
$data_ini = '';
$data_fim = '';
$tabela = '';

// Salvar novo acesso
if(isset($_GET['salvar'])){
    $marcar = $_GET['perfio_usuario'];
    $hora_ini = $_GET['hora_ini'];
    $hora_fim = $_GET['hora_fim'];
    $semanas = dias_da_semana();
    $data_ini = $_GET['data_ini'];
    $data_fim = $_GET['data_fim'];

    if($marcar != 'NULL' && mb_strlen($hora_ini) == 5 && mb_strlen($hora_fim) == 5 && mb_strlen($data_ini) == 10 && $semanas != ''){
        date_default_timezone_set('America/Manaus'); 
        $periodo_H_ini = new DateTime("$data_ini $hora_ini");
        $periodo_H_fim = new DateTime("$data_ini $hora_fim");
        $data_atual = new DateTime();
        $data_ini_max = new DateTime("$data_ini 23:59");

        if($periodo_H_ini > $periodo_H_fim){
            $msg = 'O horário final não pode ser anterior ao horário inicial.';
        } else if($data_atual > $data_ini_max){
            $msg = 'A data inicial não pode ser anterior à data atual.';
        } else {
            $periodo_D_fim = new DateTime("$data_fim 23:59");
            $data_ini_min = new DateTime("$data_ini 00:00");

            if(mb_strlen($data_fim) == 10 && $periodo_D_fim < $data_ini_min){
                $msg = 'A data final não pode ser anterior à data inicial ou à data atual.';   
            } else {
                cadastra_acesso_recurso($recurso_codigo, $marcar, $hora_ini, $hora_fim, $semanas, $data_ini, $data_fim);
                $msg = 'O acesso ao recurso foi cadastrado com sucesso.';
                $id_msg = 'success';
                header("Location: cPermissaoRecurso.php?codigo_recurso=$recurso_codigo&msg=$msg&msg_id=$id_msg");
                exit();
            }
        }
    } else {
        $msg = 'Por favor, preencha todos os dados essenciais para o cadastro.';
    }

    if($id_msg == 'danger' && strlen($semanas) != 0){
        $html = marcar_semana($semanas, $html);
    }
}
// Remover acesso
else if(isset($_GET['apagar'])){
    verificação_acesso($_SESSION['codigo_usuario'], 'adm_perm_recurso', 2);
    $chave_ac = $_GET['codigo_acesso_ao_recurso'];
    apagar_acesso_ao_recurso($chave_ac);
    $msg = 'O acesso ao recurso foi removido com sucesso.';
    $id_msg = 'success';
    header("Location: cPermissaoRecurso.php?codigo_recurso=$recurso_codigo&msg=$msg&msg_id=$id_msg");
    exit();
}

$nome_recurso = nome_recurso($recurso_codigo);
$tabela = Tabela_acesso_recurso_carrega($recurso_codigo);
$opt = opition($marcar);

$html = str_replace('{{permissoes}}', $tabela, $html);
$html = str_replace('{{perfis}}', $opt, $html);
$html = str_replace('{{nomerecurso}}', $nome_recurso, $html);
$html = cabecalho($html, 'Permissão do Recurso');
$html = str_replace('{{rep}}', $id_msg, $html);
$html = str_replace('{{mensagemAnomalia}}', $msg, $html);
$html = str_replace('{{codigorecurso}}', $recurso_codigo, $html);

$html = str_replace('{{horaInicial}}', $hora_ini, $html);
$html = str_replace('{{horaFinal}}', $hora_fim, $html);
$html = str_replace('{{dataIni}}', $data_ini, $html);
$html = str_replace('{{dataFinal}}', $data_fim, $html);

echo $html;
?>
