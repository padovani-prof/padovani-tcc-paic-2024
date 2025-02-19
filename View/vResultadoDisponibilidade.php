<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultado Disponibilidade</title>
</head>
<body>
  <header>
      <h1>Disponibilidade</h1>
  </header>

  <section>
    <div>
      <form action="cResultadoDisponibilidade.php">
        <table border="1">
          <tr>
            <th>Recurso</th>
            {{Colunas}}
          </tr>
          <tbody>
            {{Disponibilidades}}
          </tbody>

          {{cate}}

          {{recu}}

          {{periodo}}
          
          
      </table>
      <p id='erro'>{{msg}}</p>
      <input type="submit" value="Reservar" name="reserva">
      </form>
      
    </div>    
     
      <a href="cFiltroDisponibildade.php"><input type="button" value="Voltar"></a>
      
  </section>

</body>
</html>
