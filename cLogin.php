<?php

if (isset($_GET['txtemail']) and isset($_GET['txtsenha']))

{
    
    // Inclui a lógica de autenticação
    include_once 'Model/mLogin.php';

    // Verifica se os dados foram enviados via 

    $email_usu = $_GET['txtemail'];
    $senha_usu = $_GET['txtsenha'];

    // Chama o método de login e recebe o nome do usuário caso autenticado, ou NULL caso contrário
    $usuario = logar($email_usu, $senha_usu);

    if ($usuario === null) {
        // Login falhou, carrega o vLogin.php e insere a mensagem de erro
        $html = file_get_contents('View/vLogin.php');
        $html = str_replace('{{mensagem}}', 'Falha de autenticação!', $html);
        echo $html;
    } else {
        // Login bem-sucedido, carrega o vMenu.php e insere a saudação

        header("Location: cMenu.php?usuario=$usuario");
        exit();
       
    }

}
else
{
    $msm = file_get_contents('View/vLogin.php');
    $msm = str_replace('{{mensagem}}','',$msm);
    echo $msm;

}

?>

