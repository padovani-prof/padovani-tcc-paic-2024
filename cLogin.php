<?php

if (isset($_GET['desconectar']) or isset($_GET['msg'])){
    if (isset($_GET['desconectar'])){
        session_start();
        session_destroy();
    }
    $msm = file_get_contents('View/vLogin.php');
    $msm = str_replace('{{mensagem}}','Desconectado do sistema!',$msm);
    $msm = str_replace('{{resp}}', 'success', $msm); // success ou danger sair
    echo $msm;
}
else if (isset($_POST['txtemail']) and isset($_POST['txtsenha']))

{
    
    // Inclui a lógica de autenticação
    include_once 'Model/mLogin.php';

    // Verifica se os dados foram enviados via 

    $email_usu = $_POST['txtemail'];
    $senha_usu = $_POST['txtsenha'];

    // Chama o método de login e recebe o nome do usuário caso autenticado, ou NULL caso contrário
    $usuario = logar($email_usu, $senha_usu);

    if ($usuario === null) {
        // Login falhou, carrega o vLogin.php e insere a mensagem de erro
        $html = file_get_contents('View/vLogin.php');
        $html = str_replace('{{resp}}', 'danger', $html);

        $html = str_replace('{{mensagem}}', 'Falha de autenticação!', $html);
        echo $html;
    } else {
        //Login bem-sucedido, carrega o vMenu.php e insere a saudação
        session_start();
        $_SESSION['codigo_usuario'] = $usuario[0];
        header("Location: cMenu.php?usuario=$usuario[1]");
        exit();
       
    }
}
else
{
    $msg = '';
    if(isset($_GET['msg']))
    {
        $msg = $_GET['msg'];
    }

    $html = file_get_contents('View/vLogin.php');
    $html = str_replace('{{resp}}', 'danger', $html);
    $html = str_replace('{{mensagem}}',$msg, $html);
    echo $html;


}

?>

