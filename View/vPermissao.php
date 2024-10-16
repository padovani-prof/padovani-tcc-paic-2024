<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permissão</title>

</head>
<body>

  <header>
    <h1>Permissão de Recurso</h1>
  </header>

     <section> <!--Inicio Section-->
     
          <form action="cPermissaoRecurso.php">
              <input type="hidden" name="codigorecurso" value="{{codigorecurso}}">
              <p>Recurso: {{nomerecurso}}</p>

              <div>
                <label for="">Perfil com Acesso: </label>
                <select name="perfio_usuario" id="">
                  {{perfis}}
                </select>
              </div>

              <div>
                <label for="#">Horário:</label>
                <input type="time" name="hora_ini" id="">
                a
                <input type="time" name="hora_fim" id="">
              </div>
              
          
              <div>
                  <label for="">Dia da Semana</label>
                  <input type="checkbox" name="domi" id="">D
                  <input type="checkbox" name="segu" id="">S
                  <input type="checkbox" name="terc" id="">T
                  <input type="checkbox" name="quar" id="">Q
                  <input type="checkbox" name="quin" id="">Q
                  <input type="checkbox" name="sext" id="">S
                  <input type="checkbox" name="saba" id="">S
              </div>
              <label for="">Data Inicial:</label>
              <input type="date" name="data_ini" id="#">
              <label for="">Data Final:</label>
              <input type="date" name="data_fim" id="#">
              

              <input type="hidden" name="codi_recurso" value="{{codigo_recurso_atual}}">

              <div>
                <input type="submit" value="Salvar">
              </div>
              
          </form>

            

          <table border="1">
            <tr>
                <th>Nome</th>
                <th>Horário</th>
                <th>Ação</th>
            </tr>

            {{permissoes}}
            
          </table>
          
          <a href="cRecursos.php"><input type="button" value="Voltar"></a>
     </section>  
    
    <script src="script.js"></script>
</body>
</html>