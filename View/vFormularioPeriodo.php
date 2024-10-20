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
          <input type="text" name="txtnome" id="txtnome">

          <label for="">Data Inicial: </label>
          <input type="date" name="" id=""> a <input type="date" name="" id="" value="{{Campocadastro}}">
          <input type="submit" value="Salvar">
        </form>

      <a href="cPeriodo.php"><input type="button" value="Cancelar"></a>
  </section>



  
</body>
</html>