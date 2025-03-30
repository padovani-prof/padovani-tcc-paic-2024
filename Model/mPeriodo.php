<?php

function apagar_periodo($chave_pri)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Verifica se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return false;
    }

    $stmt = $conexao->prepare("DELETE FROM periodo WHERE codigo = ?");
    $stmt->bind_param("i", $chave_pri);
    $stmt->execute();

    // Fechar o statement e a conexão
    $stmt->close();
    $conexao->close();

    return true;
}

function carrega_periodo()
{
    include 'confg_banco.php';
    $cone = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi bem-sucedida
    if ($cone->connect_error) {
        return [];
    }

    $resultado = $cone->query('SELECT * FROM periodo');
    $dados = $resultado->fetch_all(MYSQLI_ASSOC);
    
    // Fechar a conexão
    $cone->close();

    return $dados;
}

function insere_periodo($nome, $data_ini, $data_final)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return 1;
    }

    $stmt = $conexao->prepare("SELECT * FROM periodo WHERE nome = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        $stmt = $conexao->prepare("INSERT INTO periodo (nome, dt_inicial, dt_final) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $data_ini, $data_final);
        $stmt->execute();
        
        // Fechar statement e conexão
        $stmt->close();
        $conexao->close();

        return 0; // salvo
    } else {
        // Fechar statement e conexão
        $stmt->close();
        $conexao->close();

        return 1; // nome repetido
    }
}

function mandar_dados($chave)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return [];
    }

    $stmt = $conexao->prepare("SELECT * FROM periodo WHERE codigo = ?");
    $stmt->bind_param("i", $chave);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $dados = $resultado->fetch_assoc();

    // Fechar statement e conexão
    $stmt->close();
    $conexao->close();

    return $dados;
}

function verificar_periodo($codigo, $dataine, $data_fim)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return false;
    }

    $stmt = $conexao->prepare("SELECT * FROM periodo WHERE codigo = ? AND dt_inicial = ? AND dt_final = ?");
    $stmt->bind_param("iss", $codigo, $dataine, $data_fim);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Fechar statement e conexão
    $stmt->close();
    $conexao->close();

    return $resultado->num_rows == 1;
}

function atualizar_periodo($chave, $nome, $dataIn, $dataFim)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return 1;
    }

    $stmt = $conexao->prepare("SELECT * FROM periodo WHERE nome = ? AND codigo != ?");
    $stmt->bind_param("si", $nome, $chave);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        $stmt = $conexao->prepare("UPDATE periodo SET nome = ?, dt_inicial = ?, dt_final = ? WHERE codigo = ?");
        $stmt->bind_param("sssi", $nome, $dataIn, $dataFim, $chave);
        $stmt->execute();

        // Fechar statement e conexão
        $stmt->close();
        $conexao->close();

        return 0; // salvo
    } else {
        // Fechar statement e conexão
        $stmt->close();
        $conexao->close();

        return 1; // nome repetido
    }
}

function Existe_esse_periodo($chave)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return false;
    }

    $stmt = $conexao->prepare("SELECT * FROM periodo WHERE codigo = ?");
    $stmt->bind_param("i", $chave);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Fechar statement e conexão
    $stmt->close();
    $conexao->close();

    return $resultado->num_rows > 0;
}

function Existe_essa_chave_na_tabela($chave, $tabela, $jogar_pra_onde)
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return;
    }

    $stmt = $conexao->prepare("SELECT * FROM $tabela WHERE codigo = ?");
    $stmt->bind_param("i", $chave);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Fechar statement e conexão
    $stmt->close();
    $conexao->close();

    if ($resultado->num_rows == 0) {
        header("Location: $jogar_pra_onde");
        exit();
    }
}
?>
