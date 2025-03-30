<?php 

function carrega_recurso($codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Usando prepared statement para evitar SQL Injection
    $stmt = $conecxao->prepare("SELECT nome FROM recurso WHERE codigo = ?");
    $stmt->bind_param("i", $codigo);  // "i" para inteiro
    $stmt->execute();
    $resultado = $stmt->get_result();
    $conecxao->close();

    // Retorna o nome do recurso
    return $resultado->fetch_assoc()['nome'];
}

function carrega_dados($codigo) {
    // Incluir a configuração do banco de dados
    include 'confg_banco.php';

    // Conectar ao banco de dados com a classe mysqli
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Verificar se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        die('Erro de conexão: ' . $conexao->connect_error);
    }

    // Preparar a consulta para evitar SQL injection
    $stmt = $conexao->prepare("SELECT * FROM checklist WHERE codigo_recurso = ?");
    
    // Verificar se a preparação da consulta foi bem-sucedida
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conexao->error);
    }

    // Vincular o parâmetro para o prepared statement (assumindo que $codigo seja um número inteiro)
    $stmt->bind_param("i", $codigo);
    
    // Executar a consulta
    $stmt->execute();
    
    // Obter o resultado da consulta
    $resultado = $stmt->get_result();

    // Usar fetch_all para obter todos os dados de uma vez e armazenar em um array associativo
    $dados = $resultado->fetch_all(MYSQLI_ASSOC);

    // Fechar o statement e a conexão
    $stmt->close();
    $conexao->close();
    
    // Retornar os dados
    return $dados;
}

function salva_no_banco($item, $codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Usando prepared statement para evitar SQL Injection
    $stmt = $conecxao->prepare("INSERT INTO checklist (item, codigo_recurso) VALUES (?, ?)");
    $stmt->bind_param("si", $item, $codigo);  // "s" para string, "i" para inteiro
    $stmt->execute();
    $conecxao->close();
}

function apagar_acesso_ao_recurso($chave_pri)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Usando prepared statement para evitar SQL Injection
    $stmt = $conecxao->prepare("DELETE FROM checklist WHERE codigo = ?");
    $stmt->bind_param("i", $chave_pri);  // "i" para inteiro
    $stmt->execute();
    $conecxao->close();
}

function Existe_essa_chave_na_tabela($chave, $tabela, $jogar_pra_onde){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Usando prepared statement para evitar SQL Injection
    $stmt = $conecxao->prepare("SELECT * FROM $tabela WHERE codigo = ?");
    $stmt->bind_param("i", $chave);  // "i" para inteiro
    $stmt->execute();
    $resulata = $stmt->get_result();
    $conecxao->close();

    if($resulata->num_rows == 0){
        header("Location: $jogar_pra_onde");
        exit();
    }
}

?>
