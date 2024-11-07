<?php
include_once 'Model/mReserva.php';

$reservas = listar_reserva();
$reservas = listar_datas();

$conteudo_reservas = '<tbody>';
foreach ($reservas as $reserva) {
    $conteudo_reservas .= '<tr>
        <td>' . ($reserva["recurso"]) . '</td>
        <td>' . ($reserva["usuario"]) . '</td>
        <td>' . ($reserva["data"]) . '</td>
        <td>
            <form>
                <input type="hidden" name="codigo_da_reserva" value="' . $reserva["reserva_id"] . '">
                <input type="submit" name="apagar" value="Apagar">
            </form>
        </td>
    </tr>';
}
$conteudo_reservas .= '</tbody>';

// Carregar o HTML do arquivo de visualização
$html = file_get_contents('View/vReserva.php');
$html = str_replace('{{Reservas}}', $conteudo_reservas, $html);
echo $html;
?>
?>