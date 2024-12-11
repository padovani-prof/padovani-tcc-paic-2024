<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu</title>

</head>
<body>
  <header>
      <h1>Ensalamento</h1>
  </header>
  <section>
    <form action="cEnsalamento.php">
        <label for="">Período: </label>
        <select name="periodo" id="txtperiodo">
          {{periodo}}

        </select>
        <label for="">Disciplina: </label>
        <select name="disciplina" id="txtdisciplina">
          {{disciplina}}

        </select>
        <label for="">Sala: </label>
        <select name="sala" id="txtsala">
          {{sala}}

        </select>

        <input type="submit" name="filtrar" value="Filtrar">
        <a href="cFormularioEnsalamento.php"><input type="button" value="Novo"></a>
    </form>

      <table border="1">
        <tr>
          <th>Sala</th>
          <th>Perído</th>
          <th>Disciplina</th>
          <th>Dia da Semana</th>
          <th>Horário</th>
          <th>Apagar</th>
        </tr>
            {{Categoria}}
    </table>

    
    <a href="cMenu.php"><input type="button" value="Voltar"></a>
  </section>

   

    
    
    <script src="script.js"></script>
</body>
</html>