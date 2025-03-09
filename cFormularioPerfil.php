<?php



# verificação de acesso da fucionalidade
# vai mandar o codi usuario e o codigo que aquela fucionalidade pertence
include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
$verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_perfil');
if ($verificar == false and !isset($_GET['codigo']))
{
    header('Location: cMenu.php?msg=Acesso negado!');
    exit();
}

include_once 'Model/mPerfilUsuario.php';  
$funcionalidades = listar_funcionalidade();
$id_resposta = 'nada';
$tipo_tela = '';
$nome = '';
$descricao = '';
$mensagem = '';
$funcionalidades_selecionadas = [];

// Verifica se o formulário foi enviado e captura as informações
if (isset($_GET["salvar"])) {
    $id_resposta = 'erro';
    if(isset($_GET['codigo'])){
        $verificar = verificação_acesso($_SESSION['codigo_usuario'], 'alt_perfil');
        if($verificar==false){
            header('Location: cMenu.php?msg=Acesso negado!');
            exit();

        }
        $codigo = $_GET['codigo'];
        $tipo_tela = '<input type="hidden" name="codigo" value="'.$codigo.'">';
    }
    
    $nome = $_GET['nome'];
    $descricao = $_GET['descricao'];

    if (isset($_GET['funcionalidades']) && is_array($_GET['funcionalidades'])) {
        $funcionalidades_selecionadas = $_GET['funcionalidades'];
    }
    if (empty($nome)) {
        $mensagem = "O nome é obrigatório!";
    } elseif (empty($descricao)) {
        $mensagem = "A descrição é obrigatória!";
    } elseif (empty($funcionalidades_selecionadas)) {
        $mensagem = "Você deve selecionar pelo menos uma funcionalidade!";

    } else {
        if(isset($_GET['codigo'])){
            // atualizar dados
            $resposta = atualizar_fucionalidade($codigo, $nome, $descricao, $funcionalidades_selecionadas);
            if ($resposta==3){
                $mensagem = "Esse nome de perfil já está sendo ultilizado. Por favor dê outro nome.";

            }
            else if ($resposta == 2) {
                $nome = '';
                $descricao = '';
                $funcionalidades_selecionadas = []; 
                $mensagem = "Perfil atualizado com sucesso!";
                $id_resposta = 'sucesso';
            }
            else if($resposta ==0){
                $mensagem = "Numero de caracteres de nome perfio invalido. Ensira pelomenos 3 caracteres.";

            }
            else {
                $mensagem = "Erro ao cadastrar o perfil. Verifique os dados.";
            }


        }else{
            $resposta = insere_perfil($nome, $descricao, $funcionalidades_selecionadas);
            if ($resposta==3){
                $mensagem = "Esse nome de perfil já está sendo ultilizado. Por favor dê outro nome.";

            }
            else if ($resposta == 2) {
                $nome = '';
                $descricao = '';
                $funcionalidades_selecionadas = []; 
                $mensagem = "Perfil cadastrado com sucesso!";
                $id_resposta = 'sucesso';
            }
            else if($resposta ==0){
                $mensagem = "Numero de caracteres de nome perfio invalido. Ensira pelomenos 3 caracteres.";

            }
             else {
                $mensagem = "Erro ao cadastrar o perfil. Verifique os dados.";
            }
            
        }
        

        
    }
}else if (isset($_GET['codigo'])){
    $verificar = verificação_acesso($_SESSION['codigo_usuario'], 'alt_perfil');
    if($verificar==false){
        header('Location: cMenu.php?msg=Acesso negado!');
        exit();

    }
    $codigo = $_GET['codigo'];
    $dados =  mandar_dados_da_tabela($codigo);
    $nome = $dados[0]['nome'];
    $descricao = $dados[0]['descricao'];
    $funcionalidades_selecionadas = $dados[1];
    $tipo_tela = '<input type="hidden" name="codigo" value="'.$codigo.'">';
}

if (is_array($funcionalidades)) {
    $aux = "<br>"; 
    $cont = 1;
    foreach ($funcionalidades as $linha) {
        $checked = in_array($linha['codigo'], $funcionalidades_selecionadas) ? "checked" : "";
        $aux .= "<input type='checkbox' name='funcionalidades[]' value='" . $linha['codigo'] . "' $checked> " .'<span title="'.$linha['descricao'] .'"> '.$linha['nome'] .'</span> '. (($cont%3==0)?"<br>":"");
        $cont ++;
    }
} else {
    $aux .= "<input type='checkbox' name='funcionalidades'> Não há nenhuma funcionalidade cadastrada <br>";
}


$html = file_get_contents('View/vFormularioPerfil.php');
$html = str_replace('{{campoNome}}', $nome, $html);
$html = str_replace('{{campoDescricao}}', $descricao, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);  
$html = str_replace('{{funcionalidades}}', $aux, $html);
$html = str_replace('{{tipo_tela}}', $tipo_tela, $html);
$html = str_replace('{{retorno}}', $id_resposta, $html);
echo $html;
?>



