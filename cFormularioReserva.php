<?php
    session_start();
    include_once 'Model/mReserva.php'; 

    $html = file_get_contents('View/vFormularioReserva.php');

    $recursos = carregar_recurso();
    $usuarios = carregar_usuario();
    
    $justificativa = '';
    $mensagem = '';
    $recurso = '';
    $usuario_utilizador = '';
    $tabela_datas = '';

    if (isset($_GET['btnSalvar'])) {
        $justificativa = $_GET['justificativa'];
        $recurso = $_GET['recurso'];
        $usuario_utilizador = $_GET['usuario_utilizador'];
        $usuario_agendador = $_SESSION['usuario_agendador'];
    
        $resultado = inserir_reserva($justificativa, $usuario_agendador, $recurso, $usuario_utilizador, $datas_reservas);
        
        if($resultado == 5){
            $justificativa = '';
            $recurso = '';
            $usuario_utilizador = '';
            $usuario_agendador = '';

        }

        $men =[ 'Justificativa é obrigatória!', 
                'Justificativa não pode ultrapassar 100 caracteres.', 
                'Data e hora são obrigatórios!',
                'Data não pode ser no passado!',
                'Hora inicial deve ser antes da hora final!',
                'Reserva Cadastrada com sucesso'];
        $mensagem = $men[$resultado];

    }

    
    if (isset($_GET['btnAdicionar'])) {
        $nova_data = $_GET['data'];
        $hora_inicial = $_GET['hora_inicial'];
        $hora_final = $_GET['hora_final'];

        $datas_reservas = $_GET['datas_reservas'];
    
        if (empty($nova_data) || empty($hora_inicial) || empty($hora_final)) {
            $mensagem = "Data e horário são obrigatórios!";
        } else {
            if($datas_reservas[0] == 0){
                $datas_reservas[0] = $nova_data;
            } else {
                $datas_reservas[] = $nova_data;
            }
            $datas_reservas[] = $hora_inicial;
            $datas_reservas[] = $hora_final;

            
        }
    }

    if(!isset($_GET['datas_reservas'])){
        $dt = '<input type="hidden" name="datas_reservas[]" value="0">';
        $html = str_replace("{{Lista Data}}",$dt,$html);
    } else{
        var_dump($datas_reservas);
        $lista = '';
        if(count($datas_reservas) > 1){
            for ($i=0; $i<count($datas_reservas); $i=$i+3) {
                    $lista .= '<input type="hidden" name="datas_reservas[]" value="'. $datas_reservas[$i] .'"';
                    $lista .= '<input type="hidden" name="datas_reservas[]" value="'. $datas_reservas[$i+1] .'"';
                    $lista .= '<input type="hidden" name="datas_reservas[]" value="'. $datas_reservas[$i+2] .'"';
                    
            }
        }

        $html = str_replace("{{Lista Data}}",$lista,$html); 
        $tabela_datas = '';
            for ($i=0; $i<count($datas_reservas); $i=$i+3) { 
                if($datas_reservas[$i] != 0) {
                    $tabela_datas .= '<tr>';
                    $tabela_datas .= "<td>" . $datas_reservas[$i] . " - " . $datas_reservas[$i+1] . " a " . $datas_reservas[$i+2] . "</td>";
                
                    $tabela_datas .='<td> 
                                <input type="submit" name="apagar" value="Remover">
                        </td>';
                }
            }       
    }


    
    if (isset($_GET['apagar'])) {
        $codigo_data = $_GET['codigo_data'];
        apagar_data($codigo_data); 
    }
    
    // Gerar os selects de recursos e usuários
    $sel_recursos = '';
    foreach ($recursos as $rec) {
        $sel_recursos .= '<option value="'.$rec['codigo'].'"'.($rec['codigo']== $recurso ? 'selected':'').'>'.$rec['nome'].'</option>';
    }
    
    $sel_usuarios = '';
    foreach ($usuarios as $us) {
        $sel_usuarios .= '<option value="'.$us['codigo'].'"'.($us['codigo']== $usuario_utilizador ? 'selected':'').'>'.$us['nome'].'</option>';
    }
    
    // Gerar a lista de datas e horários adicionados
    
    
    // Carregar o HTML do formulário e substituir os campos
    $html = str_replace('{{Campojustificativa}}', $justificativa, $html);
    $html = str_replace('{{Recursos}}', $sel_recursos, $html);
    $html = str_replace('{{Usuarios}}', $sel_usuarios, $html);
    $html = str_replace('{{Datas Reservas}}', $tabela_datas, $html);
    $html = str_replace('{{mensagem}}', $mensagem, $html);
    
    echo $html;
?>