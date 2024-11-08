<?php
include_once 'Model/mReserva.php';

$reservas = listar_reserva();
$conteudo_reservas = '<tbody>';

$men = '';

foreach ($reservas as $reserva) {
    $datas_reserva = listar_datas($reserva["codigo_reserva"]); 

    $datas_horarios = '';
    foreach ($datas_reserva as $data) {
        $datas_horarios .= $data["data"] . ' (' . $data["hora_inicial"] . '-' . $data["hora_final"] . ')<br>';
    }

    $conteudo_reservas .= '<tr>
        <td>' . htmlspecialchars($reserva["recurso"]) . '</td>
        <td>' . htmlspecialchars($reserva["usuario"]) . '</td>
        <td>' . $datas_horarios . '</td>
        <td>
            <form method="post" action="cReserva.php">
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
