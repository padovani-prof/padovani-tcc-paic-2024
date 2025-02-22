
<?php 


function carrega_recurso($codigo)
{
    
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resultado = $conecxao->query("SELECT nome from recurso where codigo=$codigo");
    $conecxao->close();

    return $resultado->fetch_assoc()['nome'];
    // vai retorna uma lista com o nome do recurso

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
    $resulta = $conecxao->prepare("INSERT INTO checklist (item, codigo_recurso
    ) values(?, ?)");
    $resulta->bind_param("si", $item, $codigo);
    $resulta->execute();
    $conecxao->close();


}


function apagar_acesso_ao_recurso($chave_pri)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $conecxao->query("DELETE from checklist where codigo=$chave_pri");
    $conecxao->close();

}


function Existe_essa_chave_na_tabela($chave, $tabela, $jogar_pra_onde){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulata = $conecxao->query("SELECT * from $tabela where codigo=$chave");
    $conecxao->close();
    if($resulata->num_rows == 0){
        header("Location: $jogar_pra_onde");
        exit();
    }

}

?>
