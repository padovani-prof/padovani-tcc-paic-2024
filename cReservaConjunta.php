<?php

include 'Model/mFormulariorRetirada.php';
include  'Model/mReservaConjunta.php';

if(isset($_GET['reservar'])){
    $dados = $_GET['dados'];
    if(verificar_reservar($dados)==true){
        header('Location: cFiltroDisponibildade.php');
        exit();
    }
    $agendador = $_GET['agendador'];
    $utilizador = $_GET['utilizador'];
    $justific = $_GET['justfc'];
    Reserva_conjunta ($dados, $agendador, $utilizador, $justific);
    // se der reserva disponibilidade 
    // joga pra disponibilidade
    header('Location: cReservas.php');
    exit();
    
}else{
    $dados = $_GET['marcas'];
    $html = file_get_contents('View/vReservaConjunta.php');
    $usuarios =  listar_usuarios();
    $usuarios = optios($usuarios);
    $hidem_dados = dados_hidem($dados);
    $tabe_htm = tabe_html($dados);
    $html = str_replace('{{dados}}', $hidem_dados, $html);
    $html = str_replace('{{reservas}}', $tabe_htm, $html);
    $html = str_replace('{{agendador}}', $usuarios, $html);
    $html = str_replace('{{usuario}}', $usuarios, $html);
    echo $html;
}
?>

