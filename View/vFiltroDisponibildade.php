<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Filtro Disponibilidade</title>
</head>
<body>
  <header>
      <h1>Disponibilidade</h1>
  </header>

  <section>

    <form action="cResultadoDisponibilidade.php">
      <div>
        <table border="1">
          <tr>
            <th>Categoria ou Recurso</th>
          </tr>
        
            {{RecursoCategoria}}
        
        </table>
        
      </div>

      <label for="">Categoria: </label>
      <select name="" id="">

      </select>
      <input type="submit" value="Adicionar" name="btnCategoria">
      <label for="">Recurso:</label>
      <select name="" id="">

      </select>
      <input type="submit" value="Adicionar" name="btnRecursos">
    
    
    <div>
      <table border="1">
        <tr>
          <th>Período</th>
        </tr>
          {{Períodos}}
        <tr>
          <td> Data: </Data><input type="date" name="" id=""></td>
        </tr>
        <tr>
          <td>Horário Local:
            <input type="time" name="" id="">
         </td>
        </tr>
        <tr>
          <td>Horário Final:
             <input type="time" name="" id="">
          </td>
        </tr>
      </table>
    </div>


      <label for="">Categoria: </label>
      <select name="" id="">

      <input type="submit" value="Adicionar" name="btnPeriodos">
        <div>
          <input type="submit" value="Consultar" name="btnConsultar">
        </div>
      
    <p id="mensagem-{{retorno}}">{{mensagem}}</p>  
    </form> 
    <a href="cMenu.php"><input type="button" value="Voltar"></a>
      
  </section>



  
</body>
</html>