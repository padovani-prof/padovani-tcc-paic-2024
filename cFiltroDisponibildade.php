

<?php 

function mandar_options_dispo($lista, $lado='a'){
   $opt = '<option value="NULL">...</option>';
   foreach($lista as $dados)
      { 
         $opt .= '<option value="'. $dados['codigo'].','.mb_strtoupper($dados['nome']).','.$lado.'">'.mb_strtoupper($dados['nome'] ) .'</option>';    
      }
   return $opt;
}

function remover_lista($lista, $dados , $tipo){
   for ($i=0; $i < count($dados); $i++) { 
      $d = explode(',', $dados[$i]);
      for ($l=0; $l < count($lista); $l++) { 
         if($d[0] == $lista[$l]['codigo'] and $tipo==$d[2]){
            unset($lista[$l]); 
            $lista = array_values($lista);
            
            break;
         }
      }
   }
   
   
   return $lista;
   
}

function tabela_cate_recu($lista){
   $recursos = '';
   // Substitui os recursos no template HTML
  foreach($lista as $dado)
   {
       $d = explode(',', $dado);
       $name = preg_replace("/[^a-zA-Z0-9 ]/", "", $dado);
       $name = str_replace(' ', '', $name);
       $recursos .= '<tr>
         <td>'.$d[1].'</td>
         <td><input  class="btn btn-outline-danger" type="submit" name="'.$name.'" value="Remover"></td>
       </tr>';
      
   }
   return $recursos;


}

function tabela_periodo($lista){
   $recursos = '';
   // Substitui os recursos no template HTML
  for ($i=0; $i < count($lista); $i+=3) { 
      $data = explode('-', $lista[$i]);
      $name = $lista[$i].$lista[$i+1].$lista[$i+2];
       $recursos .= '<tr>
         <td>'.$lista[$i+1].' até '.$lista[$i+2].' de '.$data[2].'/'.$data[1].'/'.$data[0].'</td>
         <td><input type="submit"  class="btn btn-outline-danger" name="'.$name.'" value="Remover"></td>
       </tr>';
      
   }
   return $recursos;


}

function remover_periodo($lista){
   for ($i=0; $i < count($lista); $i+=3) { 
      $name = $lista[$i].$lista[$i+1].$lista[$i+2];
      if(isset($_GET[$name])){
         unset($lista[$i]); 
         unset($lista[$i+1]); 
         unset($lista[$i+2]); 
         $lista = array_values($lista);
         break;

      }
   }
   return $lista;
}


function remove_cate_recu($lista){
   for ($i=0; $i < count($lista) ; $i++) { 
      $name = preg_replace("/[^a-zA-Z0-9 ]/", "", $lista[$i]);
      $name = str_replace(' ', '', $name);
      if(isset($_GET[$name])){
         unset($lista[$i]); 
         $lista = array_values($lista);
         return $lista;

      }
   }
   return $lista;
}


function conflito_de_periodos_adicionados($data, $h_i, $h_f, $lista_periodos){
   $add_periodo_ini = new DateTime("$data $h_i");
   $add_periodo_fim = new DateTime("$data $h_f");
   for ($i=0; $i < count($lista_periodos) ; $i+=3) { 
      $perio_ini = new DateTime($lista_periodos[$i].' '.$lista_periodos[$i+1]);
      $perio_fim = new DateTime($lista_periodos[$i].' '.$lista_periodos[$i+2]);
      if($add_periodo_fim >= $perio_ini and $add_periodo_ini <= $perio_fim){
         return true;
      }
      
   }
   return false;
}

include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cad_categoria_rec', 2);


$usua = ((isset($_GET['utilizador']) and $_GET['utilizador']!= 'NULL')? $_GET['utilizador']:'');

$html = file_get_contents('View/vFiltroDisponibildade.php');

$dados_recu_cate =  isset($_GET['cate_recu'])?json_decode(urldecode($_GET['cate_recu'])):[];
$dados_periodos =  isset($_GET['periodo'])?json_decode(urldecode($_GET['periodo'])):[];

include 'Model/mDisponibilidade.php';
include_once 'Model/mUsuario.php';
$care_recursos = Carregar_recursos_dados();
$carre_categorias = carrega_categorias_recurso();
$usuarios = listar_usuarios();

$msg_id = 'erro';
$msg = '';
$data = '';
$hora_ini = '';
$hora_fim = '';
if(isset($_GET['categoria']) and $_GET['categoria']!='NULL'){
   $cate = $_GET['categoria'];
   $dados_recu_cate[] = $cate;
   $msg = 'Nova categoria adicionada com Sucesso.';
   $msg_id = 'sucesso';

}elseif(isset($_GET['recurso']) and $_GET['recurso']!='NULL'){
   $recu =  $_GET['recurso'];
   $dados_recu_cate[] = $recu;
   $msg = 'Novo recurso adicionado com Sucesso.';
   $msg_id = 'sucesso';
   
}elseif(isset($_GET['btnPeriodos'])){
   // verficar periodo 
   $data = $_GET['p-data'];
   $hora_ini = $_GET['p-hora-ini'];
   $hora_fim = $_GET['p-hora-fim'];

   if(empty($data)){
      $msg = 'Por favor adicione uma data.';

   }elseif(empty($hora_ini)){
      $msg = 'Por favor adicione um horário inicial.';

   }
   elseif(empty($hora_fim)){
      $msg = 'Por favor adicione um horário final.';
      
   }else{
      date_default_timezone_set('America/Manaus'); 
      $data_atual = new DateTime();
      $data_inicial = new DateTime("$data $hora_ini");
      $data_fim = new DateTime("$data $hora_fim");
      if($data_atual > $data_inicial){
         $msg = 'Periodo ínvalido. Não podemos adicionar um periodos inferio a data atual e horário.';
      }elseif ($data_inicial >= $data_fim) {
         $msg = 'Periodo ínvalido. Horario inicial ultrapassou o horário final.';
      }elseif (conflito_de_periodos_adicionados($data, $hora_ini, $hora_fim, $dados_periodos)) {
         $msg = 'Período ínvalido. Conflito com período já adicionado.';
      }
      else {
         $dados_periodos[] = $data;
         $dados_periodos[] = $hora_ini;
         $dados_periodos[] = $hora_fim;

         $msg_id = 'sucesso';
         $msg = 'Periodo adicionado com Sucesso';
         $data = '';
         $hora_ini = '';
         $hora_fim = '';

      }
   }
   
}elseif (isset($_GET['btnConsultar'])) {
   if(count($dados_recu_cate)==0 ){
      $msg = 'Adicione alguma categoria ou recurso.';
      
   }elseif (count($dados_periodos)== 0) {
      $msg = 'Adicione algum periodo.';
   }elseif ($_GET['utilizador']== 'NULL') {
      $msg = 'Selecione o ultilizador para consultar.';
   }
   else {
      // separa os dados e mada pra resulatdo disponibilidade;
      $recu = [];
      $cate = [];
      foreach($dados_recu_cate as $dados){
         $tipo = explode(',', $dados);
         if($tipo[2] == 'c'){
            $cate[] = $tipo[0].','.$tipo[1];
         }else{
            $recu[] = $tipo[0].','.$tipo[1];
         }
      }

      header("Location:  cResultadoDisponibilidade.php?utilizador=".$_GET['utilizador'].'& periodos=' . urlencode(json_encode($dados_periodos)).(((count($recu)> 0)? "& recursos=" . urlencode(json_encode($recu)):'')).(((count($cate)> 0)? "& categorias=" . urlencode(json_encode($cate)):'')));
   
      exit();
   }
}elseif(isset($_GET['cate_recu']) and count($dados_recu_cate) > 0 or isset($_GET['periodo']) and count($dados_periodos)>0){
   // remover 
   $qdt = count($dados_recu_cate);
   $dados_recu_cate = remove_cate_recu($dados_recu_cate);
   $qdt_pe = count($dados_periodos);
   $dados_periodos = remover_periodo($dados_periodos);

   $msg = ($qdt > count($dados_recu_cate))?'Recurso removido.':(($qdt_pe > count($dados_periodos))?'Periodo removido.':'');
   $msg_id = 'sucesso';


   
}

$care_recursos = remover_lista($care_recursos, $dados_recu_cate , 'r');
$carre_categorias = remover_lista($carre_categorias, $dados_recu_cate , 'c');
$tabe_recu_pere = tabela_cate_recu($dados_recu_cate);
$tabe_periodo = tabela_periodo($dados_periodos);
$dados_recu_cate = '<input type="hidden" name="cate_recu" value=" '.urlencode(json_encode($dados_recu_cate)).'">';
$dados_periodos = '<input type="hidden" name="periodo" value=" '.urlencode(json_encode($dados_periodos)).'">';
$opt_recurso = mandar_options_dispo($care_recursos, 'r');
$opt_categoria = mandar_options_dispo($carre_categorias, 'c');

$usuarios = mandar_options($usuarios, $usua);



$html = str_replace('{{usuario}}', $usuarios, $html);
$html = str_replace('{{data}}', $data, $html);
$html = str_replace('{{hora_ini}}', $hora_ini, $html);
$html = str_replace('{{hora_fim}}', $hora_fim, $html);


$html = str_replace('{{retorno}}', $msg_id, $html);
$html = str_replace('{{mensagem}}', $msg, $html);

$html = str_replace('{{Períodos}}', $tabe_periodo, $html);
$html = str_replace('{{peridos-salvos}}', $dados_periodos, $html);
$html = str_replace('{{RecursoCategoria}}', $tabe_recu_pere, $html);
$html = str_replace('{{dados-catego-recu}}', $dados_recu_cate, $html);
$html = str_replace('{{op-categoria}}', $opt_categoria, $html);
$html = str_replace('{{op-recurso}}', $opt_recurso, $html);


echo $html;


?>

