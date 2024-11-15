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
    
        $resultado = inserir_reserva($justificativa, $usuario_agendador, $recurso, $usuario_utilizador, $data, $hora_inicial, $hora_final);
        
        if($resultado == 5){
            $justificativa = '';
            $recurso = '';
            $usuario_utilizador = '';
            $usuario_agendador = '';
            $data = [];
            $hora_inicial=[];
            $hora_final = [];

        }

        $men =[ 'Justificativa é obrigatória!', 
                'Justificativa não pode ultrapassar 100 caracteres.', 
                'Data e hora são obrigatórios!',
                'Data não pode ser no passado!',
                'Hora inicial deve ser antes da hora final!',
                'Reserva Cadastrada com sucesso'];
        $mensagem = $men[$resultado];

    }

    $data_reserva ='';
    if(!isset($_GET['lista_datas'])){
        $ldatas = '<input type ="hidden" name="listar_data[]" value="0">';
    }else{
        $lista_datas = $_GET['lista_data'];

        if (isset($_GET['btnAdicionar'])) {
            $nova_data = $_GET['data'];
            $hora_inicial = $_GET['hora_inicial'];
            $hora_final = $_GET['hora_final'];

            $datas = $data;
            $hr_in = $hora_inicial;
            $hr_fim = $hora_final;
        
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
    
    // Carregar o HTML do formulário e substituir os campos
    $html = str_replace('{{Campojustificativa}}', $justificativa, $html);
    $html = str_replace('{{Recursos}}', $sel_recursos, $html);
    $html = str_replace('{{Usuarios}}', $sel_usuarios, $html);
    $html = str_replace('{{Datas Reservas}}', $tabela_datas, $html);
    $html = str_replace('{{mensagem}}', $mensagem, $html);
    
    echo $html;
?>