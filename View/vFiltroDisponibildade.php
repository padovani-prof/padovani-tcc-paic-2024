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

    <form action="cFiltroDisponibildade.php">
      <div>
        <table border="1">
          <tr>
            <th>Categoria ou Recurso</th>
            <th>Ação</th>
          </tr>
          <tbody>
            {{RecursoCategoria}}
          </tbody>
        
        </table>
        
      </div>

      <label for="">Categoria: </label>
      <select name="categoria" id="">
        {{op-categoria}}

      </select>
      {{dados-catego-recu}}
      <input type="submit" value="Adicionar" name="btnCategoria">

      <label for="">Recurso:</label>
      <select name="recurso" id="">

        {{op-recurso}}

      </select>
      <input type="submit" value="Adicionar" name="btnRecursos">




    <div>
      <table border="1">
        <tr>
          <th>Período</th>
          <th>Ação</th>
        </tr>
        <tbody>
          {{Períodos}}
        </tbody>
          

      </table>
      <tr>
          <td> Data: </Data><input type="date" name="p-data" id="" value="{{data}}"></td>
        </tr>
        <tr>
          <td>Horário Local:
            <input type="time" name="p-hora-ini" id="" value="{{hora_ini}}">
         </td>
        </tr>
        <tr>
          <td>Horário Final:
             <input type="time" name="p-hora-fim"  id="" value="{{hora_fim}}">
          </td>
        </tr>
    </div>

      {{peridos-salvos}}

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

