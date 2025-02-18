<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Disciplina</title>
</head>
<body>
  <header>
      <h1>Disciplina</h1>
  </header>

  <section>
    <table border="1">
      <tr>
        <th>Nome</th>
        <th>Operações</th>
        
      </tr>
      <tbody>
        {{disciplinas}}
      </tbody>
          

      
  </table>
  <p id="{{resp}}">{{msg}}</p>


    <a href="cFormularioDisciplina.php"><input type="button" value="Nova Disciplina"></a> 
    <a href="cMenu.php"><input type="button" value="Voltar"></a>
  </section>



  
</body>
</html>
