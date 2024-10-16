<?php 


function logar($email_usu, $senha_usu){

  // connecção com o Banco
  include_once 'confg_banco.php';
  $connecxao = new mysqli($servidor, $usuario, $senha, $banco);


  // Verifica si não tem nem uma falha na connecção
  if (!$connecxao->connect_error)
  {

    // Consulta o banco 
    $senha_usu = hash('sha256', $senha_usu);
    $resultado_da_consulta = $connecxao->prepare("select nome from usuario where email = ? and senha = ?");
    $resultado_da_consulta->bind_param('ss', $email_usu, $senha_usu);
    $resultado_da_consulta->execute();
    $resultado_da_consulta = $resultado_da_consulta->get_result();

    # se as enformações passadas constarem no banco
    if ($resultado_da_consulta->num_rows > 0){

      # retorna o nome do usuario
      return $resultado_da_consulta->fetch_assoc()['nome'];

    }
    
  }
  return null;
  
  
}





?>



