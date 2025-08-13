<?php

include_once 'Model/mVerificacao_acesso.php';
include 'cGeral.php';
Esta_logado();




include_once 'Model/mPeriodo.php';
$lista_de_periodos = carrega_periodo();

$peri = '';
$nome = '';
$curso = '';
$mens = '';
$tipo_tela = '';
$id_resp = 'nada';
$tela = 'Cadastrar';
$marcar = '';

include 'Model/mDisciplina.php';
if(isset($_GET['codigo']) or isset($_GET['cod'])){

    // atualizar
    verificação_acesso($_SESSION['codigo_usuario'], 'alt_disciplina', 2);
    $id_resp = 'danger';
    $tela = 'Atualizar';
    if(isset($_GET['codigo'])){

        $chave = $_GET['codigo'];
        
        $dados = mandar_informações($chave, 'disciplina');
        $tipo_tela = '<input type="hidden" name="cod" value="'.$chave.'">';
        $nome = $dados['nome'];
        $curso = $dados['curso'];
        $peri = $dados['codigo_periodo'];
        $marcar = $peri;

    }else{
        
        $chave = $_GET['cod'];
        $tipo_tela = '<input type="hidden" name="cod" value="'.$chave.'">';
        $nome = $_GET['nome'];
        $curso = $_GET['curso'];
        $peri = $_GET['periodo'];
        $marcar = $peri;
        $resp = atualizar_disciplina($chave, $nome, $curso, $peri);

        if($resp==0)
        {
            $marcar = '';
            $peri = '';
            $nome = '';
            $curso = '';
            $id_resp = 'success';
        }
    

        $lMensa = ['Disciplina Atualizada com Sucesso!!', 'Nome do curso inválido','Nome da diciplina inválido', 'Nome da Diciplina e Périodo já está cadastrado.', 'Adicione um périodo.'];


        $mens = $lMensa[$resp];

    }
    
}

else if(isset($_GET['salvar']))
{
    $id_resp = 'danger';
    $nome = $_GET['nome'];
    $curso = $_GET['curso'];
    $peri = $_GET['periodo'];
    $marcar = $peri;
    $resp = insere_disciplina($nome, $curso, $peri);

    if($resp==0)
    {
        $marcar = '';
        $peri = '';
        $nome = '';
        $curso = '';
        $id_resp = 'success';
    }
 

    $lMensa = ['Disciplina Cadastrada com Sucesso!!', 'Nome do curso inválido','Nome inválido', 'Diciplina de mesno Nome e Périodo Existente','Adicione um périodo.'];


    $mens = $lMensa[$resp];
}else{
    // cadastrar
    verificação_acesso($_SESSION['codigo_usuario'], 'cad_disciplina', 2);

}


$op = mandar_options($lista_de_periodos, $marcar);

$html = file_get_contents('View/vFormularioDisciplina.php');
$html = cabecalho($html, 'Disciplina');
$html = str_replace('{{Camponome}}', $nome, $html);
$html = str_replace('{{Campocurso}}', $curso, $html);
$html = str_replace('{{Periodo}}', $op, $html);
$html = str_replace('{{mensagem}}', $mens, $html);
$html = str_replace('{{tipo_tela}}', $tipo_tela, $html);
$html = str_replace('{{retorno}}', $id_resp, $html);
$html = str_replace('{{tela}}', $tela, $html);

echo $html;
?>






