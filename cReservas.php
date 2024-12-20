<?php

session_start();
if(!isset($_SESSION['codigo_usuario']))
{   
    // Se o usuario não fez login jogue ele para logar
    header('Location: cLogin.php?msg=Usuario desconectado!');
    exit();
}

include_once 'Model/mVerificacao_acesso.php';
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'list_reserva');
if ($verificar == false)
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}

include_once 'Model/mReserva.php';

if (isset($_GET['codigo_da_reserva'])) {
    $cod_reserva = $_GET['codigo_da_reserva']; 
    apagar_reserva($cod_reserva);
}

$reservas = listar_reserva();
$conteudo_reservas = '<tbody>';

$men = ''; 

foreach ($reservas as $reserva) {
    $datas_reserva = listar_datas($reserva["codigo_reserva"]); 

    $datas_horarios = '';
    foreach ($datas_reserva as $data) {
        // Formatando a data para "DD/MM/AAAA"
        $data_formatada = DateTime::createFromFormat('Y-m-d', $data["data"])->format('d/m/Y');
        
        // Montando o texto formatado
        $datas_horarios .= $data_formatada . ' das ' . $data["hora_inicial"] . ' às ' . $data["hora_final"] . '<br>';
    }

    $conteudo_reservas .= '<tr>
        <td>' . ($reserva["recurso"]) . '</td>
        <td>' . ($reserva["usuario"]) . '</td>
        <td>' . $datas_horarios . '</td>
        <td>
            <form action="cReservas.php" method:"get">
                <input type="hidden" name="codigo_da_reserva" value="' . $reserva["codigo_reserva"] . '">
                <input type="submit" name="apagar" value="Apagar">
            </form>
        </td>
    </tr>';
}

$conteudo_reservas .= '</tbody>';

// Carregar o HTML do arquivo de visualização
$html = file_get_contents('View/vReservas.php');
$html = str_replace('{{Reservas}}', $conteudo_reservas, $html);
$html = str_replace('{{mensagem}}', $men, $html);
echo $html;
?>
