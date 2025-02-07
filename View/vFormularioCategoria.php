<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario-Categoia</title>
</head>
<body>
  <header>
      <h1>Categoria do Recurso</h1>
  </header>

  <section>
      <form action="cFormularioCategoria.php">
        {{tipo_tela}}
        <label for="">Nome: </label>
        <input type="text" name="nome" id="txtnome" value="{{Camponome}}">
        <label for="">Descrição: </label>
        <input type="text" name="descricao" id="txtdescricao" value="{{Campodescricao}}">
        <label for="">Ambiente Físico</label> 
        <input type="checkbox" name="ambiente_fisico" id="" {{Campoambiente}}>

        <input type="submit" name="salvar" value="Salvar">
        
      </form>
      
      <p id="mensagen-{{resposta}}" >{{mensagem}}</p>

      <a href="cCategoria.php"><input type="button" value="Voltar"></a>
      
  </section>



  
</body>
</html>
