<?php 
function listar_usuarios(){   
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT * FROM usuario");
    $todos_dados = [];

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $todos_dados[] = $linha;
        }
    }

    $conexao->close();

    return $todos_dados;
}

function listar_perfil(){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $resultado = $conexao->query("SELECT * FROM perfil_usuario ORDER BY nome ASC");

    $todos_dados = [];

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $todos_dados[] = $linha;
        }
    }

    $conexao->close();

    return $todos_dados;

}

function Validar_usuario($nome, $senha)
{
    if (strlen($nome) < 2 || strlen($nome) > 50) {
        return 0; // Nome inválido
    }

    if (empty($senha)) {
        return 1; // Senha vazia
    }

    if (strlen($senha) > 50 || strlen($senha) < 3) {
        return 2; // Senha inválida
    }

    return true; // Dados válidos
}


function insere_usuario($nome, $email, $senha) 
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {

        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        $stmt = $conexao->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);

        if ($stmt->execute()) {
            return $conexao->insert_id; 
        }
        return false;
    }
    return false;
}

function insere_usuario_perfil($codigo_usuario, $codigo_perfil) 
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {
        $stmt = $conexao->prepare("INSERT INTO usuario_perfil (codigo_usuario, codigo_perfil) VALUES (?, ?)");
        $stmt->bind_param("ii", $codigo_usuario, $codigo_perfil);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Erro: " . $conexao->error;
            return false;
        }
    }

    return false; 
}


function cadastrar_usuario($nome, $email, $senha, $perfis_selecionados) 
{
    $valido = Validar_usuario($nome, $senha);

    if ($valido === true) {
        $codigo_usuario = insere_usuario($nome, $email, $senha);

        if ($codigo_usuario) {
            foreach ($perfis_selecionados as $codigo_perfil) {
                if (!insere_usuario_perfil($codigo_usuario, $codigo_perfil)) {
                    return 4; // Erro ao vincular perfil ao usuário
                }
            }
            return 3; // Usuário cadastrado com sucesso
        } else {
            return 4; // Erro ao cadastrar no banco de dados
        }
    }
    return $valido; 
}



function apagar_usuario($chave_pri) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {
        $conexao->query("DELETE FROM usuario_perfil WHERE codigo_usuario = $chave_pri");
        $conexao->query("DELETE FROM usuario WHERE codigo = $chave_pri");
    } 
}