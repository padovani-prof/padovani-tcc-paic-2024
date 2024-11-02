<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas</title>
</head>
<body>
  <header>
      <h1>Reservas</h1>
  </header>


  <section>
    
    <form action="cFormularioReserva.php">  
      <div>
        <table border="1">  
          <tr>
            <th>Recurso</th>
            <th>Usuário</th>
            <th>Datas</th>
            <th>Ações</th>
          </tr>
        
            {{Reservas}}
        
        </table>
      </div>
        
        <p>
          <input type="submit" value="Nova Reserva" name="btnNovaReserva"> 
        </p>
     
    <p id="mensagem-{{retorno}}">{{mensagem}}</p>  
    </form> 

    <a href="cMenu.php"><input type="button" value="Voltar"></a> 
      
  </section>



  
</body>
</html>