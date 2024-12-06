<?php 
function listar_funcionalidade(){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT * FROM funcionalidade ORDER BY nome ASC");

    $todos_dados = [];

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $todos_dados[] = $linha;
        }
    }

    $conexao->close();

    // Retorna o array com todos os dados
    return $todos_dados;
}

function listar_perfis(){   
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Executa a consulta
    $resultado = $conexao->query("SELECT * FROM perfil_usuario");

    // Inicializa um array vazio
    $todos_dados = [];

    // Popula o array com os resultados
    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $todos_dados[] = $linha;
        }
    }

    // Fecha a conexão
    $conexao->close();

    // Retorna o array com todos os dados
    return $todos_dados;
}

function apagar_perfil($chave_pri) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {
        $conexao->query("DELETE FROM funcionalidade_perfil WHERE codigo_perfil = $chave_pri");
        
        $conexao->query("DELETE FROM usuario_perfil WHERE codigo_perfil = $chave_pri");
        $conexao->query("DELETE FROM perfil_usuario WHERE codigo = $chave_pri");
    }
}

function Validar_perfil($nome, $descricao){
    if (strlen($nome) < 3 or strlen($nome) > 50) 
   {
        return 0 ; // numero de caracter do nome invalido
   }
   
   if (strlen($descricao) > 100  or (strlen($descricao) < 5 ))
   {
        return 1; // nome do curso invalido
   }
   
   return true; // recurso valido
}


function insere_perfil($nome, $descricao, $funcionalidades_selecionadas) {
    // Validar os dados antes de inserir
    $validar = Validar_perfil($nome, $descricao);

    if ($validar === true) {
        include 'confg_banco.php';
    
        $conexao = new mysqli($servidor, $usuario, $senha, $banco);

        if(!$conexao->connect_error) {
            // Inserir o perfil no banco
            $stmt = $conexao->prepare("INSERT INTO perfil_usuario (nome, descricao) VALUES (?, ?)");
            $stmt->bind_param("ss", $nome, $descricao);

            if ($stmt->execute()) {
                $codigo_perfil = $conexao->insert_id; // Pega o ID do perfil inserido

                foreach ($funcionalidades_selecionadas as $codigo_funcionalidade) {
                    insere_funcionalidade_perfil($codigo_funcionalidade, $codigo_perfil);
                }

                return 2; 
            } else {
                return 4; 
            }
        }
    }

    return $validar; 
}

function insere_funcionalidade_perfil($codigo_funcionalidade, $codigo_perfil) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {
        $stmt = $conexao->prepare("INSERT INTO funcionalidade_perfil (codigo_funcionalidade, codigo_perfil) VALUES (?, ?)");
        $stmt->bind_param("ii", $codigo_funcionalidade, $codigo_perfil);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true; 
        }
    }

    return false; 
}
?>