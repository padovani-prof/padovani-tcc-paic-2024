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
        <label for="">Nome: </label>
        <input type="text" name="nome" id="txtnome" value="{{Camponome}}">
        <label for="">Descrição: </label>
        <input type="text" name="descricao" id="txtdescricao" value="{{Campodescricao}}">
        <label for="">Ambiente Físico</label> 
        <input type="checkbox" name="" id="" {{Campoambiente}}>
        <input type="submit" value="Salvar">
      </form>

      <a href="cCategoria.php"><input type="button" value="Cancelar"></a>
      
  </section>



  
</body>
</html>