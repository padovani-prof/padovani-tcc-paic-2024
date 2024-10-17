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
                <input type="time" name="hora_ini" value="{{horaInicial}}" id="">
                a
                <input type="time" name="hora_fim" value="{{horaFinal}}}" id="">
              </div>
              
          
              <div>
                  <label for="">Dia da Semana</label>
                  <input type="checkbox" name="domi" {{marca1}} id="">D
                  <input type="checkbox" name="segu" {{marca2}} id="">S
                  <input type="checkbox" name="terc" {{marca3}} id="">T
                  <input type="checkbox" name="quar" {{marca4}} id="">Q
                  <input type="checkbox" name="quin" {{marca5}} id="">Q
                  <input type="checkbox" name="sext" {{marca6}} id="">S
                  <input type="checkbox" name="saba" {{marca7}} id="">S
              </div>
              <label for="">Data Inicial:</label>
              <input type="date" name="data_ini" value="{{dataIni}}}" id="#">
              <label for="">Data Final:</label>
              <input type="date" name="data_fim" value="{{dataFinal}}}" id="#">
              

              <input type="hidden" name="codi_recurso" value="{{codigo_recurso_atual}}">

              <div>
                <input type="submit" name="salvar" value="Salvar">
              </div>
              
          </form>

            <p> {{mensagemAnomalia}} </p>

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