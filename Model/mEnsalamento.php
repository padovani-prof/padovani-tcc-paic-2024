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
   
} //mais ou menos 

function filtrar()
{
    // Inclui o arquivo de configuração do banco
    include 'confg_banco.php';

    // Cria a conexão com o banco de dados
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se houve erro na conexão
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Executa a consulta SQL
    $resultado = $conexao->query("SELECT 
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
		sgrp.periodo ON periodo.codigo = disciplina.codigo_periodo	
		");

    // Verifica se há resultados e retorna um array
    if ($resultado->num_rows > 0) {
        $dados = [];
        while ($linha = $resultado->fetch_assoc()) {
            $dados[] = $linha;
        }
        return $dados; // Retorna os dados encontrados
    } else {
        return []; // Retorna um array vazio se não houver resultados
    }
}// faltar analisar


function ensalamento($periodo, $disciplina, $sala, $dia_semana, $h_ini, $h_fim) 
{
    include 'confg_banco.php'; // Inclui a configuração do banco
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco); // Cria a conexão

    // Verifica se a conexão foi bem-sucedida
    if ($conecxao->connect_error) {
        die("Erro de conexão: " . $conecxao->connect_error);
    }

    // Consulta para buscar o código da disciplina
    $consulta_disciplina = $conecxao->query("SELECT * FROM disciplina WHERE codigo = '$disciplina' AND codigo_periodo = '$periodo'");

    // Verifica se a consulta foi bem-sucedida
    if ($consulta_disciplina === false) {
        die("Erro na consulta: " . $conecxao->error);
    }

    // Verifica se a disciplina foi encontrada
    if ($consulta_disciplina->num_rows > 0) {

        // Obtém os dados da disciplina
        $linha = $consulta_disciplina->fetch_assoc();
        $codigo_disciplina = $linha['codigo'];

        // Insere os dados no banco de dados
        $resultado = $conecxao->query("INSERT INTO ensalamento (dias_semana, hora_inicial, hora_final, codigo_disciplina, codigo_sala) 
        VALUES 
        ('$dia_semana','$h_ini','$h_fim','$codigo_disciplina','$sala')");


        // Verifica se o `INSERT` foi bem-sucedido
        if ($resultado) {
            return 0; //para sucesso
        } else {
            die("Erro ao inserir dados: " . $conecxao->error);
        }
    } else {
        return 1; // Disciplina não encontrada
    }
}// por enquanto ok

function cod_ensalamento ($disciplina, $sala, $dia_semana, $h_ini, $h_fim, $usuario_agendador, $justificativa)
{   
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    $cod_reserva = '';

    $cod_ensalamento = $conecxao->query("SELECT codigo
    FROM ensalamento 
    WHERE 
    dias_semana = '$dia_semana' AND hora_inicial = '$h_ini' AND hora_final = '$h_fim' AND codigo_disciplina = $disciplina AND codigo_sala = $sala");

    if ($cod_ensalamento->num_rows > 0) {

        $row = $cod_ensalamento->fetch_assoc();
        $codigo_ensalamento = $row['codigo'];

        // continuar o processo de incerção de informação

        $reserva = $conecxao->query("INSERT INTO reserva (justificativa, codigo_usuario_agendador, codigo_recurso, codigo_usuario_utilizador) 
        VAlUES ('$justificativa',$usuario_agendador, $sala, $usuario_agendador)");

        if ($reserva === true) {

            $cod_reserva = $conecxao->query("SELECT codigo FROM reserva 
            WHERE codigo_usuario_agendador = $usuario_agendador AND codigo_recurso = $sala AND codigo_usuario_utilizador = $usuario_agendador");

            if ($cod_reserva->num_rows > 0) {

                $row = $cod_reserva->fetch_assoc();
                $codigo_reserva = $row['codigo'];
            }

            $reserva_ensalamento = $conecxao->query("INSERT INTO reserva_ensalamento (codigo_reserva, codigo_ensalamento) 
            VALUES ($codigo_reserva, $codigo_ensalamento)");

        }

        return $reserva_ensalamento;
        
    } else {

        return 3;

    }
}// ta quase mano
        

function gerarOpcoes($lista, $selecionado) {
    $opcoes = '';
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

function dias_aulas($periodo, $dias_da_semana) {

    include 'Model/confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    $consulta = $conecxao->query("SELECT dt_inicial as dat_ini, dt_final as dat_fin FROM periodo WHERE codigo = '$periodo' ");

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
        
}