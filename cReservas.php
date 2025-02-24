<?php

function carregar_tabela_reserva(){

    $daddos = carregar_reserva();
    $tabela_html = '';
    foreach($daddos as $dados){
       $data_formatada = DateTime::createFromFormat('Y-m-d', $dados["data"])->format('d/m/Y');
       $tabela_html.='<tr> <td>'.$dados['recurso'].'</td>';
       $tabela_html.='<td>'.$dados['utilizador'].'</td>';
       $tabela_html.='<td>'.$data_formatada.' das '.$dados['h_ini'].' ás '.$dados['h_fim'].'</td>';
       $tabela_html.= '<td>
            <form action="cReservas.php">
                <input type="hidden" name="codigo_da_reserva" value="'.$dados['codigo']. '">
                <input type="submit" name="apagar" value="Apagar">
            </form>
        </td> </tr>';
       
    }
    
    return $tabela_html;
}



include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'list_reserva', 2);
include_once 'Model/mReserva.php';

$id = 'nada';
$msg = '';
if (isset($_GET['codigo_da_reserva'])) {
    $cod_reserva = $_GET['codigo_da_reserva']; 
    $msg = apagar_reserva($cod_reserva);
    $id = ($msg)?'sucesso':'erro';
    $msg = ($msg)?'Reseva apagada com sucesso':'Essa reserva não pode ser apagada por esta sendo referenciada no ensalamento.';

}

$conteudo_reservas = carregar_tabela_reserva();


// Carregar o HTML do arquivo de visualização
$html = file_get_contents('View/vReservas.php');
$html = str_replace('{{Reservas}}', $conteudo_reservas, $html);
$html = str_replace('{{mensagem}}', $msg, $html);
$html = str_replace('{{retorno}}', $id, $html);
echo $html;
?>
