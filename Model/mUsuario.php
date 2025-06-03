<?php 
function apagar_perfio_relacionado($codigo) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $stmt = $conexao->prepare("DELETE FROM usuario_perfil WHERE codigo_usuario = ?");
    $stmt->bind_param("i", $codigo);

    $stmt->execute();
    $stmt->close();
    $conexao->close();
}

function novo_usuario_atualizado($codigo, $nome, $email, $senha_usu){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    if(empty($senha_usu)){
        
        $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, email = ? WHERE codigo = ?");
        $stmt->bind_param("ssi", $nome, $email, $codigo);

    }
    else{
        $senha_usu = hash('sha256', $senha_usu);
        $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, email = ?, senha = ? WHERE codigo = ?");
        $stmt->bind_param("sssi", $nome, $email, $senha_usu, $codigo);

    }
    $resultado = $stmt->execute();
    $stmt->close();
    $conexao->close();

    return $resultado;
}



function Validar_usuario_atualizado($codigo, $nome, $senha, $email) {
    
    // Remover espaços
    $nome = str_replace(' ', '', $nome);
    $senha = str_replace(' ', '', $senha);
    $email = str_replace(' ', '', $email);
    
    // Validar nome
    if (strlen($nome) < 3 || strlen($nome) > 50) {
        return 0; // Nome inválido
    }

    // Validar senha
    if (!empty($senha) && (mb_strlen($senha) < 3 || mb_strlen($senha) > 50)) {
        return 2; // Senha inválida
    }
    
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verificar conexão
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Verificar se o nome já existe, excluindo o usuário atual
    $stmt = $conexao->prepare("SELECT * FROM usuario WHERE codigo != ? AND nome = ?");
    $stmt->bind_param("is", $codigo, $nome);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $stmt->close();
        $conexao->close();
        return 4; // Nome repetido
    }

    // Verificar se o email já existe, excluindo o usuário atual
    $stmt = $conexao->prepare("SELECT * FROM usuario WHERE codigo != ? AND email = ?");
    $stmt->bind_param("is", $codigo, $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $stmt->close();
        $conexao->close();
        return 5; // Email repetido
    }

    // Fechar conexões
    $stmt->close();
    $conexao->close();

    return true; // Validação bem-sucedida
}






function atualizar_usuario($codigo, $nome, $email, $senha, $perfis_selecionados){
    $valido = Validar_usuario_atualizado($codigo, $nome, $senha, $email);
    if ($valido === true) {
        novo_usuario_atualizado($codigo, $nome, $email, $senha);
        apagar_perfio_relacionado($codigo);
        foreach ($perfis_selecionados as $codigo_perfil) {
            if (!insere_usuario_perfil($codigo, $codigo_perfil)) {
                return 4; // Erro ao vincular perfil ao usuário
            }
        }
        return 3; // Usuário cadastrado com sucesso
           
    }
    
    return $valido; 

}
function carregar_dados($codigo){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    
    $sql = "SELECT nome, email FROM usuario WHERE codigo = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $dados = $resultado->fetch_assoc();
    
    $stmt->close();
    $conexao->close();
    
    return $dados;
}

function carrega_perfil_do_usuario($codigo){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    
    $sql = "SELECT codigo_perfil FROM usuario_perfil WHERE codigo_usuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $chaves = [];
    while ($row = $resultado->fetch_assoc()) {
        $chaves[] = $row['codigo_perfil'];
    }
    
    $stmt->close();
    $conexao->close();
    
    return $chaves;
}




function listar_usuarios(){   
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT * FROM usuario order by nome asc;");
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conexao->close();

    return $resultado;
}

function listar_perfil(){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $resultado = $conexao->query("SELECT * FROM perfil_usuario ORDER BY nome ASC");
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conexao->close();

    return $resultado;

}

function Validar_usuario($nome, $senha, $email)
{

    $nome = str_replace(' ','',$nome);
    $senha = str_replace(' ', '', $senha);
    $email = str_replace(' ', '',$email);
   

    if (strlen($nome) < 3 || strlen($nome) > 50) {
        return 0; // Nome inválido
    }

    if (empty($senha)) {
        return 1; // Senha vazia
    }

    if (strlen($senha) > 50 || strlen($senha) < 3) {
        return 2; // Senha inválida
    }
    if(verificar_existencia($nome, 'nome')){
        return 4; // nome repetido
       
   
   }
   if(verificar_existencia($email, 'email')){
    return 5; // email  repetido
            
    
   }

    return true; // Dados válidos
}


function insere_usuario($nome, $email, $senha_usu) 
{
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {

        $senha_usu = hash('sha256', $senha_usu);

        $stmt = $conexao->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha_usu);

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

    $valido = Validar_usuario($nome, $senha, $email);
    
    if ($valido === true) {
        $codigo_usuario = insere_usuario($nome, $email, $senha);
        if ($codigo_usuario) {
            foreach ($perfis_selecionados as $codigo_perfil) {
                if (!insere_usuario_perfil($codigo_usuario, $codigo_perfil)) {
                    return 4; // Erro ao vincular perfil ao usuário
                }
            }
            return 3; // Usuário cadastrado com sucesso
        }   
    }
    return $valido; 
}



function apagar_usuario($chave_pri) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verificar conexão
    if ($conexao->connect_error) {
        return false; // Se a conexão falhar, retorna false
    }

    // Verificar se o usuário tem retiradas ou devoluções
    $stmt = $conexao->prepare("SELECT * FROM retirada_devolucao WHERE codigo_usuario = ?");
    $stmt->bind_param("i", $chave_pri);
    $stmt->execute();
    $qdt_ultilizada = $stmt->get_result();

    if ($qdt_ultilizada->num_rows > 0) {
        $stmt->close();
        $conexao->close();
        return 1; // O usuário tem retiradas/devoluções
    }

    // Verificar se o usuário tem reservas
    $stmt = $conexao->prepare("SELECT * FROM reserva WHERE codigo_usuario_utilizador = ? OR codigo_usuario_agendador = ?");
    $stmt->bind_param("ii", $chave_pri, $chave_pri);
    $stmt->execute();
    $qdt_ultilizada = $stmt->get_result();

    if ($qdt_ultilizada->num_rows > 0) {
        $stmt->close();
        $conexao->close();
        return 2; // O usuário tem reservas
    }

    // Apagar o usuário
    $stmt = $conexao->prepare("DELETE FROM usuario_perfil WHERE codigo_usuario = ?");
    $stmt->bind_param("i", $chave_pri);
    $stmt->execute();

    $stmt = $conexao->prepare("DELETE FROM usuario WHERE codigo = ?");
    $stmt->bind_param("i", $chave_pri);
    $stmt->execute();

    // Fechar conexões
    $stmt->close();
    $conexao->close();

    return 0; // Usuário apagado com sucesso
}

function verificar_existencia($dado, $coluna) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    // Verificar conexão
    if ($conexao->connect_error) {
        return false; // Se a conexão falhar, retorna false
    }

    // Preparar e executar a consulta
    $stmt = $conexao->prepare("SELECT * FROM usuario WHERE $coluna = ?");
    $stmt->bind_param("s", $dado); // Usando "s" para dado do tipo string
    $stmt->execute();
    $resulta = $stmt->get_result();

    // Fechar conexões
    $stmt->close();
    $conexao->close();

    // Verificar se o dado existe
    return $resulta->num_rows > 0;
}






function possui_permicão_para_adicionar_perfis($usua){

    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $stmt = $conecxao->prepare("SELECT funcionalidade.nome as Possui_a_permicao FROM funcionalidade_perfil
        INNER join perfil_usuario
        on perfil_usuario.codigo = funcionalidade_perfil.codigo_perfil
        INNER JOIN funcionalidade
        ON funcionalidade.codigo = funcionalidade_perfil.codigo_funcionalidade
        INNER join usuario_perfil
        ON usuario_perfil.codigo_perfil= perfil_usuario.codigo
        WHERE usuario_perfil.codigo_usuario = ? and funcionalidade.sigla = 'perfisalheios_usuario';");
    $stmt->bind_param("i", $usua);
    $stmt->execute();
    $resulta = $stmt->get_result();

    $stmt->close();
    $conecxao->close();

    return $resulta->num_rows > 0;

}


