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
        <select name="" id="">

        </select>
        <label for="">Disciplina: </label>
        <select name="" id="">

        </select>

        <label for="">Sala: </label>
        <select name="" id="">

        </select>
        <p>Dias da Semana</p>
        <input type="checkbox" name="DiaSemana[]" id="">
        <label for="">Segunda</label>
        <input type="checkbox" name="DiaSemana[]" id="">
        <label for="">Terça</label>
        <input type="checkbox" name="DiaSemana[]" id="">
        <label for="">Quarta</label>
        <input type="checkbox" name="DiaSemana[]" id="">
        <label for="">Quinta</label>
        <input type="checkbox" name="DiaSemana[]" id="">
        <label for="">Sexta</label>
        <input type="checkbox" name="DiaSemana[]" id="">
        <label for="">Sábado</label>
        <input type="checkbox" name="DiaSemana[]" id="">
        <label for="">Domingo</label>

        <div>
          <label for="">Horário: </label>
          <input type="time" name="" id=""> -
          <input type="time" name="" id="">
          <input type="submit" value="Salvar">
        </div>
         
        <p id="mensagem-{{retorno}}">{{mensagem}}</p> 
    </form>

    

    
    <a href="cEnsalameno.php"><input type="button" value="Cancelar"></a>
  </section>

   

    
    
    <script src="script.js"></script>
</body>
</html>