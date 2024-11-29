<?php

function carregar_salas()
{   
    // função que retorna uma lista com todos os dados dos recursos

    // conecxao com o banco
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Executar a consulta
    $resultado = $conecxao->query("SELECT * FROM recurso WHERE codigo_categoria IN (2, 3)");
    //$resultado = $conecxao->query("SELECT * FROM categoria_recurso WHERE ambiente_fisico = 'S' ");

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
    recurso.nome AS nome_recurso
    FROM 
        ensalamento
    INNER JOIN 
        disciplina ON ensalamento.codigo_disciplina = disciplina.codigo
    INNER JOIN 
        recurso ON ensalamento.codigo_sala = recurso.codigo");

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
        $resultado = $conecxao->query("INSERT INTO ensalamento (dias_semana, hora_inicial, hora_final, codigo_disciplina, codigo_sala) VALUES ('$dia_semana','$h_ini','$h_fim','$codigo_disciplina','$sala')");

        // Verifica se o `INSERT` foi bem-sucedido
        if ($resultado) {
            return 0; // Sucesso
        } else {
            die("Erro ao inserir dados: " . $conecxao->error);
        }
    } else {
        return 1; // Disciplina não encontrada
    }
}// por enquanto ok



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


function dias_mês($periodo){

    include 'Model/confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    $consulta = $conecxao->query("SELECT dt_inicial, dt_final FROM periodo WHERE codigo = '$periodo' ");

    $todos_dados = [];

    if ($consulta->num_rows > 0){
        while ($linha = $consulta->fetch_assoc())
        {
            $todos_dados[] = $linha;
        }

        $dt_ini = $todos_dados['dt_inicial'];
        $dt_fin = $todos_dados['dt_final'];

        // $dt_ini = '2024-12-01';
       
        // Obter o dia da semana no formato ISO-8601 (1 = segunda-feira, 7 = domingo)
        $dia_iso = date('N', strtotime($dt_ini));

        // Ajustar para 1 = domingo e 7 = sábado
        $dia_da_semana_custom = ($dia_iso == 7) ? 1 : $dia_iso + 1;

        $dias_da_semana = [ '1' => 'don', '2'=>'seg', '3'=>'ter', '4'=>'qua', '5'=>'qui', '6'=>'sex', '7'=>'sab'];
        
        
        
        
        
        return $dia_da_semana_custom;

    
    }else{

        return 'erro!!';
        //return coloque alguma mensagem de erro;
    }

}