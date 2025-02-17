<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>vFormularioDisciplina</title>
</head>
<body>
  <header>
    <h1>{{tela}} Disciplina </h1>
  </header>
  <section>
      <form action="cFormularioDisciplina.php">
        {{tipo_tela}}
        <label for="">Nome: </label>
        <input type="text" name="nome" id="txtnome" value="{{Camponome}}">
        <label for="">Curso: </label>
        <input type="text" name="curso" id="txtcurso" value="{{Campocurso}}">
        <label for="">Período: </label>

        <select name="periodo" id="txtperiodo">
          
            {{Periodo}}
        
        </select>
        <input type="submit" name="salvar" value="Salvar">
      </form>
      <p id="mensagem-{{retorno}}">{{mensagem}}</p> 
  </section>
    <footer>
        <a href="cDisciplina.php"><input type="button" value="Voltar"></a>
        
    </footer>
</body>
</html>
