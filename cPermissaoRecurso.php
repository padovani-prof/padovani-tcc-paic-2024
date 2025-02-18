<?php 


include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'adm_perm_recurso', 2);
# vai mandar o codi usuario e o codigo que aquela fucionalidade pertence
include_once 'Model/mPermissao.php';
$recurso_codigo  = $_GET['codigo_recurso'];
Existe_essa_chave_na_tabela($recurso_codigo , 'recurso', 'cRecursos.php');// verifica se existe a chave do recurso
$html = file_get_contents('View/vPermissao.php');



$marcar = '';
$id_msg = 'erro';
$msg = '';
$hora_ini = '';
$hora_fim = '';
$data_ini = '';
$data_fim = '';
$tabela = '';


if(isset($_GET['salvar'])){
    // VERIFICAR DADOS
    $marcar = $_GET['perfio_usuario'];
    $hora_ini = $_GET['hora_ini'];
    $hora_fim = $_GET['hora_fim'];
    $semanas = dias_da_semana();
    $data_ini = $_GET['data_ini'];
    $data_fim = $_GET['data_fim'];

   

    if($marcar != 'NULL' and mb_strlen($hora_ini)==5  and mb_strlen($hora_fim)==5 and mb_strlen($data_ini)==10 and $semanas !=''){
        
        date_default_timezone_set('America/Manaus'); 
        $periodo_H_ini = new DateTime("$data_ini $hora_ini");
        $periodo_H_fim = new DateTime("$data_ini $hora_fim");
        $data_atual = new DateTime();
        $data_ine = new DateTime("$data_ini 23:59");
        if($periodo_H_ini > $periodo_H_fim ){
            
            // horario inicial invalido
            $msg = 'Não podemos cadastrar o horario final que seja aterior ao horario inicial.';

        }else if($data_atual > $data_ine){
            
            // data inicial invalida
            $msg = 'Não podemos cadastrar uma data inicial que seja anterior a hoje.';
        }
        else{
            $data_ine = new DateTime("$data_ini 00:00");
            $periodo_D_fim = new DateTime("$data_fim 23:59");
            if(mb_strlen($data_fim)==10 and $periodo_D_fim < $data_ine){
                    // verificação da data final
                    $msg = 'Não podemos cadastrar uma data final que seja anterior a hoje ou que seja anterior a data inicial.';   
            }else{
                cadastra_acesso_recurso($recurso_codigo, $marcar, $hora_ini, $hora_fim, $semanas, $data_ini, $data_fim);
        
                $msg = 'Acesso ao recurso cadastrado com sucesso.';
                $id_msg = 'sucesso';

                $hora_ini = '';
                $hora_fim = '';
                $data_ini = '';
                $data_fim = '';
                $marcar = '';
            }
            
        }
    }else{
        // todos os dados não foram preechidos
        $msg = 'Por favor peenxa todos os dados que são exenciais para o cadastro.';
    }
    if($id_msg=='erro' and strlen($semanas) !=0){
        $html = marcar_semana($semanas, $html);
        // se foi marcado a semana
        
    }
}else if(isset($_GET['apagar'])){
    // apagar
    verificação_acesso($_SESSION['codigo_usuario'], 'adm_perm_recurso', 2);
    $chave_ac = $_GET['codigo_acesso_ao_recurso'];
    apagar_acesso_ao_recurso($chave_ac);
    $msg = 'Acesso ao recurso removido com sucesso.';
    $id_msg = 'sucesso';
}



$nome_recurso =  nome_recurso($recurso_codigo); 
$tabela = Tabela_acesso_recurso_carrega($recurso_codigo);
$opt = opition($marcar);

$html = str_replace('{{permissoes}}', $tabela, $html);
$html = str_replace('{{perfis}}',$opt, $html);
$html = str_replace('{{nomerecurso}}', $nome_recurso, $html);

$html = str_replace('{{rep}}', $id_msg, $html);
$html = str_replace('{{mensagemAnomalia}}', $msg, $html);
$html = str_replace('{{codigorecurso}}', $recurso_codigo, $html);

$html = str_replace('{{horaInicial}}', $hora_ini, $html);
$html = str_replace('{{horaFinal}}}', $hora_fim, $html);
$html = str_replace('{{dataIni}}}', $data_ini, $html);
$html = str_replace('{{dataFinal}}}', $data_fim, $html);

echo $html;
?>

