<?php 
function listar_funcionalidade(){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT * FROM funcionalidade ORDER BY nome ASC");
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conexao->close();


    // Retorna o array com todos os dados
    return $resultado;
}

function listar_perfis(){   
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Executa a consulta
    $resultado = $conexao->query("SELECT * FROM perfil_usuario where codigo!=1 order by nome");

    // Inicializa um array vazio

    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conexao->close();


    // Retorna o array com todos os dados
    return $resultado;
}




function apagar_perfil($chave_pri) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi bem-sucedida
    if ($conexao->connect_error) {
        return false;
    }

    // Contar as funcionalidades vinculadas
    $stmt = $conexao->prepare(
        "SELECT COUNT(*) as 'qdt_fucionalidades_linkadas' 
         FROM usuario_perfil WHERE codigo_perfil = ? 
         UNION ALL
         SELECT COUNT(*) 
         FROM acesso_recurso WHERE codigo_perfil = ?"
    );
    $stmt->bind_param("ii", $chave_pri, $chave_pri);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $contagem = $resultado->fetch_all(MYSQLI_ASSOC);

    // Verifica se existem dados vinculados ao perfil
    $qdt_lk_fucicionalidades = $contagem[0]['qdt_fucionalidades_linkadas'] + $contagem[1]['qdt_fucionalidades_linkadas'];

    if ($qdt_lk_fucicionalidades == 0) {
        // Apagar as funcionalidades vinculadas ao perfil
        $stmt = $conexao->prepare("DELETE FROM funcionalidade_perfil WHERE codigo_perfil = ?");
        $stmt->bind_param("i", $chave_pri);
        $stmt->execute();

        // Apagar os links de usuário/perfil
        $stmt = $conexao->prepare("DELETE FROM usuario_perfil WHERE codigo_perfil = ?");
        $stmt->bind_param("i", $chave_pri);
        $stmt->execute();

        // Apagar o perfil de usuário
        $stmt = $conexao->prepare("DELETE FROM perfil_usuario WHERE codigo = ?");
        $stmt->bind_param("i", $chave_pri);
        $stmt->execute();

        // Fechar o statement e a conexão
        $stmt->close();
        $conexao->close();

        return true;
    }

    // Fechar a conexão
    $stmt->close();
    $conexao->close();
    return false;
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
        
        if(!verificar_existencia_nome_perfio($nome)) {
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
        $validar = 3;
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








function mandar_dados_da_tabela($chave)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparar e executar a consulta para buscar dados do perfil
    $stmt = $conecxao->prepare("SELECT * FROM perfil_usuario WHERE codigo = ?");
    $stmt->bind_param("i", $chave);
    $stmt->execute();
    $resulata = $stmt->get_result()->fetch_assoc();
    $dados = [];
    $dados[] = $resulata;

    // Preparar e executar a consulta para buscar as funcionalidades
    $stmt = $conecxao->prepare("SELECT codigo_funcionalidade FROM funcionalidade_perfil WHERE codigo_perfil = ?");
    $stmt->bind_param("i", $chave);
    $stmt->execute();
    $resulata = $stmt->get_result();
    
    $fucionalidades = [];
    while ($fucionalidade = $resulata->fetch_assoc()) {
        $fucionalidades[] = $fucionalidade['codigo_funcionalidade'];
    }

    $dados[] = $fucionalidades;

    // Fechar o statement e a conexão
    $stmt->close();
    $conecxao->close();

    return $dados;
}

function atualizar_fucionalidade($codigo, $nome, $descricao, $lista_funcionalidades)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verificar se o nome do perfil já existe antes de atualizar
    if (!verificar_existencia_nome_perfio_para_atualizar($nome, $codigo)) {
        // Preparar a consulta de atualização do perfil
        $stmt = $conecxao->prepare("UPDATE perfil_usuario SET nome = ?, descricao = ? WHERE codigo = ?");
        $stmt->bind_param("ssi", $nome, $descricao, $codigo);
        $stmt->execute();

        // Preparar a consulta para excluir as funcionalidades anteriores
        $stmt = $conecxao->prepare("DELETE FROM funcionalidade_perfil WHERE codigo_perfil = ?");
        $stmt->bind_param("i", $codigo);
        $stmt->execute();

        // Inserir as novas funcionalidades
        foreach ($lista_funcionalidades as $fucionalidade) {
            insere_funcionalidade_perfil($fucionalidade, $codigo);
        }

        // Fechar o statement e a conexão
        $stmt->close();
        $conecxao->close();

        return 2; // Atualizado com sucesso
    }

    return 3; // Nome já existe
}

function verificar_existencia_nome_perfio_para_atualizar($nome, $codigo)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparar a consulta para verificar se o nome do perfil já existe
    $stmt = $conecxao->prepare("SELECT * FROM perfil_usuario WHERE nome = ? AND codigo != ?");
    $stmt->bind_param("si", $nome, $codigo);
    $stmt->execute();
    $resulta = $stmt->get_result();
    
    // Fechar o statement e a conexão
    $stmt->close();
    $conecxao->close();

    return $resulta->num_rows > 0;
}

function verificar_existencia_nome_perfio($nome)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    // Preparar a consulta para verificar se o nome do perfil já existe
    $stmt = $conecxao->prepare("SELECT * FROM perfil_usuario WHERE nome = ?");
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $resulta = $stmt->get_result();
    
    // Fechar o statement e a conexão
    $stmt->close();
    $conecxao->close();

    return $resulta->num_rows > 0;
}



?>
