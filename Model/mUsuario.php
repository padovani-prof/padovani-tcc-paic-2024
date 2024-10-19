<?php 
function listar_usuarios()
{   
    // Conexão com o banco de dados
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Executa a consulta
    $resultado = $conexao->query("SELECT nome, email FROM usuario");

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

function listar_perfil(){
    // Conexão com o banco de dados
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Executa a consulta
    $resultado = $conexao->query("SELECT codigo, nome FROM perfil_usuario");

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

function Validar_usuario($nome, $senha)
{
    // retorna se o dado é valido

   if ( strlen($nome) < 2 or strlen($nome) > 50) 
   {
        return 0 ; // numero de caracter do nome invalido
   }
   
   if (empty($senha)){
        return 1;
   }
   elseif (strlen($senha) > 50 or strlen($senha) < 3){
        return 2;
   }

   return 3;
   
   
}

function insere_usuario($nome, $email, $senha)
{
    // insere no banco
    include 'confg_banco.php';
    
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if(!$conecxao->connect_error)
    {
        $resulta = $conecxao->query ("INSERT INTO usuario (nome, emial, senha) values ('$nome', '$email', $senha)");

        // Adicionou no banco

        return $resulta;
        
    }
    // não adicionou no banco
    return false;

}

function cadastrar_usuario($nome, $email, $senha)
{
      
    // ver se os dados estão condisentes retornando true ou false
    $valido = Validar_usuario($nome, $email, $senha, $conf_senha);

    if ($valido === true )

    {
        $insere = insere_usuario($nome, $email, $senha);
        // retorna o dado 4 que foi adicionado com sucesso
        return 4;

    }
    return $valido;
}

?>