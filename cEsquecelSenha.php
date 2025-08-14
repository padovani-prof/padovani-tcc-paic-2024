<?php 
require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function randomizar_senha(){
    $letras = 'abcdefghijklmnopkrstuvwxyz';
    $numeros = '1234567890%#&$!*';

    $senha = '';
    for ($i=0; $i < 8 ; $i++) { 
        if($i%2==0){
            $alea = random_int(0, 14); 
            $alea = $numeros[$alea];
        }else{
            $alea = random_int(0, 25); 
            $alea = strtoupper($letras[$alea]);
        }
        $senha.= $alea;
    }
    return $senha;
}

function enviar_email($nova_senha, $destinatario) {
    $mail = new PHPMailer(true);
   
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sgrp.uea@gmail.com'; // E-mail utilizado para envio
    $mail->Password = 'xnii wppw xdsw ndwr'; // Senha de aplicativo
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('sgrp.uea@gmail.com', 'SGRP (CESIT/UEA)'); // Remetente
    $mail->addAddress($destinatario); // Destinatário informado

    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de Senha'; // Assunto do e-mail
    $mail->Body    = "Olá, sua nova senha é: <h1>$nova_senha</h1>"; // Corpo do e-mail em HTML
    $mail->AltBody = "Olá, sua nova senha é: $nova_senha"; // Corpo do e-mail em texto simples

    $mail->send();
}

$msg = 'Informe o endereço de e-mail cadastrado.';
$email = '';
$id = 'danger';
$html = file_get_contents('View/vEsquecelSenha.php');

if(isset($_GET['mandar'])){
    include 'Model/mLogin.php';
    $email = $_GET['email'];
    $temos_esse_email = verifificar_email($email);

    if ($temos_esse_email){
        $nova_senha = randomizar_senha();
        
        // Atualiza a senha no banco de dados
        nova_senha_usuario($email, $nova_senha);

        // Envia e-mail com a nova senha
        enviar_email($nova_senha, $email);
        $msg = 'Uma nova senha foi enviada para o seu e-mail.';
        $email = '';
        $id = 'success';
    }else{
        $msg = 'Endereço de e-mail inválido.';
    }
}

$html = str_replace('{{email}}',$email ,$html);
$html = str_replace('{{msg}}',$msg ,$html);
$html = str_replace('{{resposta}}',$id ,$html);
echo $html;
?>
