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
      <form action="">
        <table border="1">
          <tr>
            <th>Recurso</th>
            {{Colunas}}
          </tr>
          <tbody>
            {{Disponibilidades}}
          </tbody>
          
      </table>
      <input type="submit" value="Reservar">
      </form>
      
    </div>    
     
      <a href="cMenu.php"><input type="button" value="Voltar"></a>
      
  </section>



  
</body>
</html>
