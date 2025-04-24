<?php 

function lista_usuario_acesso($chave_usu) {
    include 'confg_banco.php';
    
    $cone = new mysqli($servidor, $usuario, $senha, $banco);
    
    if ($cone->connect_error) {
        die("Falha na conexão: " . $cone->connect_error);
    }

    // Preparar a consulta SQL para evitar SQL Injection
    $sql = "
    SELECT f.codigo, f.sigla, f.descricao
    FROM sgrp.funcionalidade f
    WHERE f.codigo IN (
        SELECT fp.codigo_funcionalidade
        FROM sgrp.funcionalidade_perfil fp
        WHERE fp.codigo_perfil IN (
            SELECT up.codigo_perfil
            FROM sgrp.usuario_perfil up
            WHERE up.codigo_usuario = ?
        )
    );";
    
    // Preparar a consulta
    $stmt = $cone->prepare($sql);
    
    // Vincular o parâmetro (chave_usu) à consulta
    $stmt->bind_param("i", $chave_usu);
    
    // Executar a consulta
    $stmt->execute();
    
    // Obter o resultado da consulta
    $resultado = $stmt->get_result();
    
    // Buscar os resultados como um array associativo
    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);
    
    // Fechar a conexão
    $stmt->close();
    $cone->close();

    return $resultado;
}








?>
