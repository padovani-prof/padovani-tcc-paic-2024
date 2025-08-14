<?php

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_periodo', 2);

$html = file_get_contents('View/vFormularioPeriodo.php');

$nomeP = '';
$dataIn = '';
$dataFim = '';
$msm = '';
$respo = 'nada';
$tela = '';
$tipo_tela = 'Cadastrar';

include_once 'Model/mPeriodo.php';

if(isset($_GET['codigo']) or isset($_GET['t_codigo'])){

    // Atualização de período
    $tipo_tela = 'Atualizar';
    if(isset($_GET['codigo'])){
        $codigo = $_GET['codigo'];
        $dados = mandar_dados($codigo);
        $nomeP = $dados['nome'];
        $dataIn = $dados['dt_inicial'];
        $dataFim = $dados['dt_final'];
        $tela = '<input type="hidden" name="t_codigo" value="' .$codigo.'">';
        if(!Existe_esse_periodo($codigo)){
            header('Location: cPeriodo.php');
            exit();
        }

    } else {
        $codigo = $_GET['t_codigo'];
        if(!Existe_esse_periodo($codigo)){
            header('Location: cPeriodo.php');
            exit();
        }
        $tela = '<input type="hidden" name="t_codigo" value="' .$codigo.'">';
        $respo = 'danger';
        $nomeP =  $_GET['txtnome'];
        $dataIn = $_GET['data_ini'];
        $dataFim = $_GET['data_fim'];

        if (mb_strlen($_GET['data_ini'])>=10 && mb_strlen($_GET['data_fim'])>=10 && mb_strlen($_GET['txtnome'])>=6) {
            $ano_atual = date('Y');
            $mes_atual = date('m');
            $dia_atual = date('d');

            $quebra_data_ini = new DateTime($dataIn);
            $ano_ini = $quebra_data_ini->format('Y');
            $mes_ini = $quebra_data_ini->format('m');
            $dia_ini = $quebra_data_ini->format('d');

            $vereficar = verificar_periodo($codigo , $dataIn, $dataFim);
            $resposta = 2;

            if ($ano_ini > $ano_atual || ($ano_ini == $ano_atual && $mes_ini >= $mes_atual) || $vereficar) {
                $quebra_data_ini = new DateTime($dataFim);
                $ano_fim = $quebra_data_ini->format('Y');
                $mes_fim = $quebra_data_ini->format('m');
                $dia_fim = $quebra_data_ini->format('d');
                $resposta = 3;
                if ($ano_fim > $ano_ini || ($ano_fim == $ano_ini && $mes_fim > $mes_ini) || ($ano_fim == $ano_ini && $mes_fim == $mes_ini && $dataIn < $dia_fim) || $vereficar) {
                    $resposta = atualizar_periodo($codigo, $nomeP, $dataIn, $dataFim);
                }
            }

            $lMensagens = [
                'Período atualizado com sucesso.',
                'Não é possível cadastrar períodos com nomes repetidos.',
                'Data inicial inválida.',
                'Data final inválida.'
            ];
            $msm = $lMensagens[$resposta];

            if($resposta==0) {
                $respo = 'success';
                $nomeP = '';
                $dataIn = '';
                $dataFim = '';
            }
        } else {
            $msm = 'Por favor, preencha todos os campos obrigatórios.';
            if(mb_strlen($_GET['txtnome'])<6) {
                $msm = 'O nome do período informado é inválido.';
            }
        }
    }
} else if(isset($_GET['salvar'])) {
    $respo = 'danger';
    $nomeP = $_GET['txtnome'];
    $dataIn = $_GET['data_ini'];
    $dataFim = $_GET['data_fim'];

    if (mb_strlen($_GET['data_ini'])>=10 && mb_strlen($_GET['data_fim'])>=10 && mb_strlen($_GET['txtnome'])>=6) {
        $ano_atual = date('Y');
        $mes_atual = date('m');
        $dia_atual = date('d');

        $quebra_data_ini = new DateTime($dataIn);
        $ano_ini = $quebra_data_ini->format('Y');
        $mes_ini = $quebra_data_ini->format('m');
        $dia_ini = $quebra_data_ini->format('d');

        $resposta = 2;
        if ($ano_ini > $ano_atual || ($ano_ini == $ano_atual && $mes_ini >= $mes_atual)) {
            $quebra_data_ini = new DateTime($dataFim);
            $ano_fim = $quebra_data_ini->format('Y');
            $mes_fim = $quebra_data_ini->format('m');
            $dia_fim = $quebra_data_ini->format('d');
            $resposta = 3;
            if ($ano_fim > $ano_ini || ($ano_fim == $ano_ini && $mes_fim > $mes_ini) || ($ano_fim == $ano_ini && $mes_fim == $mes_ini && $dataIn < $dia_fim)) {
                $resposta = insere_periodo($nomeP, $dataIn, $dataFim);
            }
        }

        $lMensagens = [
            'Período cadastrado com sucesso.',
            'Não é possível cadastrar períodos com nomes repetidos.',
            'Data inicial inválida.',
            'Data final inválida.'
        ];
        $msm = $lMensagens[$resposta];

        if($resposta==0) {
            $respo = 'success';
            $nomeP = '';
            $dataIn = '';
            $dataFim = '';
        }
    } else {
        $msm = 'Por favor, preencha todos os campos obrigatórios.';
        if(mb_strlen($_GET['txtnome'])<6) {
            $msm = 'O nome do período informado é inválido.';
        }
    }
}

$html = str_replace('{{mandan}}', $respo, $html);
$html = str_replace('{{nomePeriodo}}', $nomeP, $html);
$html = str_replace('{{dataIni}}', $dataIn, $html);
$html = str_replace('{{dataFim}}', $dataFim, $html);
$html = str_replace('{{tipo_tela}}', $tela, $html);
$html = str_replace('{{tela}}', $tipo_tela, $html);
$html = cabecalho($html, 'Período');
$html = str_replace('{{mensagem}}', $msm, $html);
$html = str_replace('{{msg}}', $respo, $html);

echo $html;
?>
