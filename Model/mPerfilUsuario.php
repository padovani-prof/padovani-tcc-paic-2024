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


function insere_perfil($nome, $descricao){

    $validar = Validar_perfil($nome, $descricao);

    if ($validar === true)
    {
        include 'confg_banco.php';
    
        $conexao = new mysqli($servidor, $usuario, $senha, $banco);

        if(!$conexao->connect_error)
        {
            $resulta = $conexao->query ("INSERT INTO perfil_usuario (nome, descricao) values ('$nome', '$descricao')");

            // Adicionou no banco

            return 2; // inserido corretamente
            
        }
    }

    // não adicionou no banco
    return $validar;

}


?> 