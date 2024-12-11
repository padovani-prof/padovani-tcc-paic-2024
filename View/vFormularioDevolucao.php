<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Devolução</title>
</head>
<body>
  <header>
      <h1>Devolução</h1>
  </header>

  <section>
    
        <form action="cFormularioDevolucao.php">  
            <p>
              <label for="">Recursos Retirados: </label>
              <select name="recurso" id="">
              {{recursos}}
              </select>
            </p>
            
            <label for="">Devolvente: </label>
            <select name="devolvente" id="">
              {{devolvente}}
            </select>

            <p id="mensagem-{{retorno}}">{{mensagem}}</p>  

            <p>
              <input type="submit" value="Confirmar" name="btnConfirmar"> 
            </p>
        </form> 

        <a href="cMenu.php"><input type="button" value="Voltar"></a>
      
  </section>



  
</body>
</html>

