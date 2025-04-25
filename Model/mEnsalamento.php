<?php

function carregar_salas()
{   
    // função que retorna uma lista com todos os dados dos recursos

    // conecxao com o banco
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Executar a consulta
    $resultado = $conecxao->query("SELECT * FROM recurso WHERE codigo_categoria IN (SELECT codigo FROM categoria_recurso WHERE ambiente_fisico = 'S');");
    //$resultado = $conecxao->query("SELECT * FROM categoria_recurso WHERE ambiente_fisico = 'S' ");

    //$todos_dados = $resultado->fetch_all(MYSQLI_ASSOC);
	
	$todos_dados = [];
    while ($linha = $resultado->fetch_assoc())
    {
        $todos_dados[] = $linha;
    }
    return $todos_dados; // retorna todos os daddos da tabela recurso do banco em formato de lista
   
} //ok

//=================================================================================================================================================
//=================================================================================================================================================

function filtrar($periodo=null, $disciplina=null, $sala=null)
{
    // Inclui o arquivo de configuração do banco
    include 'confg_banco.php';
    // Cria a conexão com o banco de dados 
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se houve erro na conexão
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $query = "SELECT 
    ensalamento.codigo,
    ensalamento.dias_semana,
    ensalamento.hora_inicial,
    ensalamento.hora_final,
    disciplina.nome AS nome_disciplina,
    recurso.nome AS nome_recurso,
    periodo.nome AS nome_periodo
    FROM 
        ensalamento
    INNER JOIN 
        disciplina ON ensalamento.codigo_disciplina = disciplina.codigo
    INNER JOIN 
        recurso ON ensalamento.codigo_sala = recurso.codigo
    INNER JOIN
        sgrp.periodo ON sgrp.periodo.codigo = disciplina.codigo_periodo	
    WHERE 
        1=1 ";
    
    //caso 1 ok
    if ($periodo == null && $disciplina == null && $sala == null){
        $query .=" ";
    }
    //caso 2 ok
    elseif ($periodo != null && $disciplina == null && $sala == null){
        $query .= "AND ensalamento.codigo_disciplina = disciplina.codigo 
        AND disciplina.codigo_periodo = '$periodo'";
    }
    //caso 3 ok 
    elseif ($periodo == null && $disciplina != null && $sala == null){
        $query .= "AND ensalamento.codigo_disciplina = '$disciplina'";
    }
    //caso 4 ok 
    elseif($periodo == null && $disciplina == null && $sala != null){
        $query .= "AND ensalamento.codigo_sala = '$sala' ";
    }
    //caso 5 ok 
    elseif($periodo != null && $disciplina != null && $sala == null){
        $query .= "AND ensalamento.codigo_disciplina = disciplina.codigo 
        AND disciplina.codigo_periodo = '$periodo' 
        AND ensalamento.codigo_disciplina = '$disciplina'";
    }
    //caso 6 ok
    elseif($periodo == null && $disciplina != null && $sala != null){
        $query .= "AND ensalamento.codigo_disciplina = '$disciplina' 
        AND ensalamento.codigo_sala = '$sala'";
    }
    //caso 7 ok 
    elseif($periodo != null && $disciplina == null && $sala != null){
        $query .= "AND ensalamento.codigo_disciplina = disciplina.codigo 
        AND disciplina.codigo_periodo = '$periodo' 
        AND ensalamento.codigo_sala = '$sala'";
    }
    //caso 8
    elseif($periodo != null && $disciplina != null && $sala != null){
        $query .= "AND ensalamento.codigo_disciplina = disciplina.codigo 
        AND disciplina.codigo_periodo = '$periodo'
        AND ensalamento.codigo_disciplina = '$disciplina' 
        AND ensalamento.codigo_sala = '$sala'";
    } 
    else {
        return "erro de consultas";
    }
   
    $resultado = $conexao->query($query);

    if ($resultado->num_rows > 0) {
        $dados = [];
        while ($linha = $resultado->fetch_assoc()) {
            $dados[] = $linha;
        }
        return $dados; // Retorna os dados encontrados
    } else {
        return []; // Retorna um array vazio se não houver resultados
    }
}

//==========================================================================================================================================
//==========================================================================================================================================

function ensalamento($disciplina, $sala, $dia_semana, $h_ini, $h_fim) 
{
    include 'confg_banco.php'; // Inclui a configuração do banco
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco); // Cria a conexão

    // Verifica se a conexão foi bem-sucedida
    if ($conecxao->connect_error) {
        die("Erro de conexão: " . $conecxao->connect_error);
    }

    // Insere os dados no banco de dados
    $resultado = $conecxao->query("INSERT INTO ensalamento (dias_semana, hora_inicial, hora_final, codigo_disciplina, codigo_sala) 
    VALUES 
    ('$dia_semana','$h_ini','$h_fim','$disciplina','$sala')");


    // Verifica se o `INSERT` foi bem-sucedido
    if ($resultado) 
    {
        return 0; //para sucesso
    } else {
        die("Erro ao inserir dados: " . $conecxao->error);
    }

}// por enquanto ok

//==========================================================================================================================================
//==========================================================================================================================================

function cod_ensalamento($disciplina, $sala, $dia_semana, $h_ini, $h_fim, $usuario_agendador, $justificativa, $dts_aula)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verificar conexão
    if ($conecxao->connect_error) {
        die("Erro de conexão: " . $conecxao->connect_error);
    }

    // Verifica se o ensalamento existe
    $consulta_ensalamento = "SELECT codigo 
    FROM ensalamento 
    WHERE 
    dias_semana = '$dia_semana' 
    AND hora_inicial = '$h_ini' 
    AND hora_final = '$h_fim' 
    AND codigo_disciplina = $disciplina 
    AND codigo_sala = $sala";

    $result = $conecxao->query($consulta_ensalamento);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $codigo_ensalamento = $row['codigo'];

        // Inserir reserva
        $inserir_reserva = "INSERT INTO reserva (justificativa, codigo_usuario_agendador, codigo_recurso, codigo_usuario_utilizador) 
        VALUES ('$justificativa', $usuario_agendador, $sala, $usuario_agendador)";

        if ($conecxao->query($inserir_reserva) === TRUE) {
            $cod_reserva = $conecxao->insert_id;

            // Inserir reserva_ensalamento
            $inserir_reserva_ensalamento = "INSERT INTO reserva_ensalamento (codigo_reserva, codigo_ensalamento) 
            VALUES ($cod_reserva, $codigo_ensalamento)";


            if ($conecxao->query($inserir_reserva_ensalamento) === TRUE) {


                // Inserir datas da reserva
                foreach ($dts_aula as $dt_dia) {

                    $inserir_data_reserva = "INSERT INTO data_reserva (data, hora_inicial, hora_final, codigo_reserva) 
                    VALUES ('$dt_dia', '$h_ini', '$h_fim', $cod_reserva)";

                    $conecxao->query($inserir_data_reserva);

                }
                return 6; // Sucesso
            }
        }
    } else {
        return 3; // Ensalamento inexistente
    }

    $conecxao->close();
    return 0; // Falha geral
}

//==========================================================================================================================================
//==========================================================================================================================================

function gerarOpcoes($lista, $selecionado) {
    $opcoes = '<option value="">...</option>'; 
    foreach ($lista as $item) {
        $opcoes .= sprintf(
            '<option value="%s"%s>%s</option>',
            htmlspecialchars($item['codigo'], ENT_QUOTES, 'UTF-8'),
            $item['codigo'] == $selecionado ? ' selected' : '',
            htmlspecialchars($item['nome'], ENT_QUOTES, 'UTF-8')
        );
    }
    return $opcoes;
}//ok


function gerarOpcoesDisciplina($lista, $selecionado) {
    $opcoes = '<option value="">...</option>'; 
    foreach ($lista as $item) {
        $opcoes .= sprintf(
<<<<<<< HEAD
            '<option title=" Curso: '.$item['curso'].'" value="%s"%s>%s</option>',
=======
            '<option title="Curso: '.$item['curso'].'" value="%s"%s>%s</option>',
>>>>>>> 6cbd2092142413db457028132ec145333a730ab7
            htmlspecialchars($item['codigo'], ENT_QUOTES, 'UTF-8'),
            $item['codigo'] == $selecionado ? ' selected' : '',
            htmlspecialchars($item['nome'], ENT_QUOTES, 'UTF-8')
        );
    }
    return $opcoes;
}

//==========================================================================================================================================
//==========================================================================================================================================

function gerarDiasDaSemana($texto_dias){
    $nomes_dias = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"];
    $dias_selecionados = [];
    for ($i = 0; $i < 7; $i++){
        if ($texto_dias[$i] == "S"){
            $dias_selecionados[] = $nomes_dias[$i];
        }
    }
    $dias_selecionados = implode (", ", $dias_selecionados);
    return $dias_selecionados;
}//ok

//==========================================================================================================================================
//==========================================================================================================================================

function dias_aulas($disciplina, $dias_da_semana) {

    include 'Model/confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    $consulta = $conecxao->query("SELECT dt_inicial as dat_ini, dt_final as dat_fin 
    FROM sgrp.periodo AS peri 
    WHERE peri.codigo = (SELECT codigo_periodo FROM sgrp.disciplina WHERE codigo=$disciplina);");

    $todos_dados = [];

    if ($consulta->num_rows > 0){
        while ($linha = $consulta->fetch_assoc())
        {
            $todos_dados[] = $linha;
        }
    }

    $dt_ini = $todos_dados[0]['dat_ini']; //OK
    $dt_fin = $todos_dados[0]['dat_fin']; //OK
    $dias_de_aula = [];
    $dias = [];//OK
    
    for ($i = 0; $i < strlen($dias_da_semana); $i++) {
        if ($dias_da_semana[$i]=== 'S'){
            $dias[]= $i+1;
        }
    }
    
    while (strtotime($dt_ini) <= strtotime($dt_fin)){
        $dia_iso = date('N', strtotime($dt_ini)); // Dia da semana no formato ISO (1 = segunda-feira, 7 = domingo)
        $diasemana = ($dia_iso == 7) ? 1 : $dia_iso + 1; // Converte para o sistema personalizado (1=domingo, 7=sábado)
        
        if (in_array($diasemana, $dias)) { // Verifica se o dia está nos dias de aula
            $dias_de_aula[] = $dt_ini; // Adiciona a data ao array de dias de aula
        }
        
        $dt_ini = date('Y-m-d', strtotime($dt_ini . "+1 day")); // Incrementa os dias
    }
    return $dias_de_aula;
        
}//ok

function cosultas($disciplina, $sala, $dia_semana, $hora_ini, $hora_fin, $datas){

    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $consulta = $conecxao->query("SELECT*FROM sgrp.ensalamento WHERE dias_semana = '$dia_semana' 
    AND hora_inicial = '$hora_ini'
    AND hora_final = '$hora_fin'
    AND codigo_disciplina = '$disciplina'
    AND codigo_sala = '$sala'");

    // var_dump($consulta);

    if ($consulta->num_rows > 0) {
        // echo 'error 1';
        return null;
    }
    else {
        $consulta = $conecxao->query("SELECT codigo FROM sgrp.reserva 
        WHERE codigo_recurso = '$sala'");

        if ($consulta->num_rows > 0) {
            $dados = [];
            while ($linha = $consulta->fetch_assoc()) {
                $dados[] = $linha;
            }

            // var_dump($dados);
            // echo '<br>';
            
            // fazer o for alinhado para realizar as consultas
            $resultado = [];
            
            foreach ($dados as $reserva)
            {   
                // echo '1';
                // echo '<br>';
                foreach ($datas as $dt)
                {   
                    //echo '2';
                    //echo '<br>';

                    $cod_reserva = $reserva['codigo'];
                    // var_dump($cod_reserva);
                    // echo '<br>';

                    // var_dump($dt);
                    // echo '<br>';

                    // var_dump($hora_ini, $hora_fin);
                    // echo '<br>';

                    $consulta = $conecxao->query("SELECT codigo FROM sgrp.data_reserva 
                    WHERE
                    data = '$dt' 
                    AND ((hora_inicial < '$hora_fin' AND hora_final > '$hora_ini'))
                    AND codigo_reserva =  '$cod_reserva' ");

                    // var_dump($consulta);
                    // echo '<br>';

                    if ($consulta->num_rows > 0) {
                        while ($linha = $consulta->fetch_assoc()) {
                            // Salve os dados no array
                            $resultado[] = $linha;
                            
                            // echo '3';
                            // var_dump($resultado);
                            // echo '<br>';
                        }
                    } else {
                        $resultado = null;
                    }                    
                }
            }
            // ver uma forma de salvar informação 

            if($resultado !== null ){
                // echo 'error 2';
                return null;
            }
            else {
                // echo'true';
                return 'permitir o ensalamento';
            }
        }
        else {
            return "Permitir o ensalamento";
        }
    }
}

function apagar($cod_ensalamento)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $consulta = $conecxao->query("SELECT codigo_reserva FROM sgrp.reserva_ensalamento
    WHERE codigo_ensalamento = '$cod_ensalamento' ");

    $dados = '';

    if ($consulta->num_rows > 0) 
    {
        $dados = [];
        while ($linha = $consulta->fetch_assoc()) 
        {
            $dados[] = $linha;
        }
    }

    if ($dados != null){

        $cod_reserva = $dados['0']['codigo_reserva'];

        // var_dump($cod_reserva);

        $apagar = $conecxao->query("DELETE FROM sgrp.reserva_ensalamento
        WHERE  codigo_ensalamento = '$cod_ensalamento' and codigo_reserva = '$cod_reserva'");

        // var_dump($apagar);
        // echo '<br>';

        $apagar = $conecxao->query("DELETE FROM sgrp.data_reserva WHERE codigo_reserva = '$cod_reserva' ");

        // var_dump($apagar);
        // echo '<br>';

        $apagar = $conecxao->query("DELETE FROM sgrp.reserva WHERE codigo = '$cod_reserva' ");

        // var_dump($apagar);
        // echo '<br>';
        
        $apagar = $conecxao->query("DELETE FROM sgrp.ensalamento WHERE codigo = '$cod_ensalamento'");

        // var_dump($apagar);
        // echo '<br>';

    }
}// fazer
