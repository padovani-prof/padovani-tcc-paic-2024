<?php 

function apagar_perfio_relacionado($codigo){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("DELETE  from usuario_perfil where codigo_usuario=$codigo;");
    $conexao->close();

}

function novo_usuario_atualizado($codigo, $nome, $email, $senha_usu){

    $senha_usu = hash('sha256', $senha_usu);

    include 'confg_banco.php';

    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $stmt = $conexao->prepare("UPDATE usuario SET nome = ?, email = ?, senha = ? WHERE codigo = ?");

    $stmt->bind_param("sssi", $nome, $email, $senha_usu, $codigo);
    $resultado = $stmt->execute();
    $stmt->close();
    $conexao->close();

    return $resultado;
}


function Validar_usuario_atualizado($codigo, $nome, $senha, $email){

    $nome = str_replace(' ','',$nome);
    $senha = str_replace(' ', '', $senha);
    $email = str_replace(' ', '',$email);
    
    
    if (strlen($nome) < 2 || strlen($nome) > 50) {
        return 0; // Nome inválido
    }
    if (mb_strlen("$senha") > 50 || mb_strlen("$senha") < 3) {
        return 2; // Senha inválida
    }
    
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT * FROM usuario where codigo!=$codigo and nome='$nome';");
    if($resultado->num_rows > 0){
        return 4; // nome repetido
    }
    $resultado = $conexao->query("SELECT * FROM usuario where codigo!=$codigo and email='$email';");
    if($resultado->num_rows > 0){
        return 5; // email repetido
    }
    return true;

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
    $resultado = $conexao->query("SELECT nome, email FROM usuario where codigo=$codigo");
    $conexao->close();
    return $resultado->fetch_assoc();

}


function carrega_perfil_do_usuario($codigo){
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT codigo_perfil FROM usuario_perfil where codigo_usuario=$codigo;");
    $chaves =[];
    while($codigo = $resultado->fetch_assoc()){
        $chaves[] = $codigo['codigo_perfil'];
    }
    $conexao->close();

    return $chaves;
}



function listar_usuarios(){   
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    $resultado = $conexao->query("SELECT * FROM usuario");
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
   

    if (strlen($nome) < 2 || strlen($nome) > 50) {
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

    if (!$conexao->connect_error) {

        $qdt_ultilizada = $conexao->query("SELECT *  from retirada_devolucao WHERE retirada_devolucao.codigo_usuario=$chave_pri;"); 
    
        if($qdt_ultilizada->num_rows > 0){
            return 1;
            
        }
        $qdt_ultilizada = $conexao->query("SELECT * from reserva WHERE reserva.codigo_usuario_utilizador=$chave_pri or reserva.codigo_usuario_agendador=$chave_pri;");
        if($qdt_ultilizada->num_rows>0){
            return 2;
        }else{
            $conexao->query("DELETE FROM usuario_perfil WHERE codigo_usuario = $chave_pri");
            $conexao->query("DELETE FROM usuario WHERE codigo = $chave_pri");
            return 0;

        }
        
    }
        
}

function verificar_existencia($dado, $coluna){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conecxao->query("SELECT * from usuario where $coluna='$dado'");
    if($resulta->num_rows > 0){
        return true;
    }
    return false;
}


