<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Recurso</title>

</head>
<body>

     <header>
      <h1>Recursos</h1>
     </header>
     
     <section> <!--Inicio Section-->
        <form action="cNovoRecurso.php">
          <label for="">Nome:</label>
          <input type="text" name="nome" value = "{{campoNome}}" id="">
          <label for="">Descrição: </label>
          <input type="text" name="descricao" value="{{campoDescricao}}" id="">
          <label for="">Categoria</label>
          <select name="categoria" id="">
            
            {{categoriarecurso}}
          
          </select>
          <p id="mensagem-{{retorno}}" >{{mensagem}}</p>


        
        <a href="cRecursos.php"><input type="button" value="Cancelar"></a>
        <input type="submit" value="Salvar">
       

        </form>
        
                 
      </section>  
    
    <script src="script.js"></script>
</body>
</html>
