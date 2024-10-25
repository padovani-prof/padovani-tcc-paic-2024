
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario Período</title>
  
</head>
<body>
  <header>
      <h1>Cadastrar Período</h1>
  </header>

  <section>
      <form action="cFormularioPeriodo.php">
          <label for="">Nome: </label>
          <input type="text" name="txtnome" id="txtnome" value="{{nomePeriodo}}">

          <label for="">Data Inicial: </label>
          <input type="date" name="data_ini" id="" value="{{dataIni}}"> a <input type="date" name="data_fim" id="" value="{{dataFim}}">
          <input type="submit" name="salvar" value="Salvar">
        </form>

         <p id="{{msg}}">{{mensagem}}</p>

      <a href="cPeriodo.php"><input type="button" value="Voltar"></a>
  </section>



  
</body>
</html>
