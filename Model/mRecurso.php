<?php 

function tem_banco($categoria)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if ($conecxao->connect_error) {
        return false;
    }

    $consulta = $conecxao->prepare("SELECT * FROM categoria_recurso WHERE codigo = ?");
    $consulta->bind_param('i', $categoria);
    $consulta->execute();
    $resultado = $consulta->get_result();

    $consulta->close();
    $conecxao->close();

    return $resultado->num_rows > 0;
}

function Validar_recurso($nome, $desc, $cCatego)
{
    if (mb_strlen($nome) < 3 || mb_strlen($nome) > 50) {
        return 3; // número de caracteres do nome inválido
    }
   
    if (mb_strlen($desc) < 5 or mb_strlen($desc) > 100) {
        return 1; // passou do número máximo de caracteres da descrição
    }
   
    if (tem_banco($cCatego) === false) {
        return 2; // categoria não consta no banco
    }

    return true; // recurso válido
}

function insere_no_banco($nome, $descre, $cCatego)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if ($conecxao->connect_error) {
        return false;
    }

    $stmt = $conecxao->prepare("SELECT * FROM recurso WHERE nome = ?");
    $stmt->bind_param('s', $nome);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        $stmt = $conecxao->prepare("INSERT INTO recurso (nome, descricao, codigo_categoria) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $nome, $descre, $cCatego);

        if ($stmt->execute()) {
            $stmt->close();
            $conecxao->close();
            return 0; // Adicionado com sucesso
        } else {
            $stmt->close();
            $conecxao->close();
            return 5; // Erro ao inserir
        }
    } else {
        $stmt->close();
        $conecxao->close();
        return 4; // Nome repetido
    }
}

function cadastrar_recurso($nome, $des, $cCatego)
{
    $nome = trim(mb_strtoupper($nome));
    $descre = trim($des);

    $valido = Validar_recurso($nome, $descre, $cCatego);
    
    if ($valido === true) {
        return insere_no_banco($nome, $descre, $cCatego);
    }
    
    return $valido;
}

function apagar_recurso($chave_pri)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    if ($conecxao->connect_error) {
        return 6; // Erro na conexão
    }

    $stmt = $conecxao->prepare("SELECT * FROM retirada_devolucao WHERE codigo_recurso = ?");
    $stmt->bind_param('i', $chave_pri);
    $stmt->execute();
    $resulata = $stmt->get_result();

    if ($resulata->num_rows > 0) {
        $stmt->close();
        $conecxao->close();
        return 1; // Possui retirada
    }

    $stmt = $conecxao->prepare("SELECT * FROM ensalamento WHERE codigo_sala = ?");
    $stmt->bind_param('i', $chave_pri);
    $stmt->execute();
    $resulata = $stmt->get_result();

    if ($resulata->num_rows > 0) {
        $stmt->close();
        $conecxao->close();
        return 2; // Possui ensalamento
    }

    $stmt = $conecxao->prepare("SELECT * FROM reserva WHERE codigo_recurso = ?");
    $stmt->bind_param('i', $chave_pri);
    $stmt->execute();
    $resulata = $stmt->get_result();

    if ($resulata->num_rows > 0) {
        $stmt->close();
        $conecxao->close();
        return 3; // Possui reserva
    }

    $stmt = $conecxao->prepare("DELETE FROM checklist WHERE codigo_recurso = ?");
    $stmt->bind_param('i', $chave_pri);
    $stmt->execute();

    $stmt = $conecxao->prepare("DELETE FROM acesso_recurso WHERE codigo_recurso = ?");
    $stmt->bind_param('i', $chave_pri);
    $stmt->execute();

    $stmt = $conecxao->prepare("DELETE FROM recurso WHERE codigo = ?");
    $stmt->bind_param('i', $chave_pri);

    if ($stmt->execute()) {
        $stmt->close();
        $conecxao->close();
        return 0; // Recurso apagado
    }

    $stmt->close();
    $conecxao->close();
    return 7; // Erro ao apagar
}

function mandar_dados($chave)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conecxao->connect_error) {
        return null;
    }

    $stmt = $conecxao->prepare("SELECT * FROM recurso WHERE codigo = ?");
    $stmt->bind_param('i', $chave);
    $stmt->execute();
    $resulata = $stmt->get_result();

    $stmt->close();
    $conecxao->close();

    return $resulata->fetch_assoc();
}

function atualizar_dados($chave, $nome, $descre, $cCatego)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $stmt = $conecxao->prepare("SELECT codigo FROM recurso WHERE nome = ?");
    $stmt->bind_param('s', $nome);
    $stmt->execute();
    $resulata = $stmt->get_result();

    if ($resulata->num_rows == 0 || ($resulata->num_rows == 1 && $resulata->fetch_assoc()['codigo'] == $chave)) {
        $stmt = $conecxao->prepare("UPDATE recurso SET nome = ?, descricao = ?, codigo_categoria = ? WHERE codigo = ?");
        $stmt->bind_param('ssii', $nome, $descre, $cCatego, $chave);

        if ($stmt->execute()) {
            $stmt->close();
            $conecxao->close();
            return 0; // Atualizado com sucesso
        } else {
            $stmt->close();
            $conecxao->close();
            return 5; // Erro ao atualizar
        }
    }

    $stmt->close();
    $conecxao->close();
    return 4; // Nome repetido
}

function verificar_atualizar($chave, $nome, $des, $cCatego)
{
    $nome = trim(mb_strtoupper($nome));
    $descre = trim($des);

    $valido = Validar_recurso($nome, $descre, $cCatego);
    
    if ($valido === true) {
        return atualizar_dados($chave, $nome, $descre, $cCatego);
    }
    return $valido;
}

function Existe_esse_recurso($chave)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conecxao->connect_error) {
        return false;
    }

    $stmt = $conecxao->prepare("SELECT * FROM recurso WHERE codigo = ?");
    $stmt->bind_param('i', $chave);
    $stmt->execute();
    $resulata = $stmt->get_result();

    $stmt->close();
    $conecxao->close();

    return $resulata->num_rows > 0;
}

function Carregar_recursos_dados()
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($conecxao->connect_error) {
        return [];
    }

    $resultado = $conecxao->query("SELECT * FROM recurso");

    $conecxao->close();

    return $resultado->fetch_all(MYSQLI_ASSOC);
}

?>
