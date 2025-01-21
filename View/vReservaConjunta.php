<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Conjunta</title>
</head>
<body>

    <h1>Reserva Conjunta</h1>
    <form action="cReservaConjunta.php">

        <div>
            <table border="1">
            <tr>
                <th>Reservas</th>
                <th>Data e Horário</th>
            </tr>
            <tbody>
                {{reservas}}
            </tbody>
            
            </table>
            
        </div>
        <label for="">Agendado por: </label>
            <select name="agendador" id="">
                {{agendador}}
        </select><br>

        <label for="">Justificativa: </label>
        <input type="text" name="justfc"><br>

        <label for="">Agendado para:</label>
            <select name="utilizador" id="">
            {{usuario}}
        </select>
        {{dados}}
        <div>
            <input type="submit" name="reservar" value="Reservar">
        </div>
    </form>
    <a href="cFiltroDisponibildade.php"><input type="button" value="Voltar"></a> <!--FiltoDisnonibilidade ou vReservasd?-->

</body>
</html>
