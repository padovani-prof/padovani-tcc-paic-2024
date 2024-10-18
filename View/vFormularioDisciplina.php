<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>vFormularioDisciplina</title>
</head>
<body>
  <header>
    <h1>Cadastrar Disciplina </h1>
  </header>
  <section>
      <form action="cFormularioDisciplina.php">
        <label for="">Nome: </label>
        <input type="text" name="nome" id="txtnome" value="{{Camponome}}">
        <label for="">Curso: </label>
        <input type="text" name="curso" id="txtcurso" value="{{Campocurso}}">
        <label for="">Período: </label>

        <select name="periodo" id="txtperiodo">
          <option value="">
            {{Período}}
          </option>
        </select>
        <a href=""><input type="button" value="Salvar"></a>
      </form>
      <p id="mensagem-{{retorno}}">{{mensagem}}</p> 
  </section>
    <footer>
        <a href="cDisciplina.php"><input type="button" value="Cancelar"></a>
        
    </footer>
</body>
</html>