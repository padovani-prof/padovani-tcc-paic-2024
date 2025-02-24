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
  <p id="mensagem-{{retorno}}">{{mensagem}}</p>  
    <form action="cReservas.php">  
      <div>
        <table border="1">  
          <tr>
            <th>Recurso</th>
            <th>Usuário</th>
            <th>Datas e Horários</th>
            <th>Ações</th>
          </tr>
          <tbody>
            {{Reservas}}

          </tbody>
            
        
        </table>
      </div>
        
      <div>
        <br>
        <a href="cFormularioReserva.php"><input type="button" value="Nova Reserva" name="btnNovaReserva"></a>
      </div>
     
    
    </form> 

    <a href="cMenu.php"><input type="button" value="Voltar"></a> 
      
  </section>
</body>
</html>
