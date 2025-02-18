<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos</title>

</head>
<body>

     <header>
      <h1>Recursos</h1>
     </header>
     
     <section> <!--Inicio Section-->

     
        <table border="1">
            <tr>
              <th>Nome</th>
              <th>Operações</th>
              <th>Checklist</th>
              <th>Permissão</th>
            </tr>
            <tbody>
              {{recursos}}
            </tbody> 
            
        </table>
       <p id="resposta-{{repos}}">{{msg}}</p> 

        <div>
          <a href="cMenu.php"><input type="button" value="Voltar"></a>
        </div>
        <div id="bt-nov-recurso">
          <a href="cFormularioRecurso.php"><input type="button" value="Novo Recurso"></a>
        </div>
        

                 
      </section>  

    
    <script src="script.js"></script>
</body>
</html>
