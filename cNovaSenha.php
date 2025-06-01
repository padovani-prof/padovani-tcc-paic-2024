<?php 



$html = file_get_contents('View/vNovaSenha.php');

include 'Model/mLogin.php';

include 'cGeral.php';
$senha_at = '';
$senha_atual = '';
$senha_comf = '';

$msg = (isset($_GET['msg']))?$_GET['msg']:'';
$id = (isset($_GET['msg']))?'success':'danger';


Esta_logado();

$usuario = $_SESSION['codigo_usuario'];


$input = (!isset($_POST['senha_nova']))?'<label for="">Senha Atual: </label>
    <input type="password" name="senha_antiga" value="{{antiga}}" >':


    '<label for="">Senha Nova: </label>
    <input type="password" name="senha_nova" value="{{nova}}" >
    <label for="">Confirmar Senha</label>
    <input type="password" name="senha_repatida" value="{{comf}}">';


if (isset($_POST['mandar'])){

    $senha = (isset($_POST['senha_antiga']))?$_POST['senha_antiga']:$_POST['senha_nova'];

    if(isset($_POST['senha_repatida'])){
        $senha_repetida = $_POST['senha_repatida'];        
        $senha_atual = $senha;
        $senha_comf = $senha_repetida;
        if (mb_strlen($senha) > 50 or mb_strlen($senha) < 3){
            $msg = 'Quantidade de caracter da senha ínvalida';
        }elseif ($senha != $senha_repetida) {
            $msg = 'As senhas não correspondem';
        }else {
           // atualizar
           $msg = 'Senha alterada com Sucesso.';
           
           header("Location: cNovaSenha.php?msg=$msg");
           exit();
        


        }

        

    }else{
        // verifica no banco 
        $verificar_senha = checar_senha($usuario, $senha);
        if($verificar_senha){
            $input = '<label for="">Senha Nova: </label>
    <input type="password" name="senha_nova" value="{{nova}}" >
    <label for="">Confirmar Senha: </label>
    <input type="password" name="senha_repatida" value="{{comf}}" >';

    $msg = 'Crie uma nova senha.';
        }else{
            $senha_at = $senha;
            $msg = 'Senha ínvalida.';

        }
    }
    
}



$html = str_replace('{{dados}}', $input, $html);
$html = str_replace('{{antiga}}', $senha_at, $html);
$html = str_replace('{{nova}}', $senha_atual, $html);
$html = str_replace('{{comf}}', $senha_comf, $html);
$html = str_replace('{{msg}}', $msg, $html);
$html = str_replace('{{resp}}', $id, $html);
echo $html;











?>

