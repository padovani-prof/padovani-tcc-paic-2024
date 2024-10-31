
<?php 


$html = file_get_contents('View/vFiltroDisponibildade.php');
include_once 'Model/mCategoriaRecurso.php';
include_once 'Model/mRecurso.php';

function remover_rc($chave, $lista_dados)
{
   for ($i=0; $i < count($lista_dados) ; $i++) 
   { 
      if ($lista_dados[$i] != 0)
      {
         $dado = explode(',', $lista_dados[$i]);
         if($chave == $dado[1])
         {
            unset($lista_dados[$i]);
            $lista_dados = array_values($lista_dados);
         }

      }
      
   }
   return $lista_dados;
}


function remover($dados, $removida)
{
   for ($i=0; $i < count($dados); $i++) { 
      if($dados[$i] != 0)
      {
         $quebra = explode(',', $dados[$i]);
         for ($d=0; $d < count($removida); $d++) { 
            if ($quebra[1] == $removida[$d]['nome'])
            {
               unset($removida[$d]);
               $removida = array_values($removida);

            }
         }
      }
   }
   return $removida;
}




$recursoR_categoG  = '';
$periodo_tabe = '';

$dados_recurso = '';
$dados_catego = '';

$categoria0 = '';
$recurso0 = '';

$mensagem = '';
$id_mensa = 'erro';


$categorias = carrega_categorias_recurso();
$recursos = Carregar_recursos();




$dados_cate = '';
$dados_recu = '';






if(isset($_GET['btnCategoria']) or isset($_GET['btnRecursos']) or isset($_GET['btnPeriodos']) or isset($_GET['apagar']))
{
   $lista_informação = $_GET['dados-categoria'];
   $lista_informação_recu = $_GET['dados-recurso'];
   $lista_informação_peri = $_GET['periodo-salvos'];

   if (isset($_GET['apagar']))
   {
      $lista_informação = remover_rc($_GET['apagar_d'],$lista_informação);

      if (count($lista_informação)==0)
      {
         $categoria0 = '<input type="hidden" value="0" name="dados-categoria[]">';
      }

      $lista_informação_recu = remover_rc($_GET['apagar_d'],$lista_informação_recu);

      if (count($lista_informação_recu)==0)
      {
         $recurso0 = '<input type="hidden" value="0" name="dados-recurso[]">';

      }
      $id_mensa = 'sucesso';
      $mensagem = 'Recurso removido';
      
   }


   if (isset($_GET['dados-categoria']))
   {
   
      if(isset($_GET['btnCategoria']) and count($lista_informação) != count($categorias ))
      {
         
         if(mb_strlen( $lista_informação[0]) == 1 )
         {
            unset($lista_informação[0]);
            $lista_informação = array_values($lista_informação);
         }
         $lista_informação[] = $_GET['categoria'];
         $id_mensa = 'sucesso';
         $mensagem = 'Nova categoria adicionada';
      }
   
      $iforma_c = '';
    
      for ($i=0; $i < count($lista_informação); $i++) 
      { 
         $iforma_c = $iforma_c.'<input type="hidden" value="'. $lista_informação[$i].'" name="dados-categoria[]">';
      }
      $html = str_replace('{{dados-catego}}', $iforma_c, $html);
   }
   
   
   if(isset($_GET['dados-recurso']))
   {
      
      if (isset($_GET['btnRecursos']) and count($lista_informação_recu) != count($recursos))
      {
         if(mb_strlen( $lista_informação_recu[0]) == 1)
         {
            unset($lista_informação_recu[0]);
            $lista_informação_recu = array_values($lista_informação_recu);
         }
         $lista_informação_recu[] = $_GET['recurso'];
         $id_mensa = 'sucesso';
         $mensagem = 'Novo recurso adicionado';
      }

      
      
      // mandar dados e remover os dados que estão nas listas que foram adicionados

      $categorias = remover($lista_informação, $categorias);
      $recursos = remover($lista_informação_recu, $recursos);
    
     
     
      
      $iforma_c = '';

      for ($i=0; $i < count($lista_informação_recu); $i++) 
      { 
         $iforma_c = $iforma_c.'<input type="hidden" value="'. $lista_informação_recu[$i].'" name="dados-recurso[]">';
      }
      $html = str_replace('{{dados-recurso}}', $iforma_c, $html);

   }

      
   

   // carrega recurso ou categorias adicionados

   $recursoR_categoG = '<form action="cResultadoDisponibilidade.php">';

   $maior = max(count($lista_informação), count($lista_informação_recu));

   for ($i=0; $i < $maior; $i++) 
   { 
      if ($i < count($lista_informação))
      {
         if($lista_informação[$i]!=0)
         {
            $dado = explode(',',$lista_informação[$i]);
            $recursoR_categoG = $recursoR_categoG.'<tr>';
            $recursoR_categoG = $recursoR_categoG.'<td>'.$dado[0].': '.$dado[1].'</td>'.'<td> '.'<input name="apagar_d" type="hidden" value="'.$dado[1].'"><input type="submit" name="apagar" value="Remover"></td>';    
            $recursoR_categoG = $recursoR_categoG.'<tr/>';
         }

      }
      if ($i < count($lista_informação_recu))
      {
         if($lista_informação_recu[$i]!=0)
         {
            $dado = explode(',',$lista_informação_recu[$i]);
            $recursoR_categoG = $recursoR_categoG.'<tr>';
            $recursoR_categoG = $recursoR_categoG.'<td>'.$dado[0].': '.$dado[1].'</td>' .'<td> '.'<input name="apagar_d" type="hidden" value="'.$dado[1].'">
            <input type="submit" name="apagar" value="Remover"></td>'; 
            $recursoR_categoG = $recursoR_categoG.'<tr/>';
         }
      }
      $recursoR_categoG = $recursoR_categoG .'</form>';
      
   }
   // periodos
   if(isset($_GET['btnPeriodos']))
   {
      // verificar informações condisetes
      
      $data_d = $_GET['p-data'];
      $hora_ini_d = $_GET['p-hora-ini'];
      $hora_fim_d = $_GET['p-hora-fim'];


      if(mb_strlen($_GET['p-data'])>=10 and mb_strlen($_GET['p-hora-ini'])>=5 and  mb_strlen($_GET['p-hora-ini'])>=5)
      {
         

         $dataUsu = new DateTime($_GET['p-data']);
         $dataAtual = new DateTime();

         

        
         if($dataUsu>=$dataAtual)
         {
            $horaini = explode(':', $_GET['p-hora-ini']);
            $horafim = explode(':',  $_GET['p-hora-fim']);

            if($horaini[0]==$horafim[0] and $horaini[1] < $horafim[1] or $horaini[0]<$horafim[0])
            {
               if($lista_informação_peri[0]==0)
               {
                  $lista_informação_peri[0] = $_GET['p-data'];
                  $lista_informação_peri[] = $_GET['p-hora-ini'];
                  $lista_informação_peri[] = $_GET['p-hora-fim'];
               }
               else
               {
                  $lista_informação_peri[] = $_GET['p-data'];
                  $lista_informação_peri[] = $_GET['p-hora-ini'];
                  $lista_informação_peri[] = $_GET['p-hora-fim'];
               }
               $id_mensa = 'sucesso';
               $respo = 0;
               $data_d = '';
               $hora_ini_d = '';
               $hora_fim_d = '';

            }
            else
            {
               $respo = 3;
            }
            

         }
         else
         {
            $respo = 2;

         }

      }
      else
      {
         $respo = 1;
         
      }
      $lmensagem = ['Periodo adicionado com sucesso','Preencha todos os campos', 'Data do periodo ínvalida','Horario do periodo ínvalido'];

      $mensagem = $lmensagem[$respo];
      $html = str_replace('{{data}}',$data_d, $html);
      $html = str_replace('{{hora_ini}}',$hora_ini_d, $html);
      $html = str_replace('{{hora_fim}}',$hora_fim_d, $html);

      

     
   }

   
   $periodos = '<tbody>';
   for ($i=0; $i < count($lista_informação_peri); $i=$i+3) 
   { 
      
      if($lista_informação_peri[$i]!=0)
      {
         $periodos = $periodos.  '<input type="hidden" value=" ' . $lista_informação_peri[$i]  .'" name="periodo-salvos[]">';
         $periodos = $periodos.  '<input type="hidden" value=" ' . $lista_informação_peri[$i+1]  .'" name="periodo-salvos[]">';
         $periodos = $periodos.  '<input type="hidden" value=" ' . $lista_informação_peri[$i+2]  .'" name="periodo-salvos[]">';

         $data = explode('-',$lista_informação_peri[$i]);

         $periodo_tabe = $periodo_tabe.'<tr>';
         $periodo_tabe = $periodo_tabe.'<td>'. $data[2].'/'.$data[1]. '/'. $data[0].'  '.$lista_informação_peri[$i+1].' - '.$lista_informação_peri[$i+2].'</td>';
         $periodo_tabe = $periodo_tabe.'<tr/>';



      }
      else
      {

         $periodos = $periodos.  '<input type="hidden" value=" ' . $lista_informação_peri[$i]  .'" name="periodo-salvos[]">';
      }
   }  $periodos = $periodos.'</tbody>';



}
else
{
   $periodos =  '<input type="hidden" value="0" name="periodo-salvos[]">';
   $categoria0 = '<input type="hidden" value="0" name="dados-categoria[]">';
   $recurso0 = '<input type="hidden" value="0" name="dados-recurso[]">';
}



$maior_arrey = max(count($categorias), count($recursos));

for ($i=0; $i <$maior_arrey ; $i++) 
{ 
   $dados_cate = $dados_cate.($i < count($categorias)?'<option value="'. 'Categoria'.','.$categorias[$i]['nome'].','.$categorias[$i]['codigo'].'">'.$categorias[$i]['nome'] .'</option>':'');

   $dados_recu = $dados_recu.($i<count($recursos)?'<option value="'. 'Recurso'.','.$recursos[$i]['nome'].','.$recursos[$i]['codigo'].'">'.$recursos[$i]['nome'] .'</option>':'');
}




$html = str_replace('{{op-recurso}}', $dados_recu, $html);
$html = str_replace('{{op-categoria}}', $dados_cate, $html);

$html = str_replace('{{RecursoCategoria}}',$recursoR_categoG, $html);
$html = str_replace('{{dados-catego}}',$dados_catego, $html);
$html = str_replace('{{dados-recurso}}',$dados_recurso, $html);

$html = str_replace('{{dados-catego0}}',$categoria0 , $html);
$html = str_replace('{{dados-recurso0}}',$recurso0, $html);

$html = str_replace('{{peridos-salvos}}',$periodos, $html);
$html = str_replace('{{Períodos}}',$periodo_tabe, $html);

$html = str_replace('{{mensagem}}',$mensagem, $html);
$html = str_replace('{{retorno}}',$id_mensa, $html);




echo $html;
?>
