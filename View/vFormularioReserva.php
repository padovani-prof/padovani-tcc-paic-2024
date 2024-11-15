<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nova Reserva</title>
</head>
<body>
  <header>
      <h1>Nova Reserva</h1>
  </header>

  <section>
    
    <form action="cFormularioReserva.php">  
        <label for="">Recurso: </label>
        <select name="recurso" id="recurso">
          {{Recursos}}
        </select>

        <label for="">Agendado por: </label>
        <select name="usuario_agendador" id="usuario_agendador">
          {{Usuarios}}
        </select>

        <p>
          <label for="">Justificativa: </label>
          <input type="text" name="justificativa" id="justificativa" value="{{Campojustificativa}}">
        </p>

        <table border="1">
            <tr>
              <th>Data</th>
              <th>Ação</th>
            </tr>
            {{Datas Reservas}}
        </table>

        Nova Data: 
        <p> 
          
        <input type="hidden" name="datas" value="{{d}}">
        <input type="hidden" name="horarios_iniciais" value="{{hr_inicial}}">
        <input type="hidden" name="horarios_finais" value="{{hr_final}}">

          <label for="">Data: </label>
          <input type="date" name="data" id="data">
          <label for="">Hora: </label>
          <input type="time" name="hora_inicial" id=""> - <input type="time" name="hora_final" id="">

          <input type="hidden" name="lista_periodos" value="<?php ($lista_periodos); ?>">
        </p>


        <p>
          <input type="submit" value="Adicionar" name="btnAdicionar"> 
        </p>

        <p>
          <label for="">Agendado para: </label>
          <select name="usuario_utilizador" id="usuario_utilizador">
            {{Usuarios}}
          </select>
        </p>


     
        <p id="mensagem-{{retorno}}">{{mensagem}}</p>  

        <p>
          <input type="submit" value="Salvar" name="btnSalvar"> 
        </p>
    </form> 



    <a href="cReservas.php"><input type="button" value="Voltar"></a>
      
  </section>
</body>
</html>