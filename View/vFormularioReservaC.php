<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reserva Conjunta</title>
</head>
<body>
   {{cabecario}}

  <section>
    
    <form action="cReservas.php">  
      <div>
        <table border="1">  
          <tr>
            <th>Reservas</th>
            <th>Data e Horário</th>
          </tr>
        
            {{Reserva Conjunta}}
        
        </table>
      </div>

        <label for="">Agendado por: </label>
        <select name="" id="">
            
        </select>
        <p>
          <label for="">Justificativa: </label>
          <input type="text" name="" id="" value="{{Campojustificativa}}">
        </p>

        <label for="">Agendado por: </label>
        <select name="" id="">
            
        </select>
        
        <p>
          <input type="submit" value="Reserva" name="btnReserva"> 
        </p>
     
    <p id="mensagem-{{retorno}}">{{mensagem}}</p>  
    </form> 

    <a href="cFiltroDisponibilidade.php"><input type="button" value="Voltar"></a> <!--FiltoDisnonibilidade ou vReservasd?-->
      
  </section>



  
</body>
</html>