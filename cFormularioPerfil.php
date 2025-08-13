<?php



# verificação de acesso da fucionalidade
# vai mandar o codi usuario e o codigo que aquela fucionalidade pertence
include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();



include_once 'Model/mPerfilUsuario.php';  



$funcionalidades = possui_permicão_para_adicionar_permic($_SESSION['codigo_usuario']);
$titulo = ($funcionalidades)?'Funcionalidades: ':'';
$funcionalidades = ($funcionalidades)?listar_funcionalidade():[];
$id_resposta = 'danger';
$tipo_tela = '';
$nome = '';
$descricao = '';
$mensagem = '';
$funcionalidades_selecionadas = [];

// Verifica se o formulário foi enviado e captura as informações
if (isset($_GET["salvar"])) {
    
    if(isset($_GET['codigo'])){
        $verificar = verificação_acesso($_SESSION['codigo_usuario'], 'alt_perfil');
        if($verificar==false){
            header('Location: cMenu.php?msg=Acesso negado!');
            exit();

        }
        $codigo = $_GET['codigo'];
        
        $tipo_tela = '<input type="hidden" name="codigo" value="'.$codigo.'">';
    }
    
    $nome = trim($_GET['nome']);
    $descricao = trim($_GET['descricao']);

    if (isset($_GET['funcionalidades']) && is_array($_GET['funcionalidades'])) {
        $funcionalidades_selecionadas = $_GET['funcionalidades'];
    }
    
    if (empty($nome)) {
        $mensagem = "O nome é obrigatório!";
    }elseif (mb_strlen($nome)< 3 or mb_strlen($nome)> 30 ) {
        $mensagem = 'O nome informado é inválido. Ele deve ter no mínimo 3 caracteres e no máximo 30';
        
    } 
    elseif ((mb_strlen($descricao) > 100  or (mb_strlen($descricao) < 5 ))) {
        $mensagem = "Adicione uma descrição com no mínimo 5 e no máximo 100 caracteres";
    }
     else {
        if(isset($_GET['codigo'])){
            // atualizar dados
            $resposta = atualizar_fucionalidade($codigo, $nome, $descricao, $funcionalidades_selecionadas);
            if ($resposta==3){
                $mensagem = "Nome existente. Insira um novo.";

            }
            else if ($resposta == 2) {
                $nome = '';
                $descricao = '';
                $funcionalidades_selecionadas = []; 
                $mensagem = "Perfil atualizado com Sucesso!";
                $id_resposta = 'success';
            }
            else if($resposta ==0){
                $mensagem = "O nome do Perfil informado é inválido. Ele deve ter no mínimo 3 caracteres";

            }
            else {
                $mensagem = "Erro ao cadastrar o perfil. Verifique os dados.";
            }


        }else{
            // cadastrar perfil
            $resposta = insere_perfil($nome, $descricao, $funcionalidades_selecionadas);
            if ($resposta==3){
                $mensagem = "Nome existente. Insira um novo.";

            }
            else if ($resposta == 2) {
                $nome = '';
                $descricao = '';
                $funcionalidades_selecionadas = []; 
                $mensagem = "Perfil cadastrado com Sucesso!";
                $id_resposta = 'success';
            }
            else if($resposta==0){
                $mensagem = "O nome do Perfil informado é inválido. Ele deve ter no mínimo 3 caracteres";

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
    Verificar_codigo('perfil_usuario', $codigo);
    $dados =  mandar_dados_da_tabela($codigo);
    $nome = $dados[0]['nome'];
    $descricao = $dados[0]['descricao'];
    $funcionalidades_selecionadas = $dados[1];
    $tipo_tela = '<input type="hidden" name="codigo" value="'.$codigo.'">';
}else{
    $verificar = verificação_acesso($_SESSION['codigo_usuario'], 'cad_perfil');
    if ($verificar == false and !isset($_GET['codigo']))
    {
        header('Location: cMenu.php?msg=Acesso negado!');
        exit();
    }

}

if (is_array($funcionalidades)) {
    $aux = "<tr>"; 
    $cont = 1;
    foreach ($funcionalidades as $linha) {
        $checked = in_array($linha['codigo'], $funcionalidades_selecionadas) ? "checked" : "";
        $aux .= '
        <td title="'.$linha['descricao'] .'" alt="'.$linha['descricao'] .'"><input type="checkbox" name="funcionalidades[]"'.$checked.' value="'. $linha['codigo'] .'"/>'.$linha['nome'].($cont%3==0?'<br>&nbsp;</td></tr>':'<br>&nbsp;</td>');
        $cont ++;
    }
    
} else {
    $aux .= "<input type='checkbox' name='funcionalidades'> Não há nenhuma funcionalidade cadastrada <br>";
}

$html = file_get_contents('View/vFormularioPerfil.php');
$html = cabecalho($html, $_SESSION['codigo_usuario']);

$html = str_replace('{{campoNome}}', $nome, $html);
$html = str_replace('{{campoDescricao}}', $descricao, $html);
$html = str_replace('{{mensagem}}', $mensagem, $html);  
$html = str_replace('{{funcionalidades}}', $aux, $html);
$html = str_replace('{{tipo_tela}}', $tipo_tela, $html);
$html = str_replace('{{retorno}}', $id_resposta, $html);

$html = str_replace('{{titulo}}', $titulo, $html);
echo $html;
?>



        
    


