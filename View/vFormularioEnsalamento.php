<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu</title>

</head>
<body>
  <header>
      <h1>Novo Ensalamento</h1>
  </header>
  <section>
    <form action="cFormularioEnsalamento.php">
        <label for="">Perído:</label>
        <select name="periodo" id="txtperiodo">
          {{Periodo}}

        </select>
        <label for="">Disciplina: </label>
        <select name="disciplina" id="txtdisciplina">
          {{Disciplina}}

        </select>

        <label for="">Sala: </label>
        <select name="sala" id="txtsala">
          {{Sala}}

        </select>
        <p>Dias da Semana</p>
        <input type="checkbox" name="DiaSemana[]" value="1">
        <label for="">Domingo</label>
        <input type="checkbox" name="DiaSemana[]" value="2">
        <label for="">Segunda</label>
        <input type="checkbox" name="DiaSemana[]" value="3">
        <label for="">Terça</label>
        <input type="checkbox" name="DiaSemana[]" value="4">
        <label for="">Quarta</label>
        <input type="checkbox" name="DiaSemana[]" value="5">
        <label for="">Quinta</label>
        <input type="checkbox" name="DiaSemana[]" value="6">
        <label for="">Sexta</label>
        <input type="checkbox" name="DiaSemana[]" value="7">
        <label for="">Sábado</label>
        

        <div>
          <label for="">Horário: </label>
          <input type="time" name="h_inicio" id="hora_inicio"> -
          <input type="time" name="h_fim" id="hora_fim">
          <input type="submit" name="salvar" value="Salvar">
        </div>
         
        <p id="mensagem-{{retorno}}">{{mensagem}}</p> 
    </form>

    

    
    <a href="cEnsalamento.php"><input type="button" value="Cancelar"></a>
  </section>

   

    
    
    <script src="script.js"></script>
</body>
</html>
