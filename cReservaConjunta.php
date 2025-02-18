<?php

include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cons_disponibilidade', 2);


include 'Model/mFormulariorRetirada.php';
include  'Model/mReservaConjunta.php';
$dados = $_GET['marcas'];
$marca_ult = '';
$marca_agen = '';

$justific = '';
$msg = '';
if(isset($_GET['reservar'])){
    $dados = $_GET['marcas'];
    if(verificar_reservar($dados)==true){
        header('Location: cFiltroDisponibildade.php');
        exit();
    }
    
    $agendador = $_GET['agendador'];
    $utilizador = $_GET['utilizador'];
    $justific = $_GET['justfc'];
    $marca_ult = $utilizador;
    $marca_agen = $agendador;


    if($agendador == 'NULL'){
        // agendador
        $msg = 'Selecione o agendador.';
    }else if($utilizador == 'NULL'){
        // ultili
        $msg = 'Selecione o ultilizador.';

    }else{
        Reserva_conjunta ($dados, $agendador, $utilizador, $justific);
        // se der reserva disponibilidade 
        // joga pra disponibilidade
        header('Location: cReservas.php');
        exit();
    }
    
    
}else{
    
    $dados = string_pra_lista($dados);
}





$html = file_get_contents('View/vReservaConjunta.php');
$usuarios =  listar_usuarios();
$usuarios_agendador = mandar_options($usuarios, $marca_agen);
$usuario_ultilizador = mandar_options($usuarios, $marca_ult);
$hidem_dados = dados_hidem($dados);
$tabe_htm = tabe_html($dados);
$html = str_replace('{{dados}}', $hidem_dados, $html);
$html = str_replace('{{reservas}}', $tabe_htm, $html);
$html = str_replace('{{agendador}}', $usuarios_agendador, $html);
$html = str_replace('{{usuario}}', $usuario_ultilizador, $html);
$html = str_replace('{{just}}', $justific, $html);

$html = str_replace('{{msg}}', $msg, $html);
echo $html;

?>

