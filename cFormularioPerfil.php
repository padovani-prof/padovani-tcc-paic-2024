<?php
include_once 'Model/mFuncionalidade.php';  
$funcionalidades = listar_funcionalidades();
                
// Inicializa a variável que conterá o HTML dos checkboxes
$aux = ""; 
if(is_array($funcionalidades)){
    foreach ($funcionalidades as $func) {
        $aux .= "<input type='checkbox' name='funcionalidades[]' value='" . $func . "'> " . $func . "<br>";
    }
} else {
    $aux .= "<input type='checkbox' name='funcionalidades'> Não há nenhuma funcionalidade cadastrada <br>";
}

// Carrega o formulário
$html = file_get_contents('View/vFormularioPerfil.php');
$html = str_replace('{{funcionalidades}}', $aux, $html);
echo $html;
?>
 