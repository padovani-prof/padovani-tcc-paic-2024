<?php 
function listar_usuarios()
{   
    // Conexão com o banco de dados
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT * FROM usuario");
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
    // Validação do nome e senha
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
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conecxao->connect_error) {

        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        // Usar prepared statements para evitar SQL Injection
        $stmt = $conecxao->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);

        // Executa a query e retorna o resultado
        if ($stmt->execute()) {
            return true; // Adicionado com sucesso
        }

        return false; // Falha ao adicionar
    }

    // Erro de conexão
    return false;
}

function cadastrar_usuario($nome, $email, $senha)
{
    // Validação dos dados
    $valido = Validar_usuario($nome, $senha);

    if ($valido === true) {
        // Inserção no banco
        if (insere_usuario($nome, $email, $senha)) {
            return 3; // Usuário cadastrado com sucesso
        } else {
            return 4; // Erro ao cadastrar no banco de dados
        }
    }

    // Dados inválidos
    return $valido;
}

function apagar_usuario($chave_pri)
{
   
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    $resulata = $conecxao->query("DELETE from usuario where codigo=$chave_pri");

} 