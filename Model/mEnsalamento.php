<?php

function carregar_salas()
{   
    // função que retorna uma lista com todos os dados dos recursos

    // conecxao com o banco
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);
    
    // Executar a consulta
    $resultado = $conecxao->query("SELECT * FROM recurso WHERE codigo_categoria IN (2, 3)");
    //$resultado = $conecxao->query("SELECT * FROM categoria_recurso WHERE ambiente_fisico = 'S' ");

    $todos_dados = [];

    while ($linha = $resultado->fetch_assoc())
    {
        $todos_dados[] = $linha;
    }
    return $todos_dados; // retorna todos os daddos da tabela recurso do banco em formato de lista
   
    
    
}

function filtar($periodo, $disciplina, $sala)
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resultado = $conecxao->query("SELECT * FROM esnsalamento ");
}

function ensalamento($periodo, $disciplina, $sala, $dia_semana, $h_ini, $h_fim )
{
    include 'confg_banco.php';
    $conecxao = new mysqli($servidor, $usuario, $senha, $banco);

    $resultado = $conecxao->query("INSERT INTO ensalamento (dias_semana, hora_inicial, hora_final, codigo_disciplina, codigo_sala) VALUES ('$dia_semana','$h_ini','$h_fim','$disciplina','$sala')");

    return 0;

    

}

// $salas = Carregar_Salas ();

// var_dump($salas);