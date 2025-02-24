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
    $resultado = $conexao->query("SELECT * FROM perfil_usuario");

    // Inicializa um array vazio

    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $conexao->close();


    // Retorna o array com todos os dados
    return $resultado;
}

function apagar_perfil($chave_pri) {
    include 'confg_banco.php';
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if (!$conexao->connect_error) {

        $qdt_lk_fucicionalidades =  $conexao->query("SELECT COUNT(*) as 'qdt_fucionalidades_linkadas' from usuario_perfil WHERE codigo_perfil=$chave_pri;")->fetch_assoc()['qdt_fucionalidades_linkadas'] + $conexao->query("SELECT COUNT(*) as 'qdt_fucionalidades_linkadas' from  acesso_recurso WHERE codigo_perfil=$chave_pri;")->fetch_assoc()['qdt_fucionalidades_linkadas']; //quantidades de dados que estão lincados com essa chave primario
        if($qdt_lk_fucicionalidades==0){
            $conexao->query("DELETE FROM funcionalidade_perfil WHERE codigo_perfil = $chave_pri");
        
            $conexao->query("DELETE FROM usuario_perfil WHERE codigo_perfil = $chave_pri");
            $conexao->query("DELETE FROM perfil_usuario WHERE codigo = $chave_pri");
            return true;
        }
        return false;

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

function mandar_dados_da_tabela($chave){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulata = $conecxao->query("SELECT * from perfil_usuario where codigo=$chave");
    $resulata = $resulata->fetch_assoc();
    $dados = [];
    $dados[] = $resulata;
    $resulata = $conecxao->query("SELECT codigo_funcionalidade from funcionalidade_perfil where codigo_perfil=$chave");
    $fucionalidades = [];
    while ($fucionalidade = $resulata->fetch_assoc()) {
        $fucionalidades[] = $fucionalidade['codigo_funcionalidade'];
    }
    $dados[] = $fucionalidades;
    return $dados;

}


function atualizar_fucionalidade($codigo, $nome, $descricao, $lista_funcionalidades){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    if(!verificar_existencia_nome_perfio_para_atualizar($nome, $codigo)){
        $resulta = $conecxao->query("UPDATE perfil_usuario set nome='$nome', descricao='$descricao' where codigo=$codigo");
        $resulta = $conecxao->query("DELETE from funcionalidade_perfil where codigo_perfil=$codigo");
        foreach($lista_funcionalidades as $fucionalidade){
            insere_funcionalidade_perfil($fucionalidade, $codigo);
        }
        return 2;

    }
    return 3;
    



}


function verificar_existencia_nome_perfio_para_atualizar($nome, $codigo){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conecxao->query("SELECT * from perfil_usuario where nome='$nome' and codigo!=$codigo");
    if($resulta->num_rows > 0){
        return true;
    }
    return false;

}

function verificar_existencia_nome_perfio($nome){
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    $resulta = $conecxao->query("SELECT * from perfil_usuario where nome='$nome'");
    if($resulta->num_rows > 0){
        return true;
    }
    return false;
}

?>
