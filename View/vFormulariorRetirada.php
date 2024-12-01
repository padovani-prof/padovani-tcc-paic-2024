<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Retirada e Devolução</title>
</head>
<body>
  <header>
      <h1>Retirada</h1>
  </header>

  <section>
    
        <form action="cFormularioRetirada.php">  
            <p>
              <label for="">Recursos: </label>
              <select name="recurso" id="">
              {{recursos}}
              
              </select>
            </p>
            
            <label for="">Retirante: </label>
            <select name="retirante" id="">
              {{retirante}}

            </select>

            
              <p>
                Horário Final:
                <input type="time" name="hora_final"  id="" value="{{hora_fim}}">
            </p>
  
              
            <p id="mensagem-{{retorno}}">{{mensagem}}</p>  

            <p>
              <input type="submit" value="Confirmar" name="btnConfirmar"> 
            </p>
        </form> 

        <a href="cMenu.php"><input type="button" value="Voltar"></a>
      
  </section>



  
</body>
</html>

