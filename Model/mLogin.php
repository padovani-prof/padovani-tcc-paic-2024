<?php 


function logar($email_usu, $senha_usu){

  // connecção com o Banco
  include 'confg_banco.php';
  $connecxao = new mysqli($servidor, $usuario, $senha, $banco);


  

    // Consulta o banco 
    $senha_usu = hash('sha256', $senha_usu);
    $resultado_da_consulta = $connecxao->prepare("SELECT codigo, nome from usuario where email = ? and senha = ?");
    $resultado_da_consulta->bind_param('ss', $email_usu, $senha_usu);
    $resultado_da_consulta->execute();
    $resultado_da_consulta = $resultado_da_consulta->get_result();

    # se as enformações passadas constarem no banco
    if ($resultado_da_consulta->num_rows > 0){

      # retorna o nome do usuario
      $usuario = $resultado_da_consulta->fetch_assoc();

      # retorna o nome do usuario
      return array($usuario['codigo'], $usuario['nome']);

    }
    
  
  return null;
  
  
}


function verifificar_email($email){
  include 'confg_banco.php';
  $connecxao = new mysqli($servidor, $usuario, $senha, $banco);
  $resultado_da_consulta = $connecxao->prepare("SELECT  nome from usuario where email = ?;");
  $resultado_da_consulta->bind_param('s', $email);
  $resultado_da_consulta->execute();
  $resultado_da_consulta = $resultado_da_consulta->get_result();

  
  $respo = ($resultado_da_consulta->num_rows > 0)? true:false;
  $connecxao->close();
  return $respo;




}



function nova_senha_usuario($email, $nova_senha){
  // connecção com o Banco
  include 'confg_banco.php';
  $connecxao = new mysqli($servidor, $usuario, $senha, $banco);

  // Consulta o banco 
  $nova_senha = hash('sha256', $nova_senha);
  $resultado_da_consulta = $connecxao->prepare("UPDATE usuario set usuario.senha = ? WHERE usuario.email = ?;");
  $resultado_da_consulta->bind_param('ss', $nova_senha, $email);
  $resultado_da_consulta->execute();
  $connecxao->close();

}




function checar_senha($pessoa, $senha_usuario){

  include 'confg_banco.php';
  $connecxao = new mysqli($servidor, $usuario, $senha, $banco);

  // Consulta o banco 
  $senha_usuario = hash('sha256', $senha_usuario);
  $resultado_da_consulta = $connecxao->prepare("SELECT * from usuario WHERE usuario.codigo = ? and usuario.senha = ?;");
  $resultado_da_consulta->bind_param('is', $pessoa, $senha_usuario);
  $resultado_da_consulta->execute();
  $resultado_da_consulta = $resultado_da_consulta->get_result();
  $connecxao->close();

  return $resultado_da_consulta->num_rows > 0;

}


?>



