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
            $alea = ($i == 3 or $i==7)?strtoupper($letras[$alea]):$letras[$alea];

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
    $mail->Username = 'flp.lic23@uea.edu.br'; // e-mail que envia
    $mail->Password = 'vvtg eowl sfbu cild'; // senha de app
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('flp.lic23@uea.edu.br', 'Gerenciamento de Recursos UEA');// email ultilizado para envio
    $mail->addAddress($destinatario); // usa o destinatário passado

    $mail->isHTML(true);
    $mail->Subject = 'Sua nova senha'; // titulo do email
    $mail->Body    = "Olá, sua nova senha é: <h1>$nova_senha</h1>"; // corpo do email
    $mail->AltBody = "Olá, sua nova senha é: $nova_senha"; // final do email

    $mail->send();
        
    
}




$msg = 'Informe seu E-mail de cadastrado.';
$email = '';
$id = 'danger';
$html = file_get_contents('View/vEsquecelSenha.php');
if(isset($_GET['mandar'])){
    include 'Model/mLogin.php';
    $email = $_GET['email'];
    $temos_esse_email = verifificar_email($email);

    

    

    if ($temos_esse_email){
        $nova_senha = randomizar_senha();
        // mandar senha para o banco
        nova_senha_usuario($email, $nova_senha);


        // eviar email com a nova senha
        enviar_email($nova_senha, $email);
        $msg = 'Eviamos uma nova senha para seu E-mail.';
        $email = '';
        $id = 'success';

    }else{
        $msg = 'E-mail ínvalido.';

    }



}


$html = str_replace('{{email}}',$email ,$html);



$html = str_replace('{{msg}}',$msg ,$html);
$html = str_replace('{{resposta}}',$id ,$html);
echo $html;

?>

