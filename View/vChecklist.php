<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist</title>

  </head>

<body>
     <header>
      <h1>Checklist</h1>
     </header>

     <section> <!--Inicio Section-->
          <form action="cChecklist.php">
            <div>
                <p>Recurso: {{nomerecurso}}</p>  
                <label for="txtitem">Item:</label>
                <input type="text" name="txtitem" id="txtitem">
                <input type="hidden" name='codigo' value="{{codigo}}">
              </div>

              <div>
                <input type="submit" name="adicionar" value="Adicionar">
              </div>


          </form>
           

            <table border="1">
              <tr>
                <th>Itens</th>
                <th>Ação</th>
              </tr>

              {{itens}}
            </table>
            

            <div>
                <a href="cRecursos.php"><input type="button" value="Voltar"></a>
            </div>


     </section>  
    
    <script src="script.js"></script>
</body>
</html>