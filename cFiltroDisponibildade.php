

<?php 

include_once 'Model/mVerificacao_acesso.php';
Esta_logado();
verificação_acesso($_SESSION['codigo_usuario'], 'cons_disponibilidade', 2);

$html = file_get_contents('View/vFiltroDisponibildade.php');
include_once 'Model/mCategoriaRecurso.php';
include_once 'Model/mRecurso.php';


$categorias = carrega_categorias_recurso();
$recursos = Carregar_recursos_dados();


$op_recuso = '';
   foreach($recursos as $recurso)
   {
      $op_recuso .='<option value="Recurso,' .$recurso['nome'].',' .$recurso['codigo']  .'">  '.$recurso['nome'].' </option>'; 
   }

$mensagem = '';

$id_erro = 'erro';


function ja_ta_adicionado($cate_recu, $lista_cate_recu)
{
   for ($i=0; $i < count($lista_cate_recu) ; $i++) 
   {
      if($lista_cate_recu[$i] != 0)
      {
         $dado = explode(',',$lista_cate_recu[$i]);
         if($cate_recu == $dado[1])
         {
            return true;
         }
      }
     
   }
   return false;

}

function remover_recurso_categoria($lista_recurso_cate)
{
   try
   {
      if($lista_recurso_cate[0]!=0)
      {
         foreach ($lista_recurso_cate as $i => $item)
         {
            $nameParts = explode(',', $item);
            $name = str_replace(' ','', $nameParts[1]);
            $name = preg_replace("/[^a-zA-Z0-9]/", "", $name);
            if (isset($_GET[$name])) 
            {
               return $i; 
            }
         }

      }
      
   return -1;

   }catch (Exception $e) 
   {
      return -1;
     
  }
   
  
}


function remover_periodo($lista_periodos)
{
   for ($i=0; $i < count($lista_periodos); $i=$i+3) 
   {

      $name = $lista_periodos[$i].$lista_periodos[$i+1].$lista_periodos[$i+2];
      if (isset($_GET[$name])) 
      {
         return $i; 
      }
   }
}



// mandar dados para filtro disponibidade
if(isset($_GET['btnConsultar']))
{
   if (isset($_GET['lsrecursos-categorias']) and  $_GET['lsrecursos-categorias'][0] != 0 and isset($_GET['lista_periodos']) and  $_GET['lista_periodos'][0] != 0)
   {

      // vai jogar dados pra proxima pagina

     // Converte a lista para JSON e faz o encode para URL

      $lista_categoria_recuso = $_GET['lsrecursos-categorias'];
      $lista_periodo = urlencode(json_encode($_GET['lista_periodos']));
      $cate = [];
      $recu = [];

      foreach($lista_categoria_recuso as $cate_recu)
      {
         if($cate_recu[0]=='C')
         {
            $cate[] = $cate_recu;

         }
         else
         {
            $recu[] =  $cate_recu;
         }

      }      
         header("Location:  cResultadoDisponibilidade.php?categorias=" . urlencode(json_encode($cate)) . 
         "&recursos=" . urlencode(json_encode($recu)) . 
         "&periodos=" .$lista_periodo);
         exit();

   }
   else
   {
      if(isset($_GET['lsrecursos-categorias']) and  $_GET['lsrecursos-categorias'][0] == 0 )
      {
         $mensagem = 'Adicione alguma categoria ou recurso';

      }
      if( isset($_GET['lista_periodos']) and  $_GET['lista_periodos'][0] == 0)
      {
         $mensagem = 'Adicione alguma periodo';
      }


      
   }
}


// recursos e categorias
// inicia da primeira requisiçã

if(!isset($_GET['lsrecursos-categorias']))
{
   $lista_recurso_cate = '<input type="hidden" name="lsrecursos-categorias[]" value="0">';

   $html = str_replace('{{dados-catego-recu}}', $lista_recurso_cate, $html);

   // carregando as opções categorias
   $op_categorias = '';
   foreach($categorias as $categora)
   {
      $op_categorias .= '<option value="Categoria,' .$categora['nome'].',' .$categora['codigo']  .'">  '.$categora['nome'].' </option>';
      
   }
      

   // carregando as opções recuso
   

}
else
{
   $lista_cate_recu = $_GET['lsrecursos-categorias'];
   // adiciona categoria
   if(isset($_GET['btnCategoria']) and isset($_GET['categoria']))
   {
      if($lista_cate_recu[0]==0)
      {
         $lista_cate_recu[0] = $_GET['categoria'];
      }
      else
      {
         $lista_cate_recu[] = $_GET['categoria'];
      }
      $mensagem = 'Categoria adicionada com Sucesso';
      $id_erro = 'sucesso';


   }

   // adiciona recuso
   if(isset($_GET['btnRecursos']) and isset($_GET['recurso']))
   {
      if($lista_cate_recu[0]==0)
      {
         $lista_cate_recu[0] = $_GET['recurso'];
      }
      else
      {
         $lista_cate_recu[] = $_GET['recurso'];
      }
      $mensagem = 'Recurso adicionado com Sucesso';
      $id_erro = 'sucesso';

      
   }
}
$salvos_recu_cate = '';


// retrasmite dados para frente
if(isset($_GET['lsrecursos-categorias']))
{
   if(!isset($_GET['btnCategoria']) and !isset($_GET['btnRecursos'])  and !isset($_GET['btnPeriodos'])  and !isset($_GET['btnConsultar']) )
      {
         
         $id = remover_recurso_categoria($lista_cate_recu);

         

         if($id != -1)
            {
               unset($lista_cate_recu[$id]);
               $lista_cate_recu = array_values($lista_cate_recu);
               $mensagem = 'Recurso removido com sucesso';
               $id_erro = 'sucesso';
            }
         if(count($lista_cate_recu)==0)
         {
            $lista_recurso_cate = '<input type="hidden" name="lsrecursos-categorias[]" value="0">';
            $html = str_replace('{{dados-catego-recu}}', $lista_recurso_cate, $html);
         }
        
      }
  

   $lista_recurso_cate ='';
   foreach($lista_cate_recu as $cate_recu)
   {
      $lista_recurso_cate .= "<input type='hidden' name='lsrecursos-categorias[]' value='$cate_recu'>";
   }
   $html = str_replace('{{dados-catego-recu}}', $lista_recurso_cate, $html);
   
   // carregando as opções categorias
   $op_categorias = '';
   foreach($categorias as $categora)
   {
      if(ja_ta_adicionado($categora['nome'], $lista_cate_recu) == false)
      {
         $op_categorias .= '<option value="Categoria,' .$categora['nome'].',' .$categora['codigo']  .'">  '.$categora['nome'].' </option>';
      }
   }
     

   // carregando as opções recuso
   $op_recuso = '';
   foreach($recursos as $recurso)
   {

      if(ja_ta_adicionado($recurso['nome'], $lista_cate_recu) == false)
      {
         $op_recuso .='<option value="Recurso,' .$recurso['nome'].',' .$recurso['codigo']  .'">  '.$recurso['nome'].' </option>';

      }
     
   }

   if(count($lista_cate_recu)>0)
   {
      if($lista_cate_recu[0] != 0)
      {
          // carrega tabela
         foreach($lista_cate_recu  as $recu_cate)
         {
            $dados = explode(',', $recu_cate );
            $dado_s = str_replace(' ','', $dados[1]);
            $dado_s = preg_replace("/[^a-zA-Z0-9]/", "", $dado_s);
            $salvos_recu_cate .= '<tr> <td> '.$dados[0].': '. $dados[1].'</td> <td>
   <input type="submit" name="'.$dado_s.'" value="Remover"> </td> </tr>';
         }
   
   
      }

   }
  
  
}

// periodos

$marca_periodo = '';

if(!isset($_GET['lista_periodos']))
{
   $lperiodos = '<input type="hidden" name="lista_periodos[]" value="0">';
   $html = str_replace('{{peridos-salvos}}', $lperiodos, $html);


}
else
{
   $lista_periodos = $_GET['lista_periodos'];

   
   if(isset($_GET['btnPeriodos']))
   {

      $data = $_GET['p-data'];
      $hora_ini =  $_GET['p-hora-ini']; 
      $hora_fim =  $_GET['p-hora-fim'];

      $data_d = $data;
      $hora_ini_d = $hora_ini;
      $hora_fim_d = $hora_fim;
      $l_mensagem = ['Periodo adicionado com sucesso','Periodo invalido', 'Preencha todos os campos'];

      $mensagem = $l_mensagem[2];

      if (mb_strlen($data)>0)
      {
        if(mb_strlen($hora_ini)>0)
        {
          if (mb_strlen($hora_fim)>0)
          {
            $mensagem = $l_mensagem[1];
            date_default_timezone_set('America/Manaus'); 
            $data_atual = new DateTime();
            $data_ini = new DateTime("$data $hora_ini");
            if($data_atual <= $data_ini)
            {
               $data_fim = new DateTime("$data $hora_fim");
               if($data_ini < $data_fim)
               {

                  if ($lista_periodos[0] == 0)
                  {
                     $lista_periodos[0] =  $data;

                  }
                  else
                  {
                     $lista_periodos[] =  $data;
                  }
                  $lista_periodos[] =  $hora_ini;
                  $lista_periodos[] =  $hora_fim;


                  $data_d = '';
                  $hora_ini_d = '';
                  $hora_fim_d = '';
                  $mensagem = $l_mensagem[0];
                  $id_erro = 'sucesso';

               }

            }
          }

        }
      }
      


      $html = str_replace('{{data}}', $data_d, $html );
      $html = str_replace('{{hora_ini}}', $hora_ini_d, $html );
      $html = str_replace('{{hora_fim}}', $hora_fim_d, $html );

      // adiciona os periodos

   }

   // remover periodos

   if(!isset($_GET['btnCategoria']) and !isset($_GET['btnRecursos'])  and !isset($_GET['btnPeriodos'])  and !isset($_GET['btnConsultar']) and $id==-1)
   {
      $id = remover_periodo($lista_periodos);
      unset($lista_periodos[$id]);
      $lista_periodos = array_values($lista_periodos);
      unset($lista_periodos[$id]);
      $lista_periodos = array_values($lista_periodos);
      unset($lista_periodos[$id]);
      $lista_periodos = array_values($lista_periodos);

      $mensagem = 'Período removido com sucesso';
      $id_erro = 'sucesso';

      
      if(count($lista_periodos) == 0)
      {
         $lperiodos = '<input type="hidden" name="lista_periodos[]" value="0">';
         $html = str_replace('{{peridos-salvos}}', $lperiodos, $html);

      }
      

   }
   

  

   if(count($lista_periodos)>1)
   {
      for ($i=0; $i < count($lista_periodos); $i=$i+3)
      { 
         $data = explode('-', $lista_periodos[$i]);
         $marca_periodo .= '<tr> <td>'.$lista_periodos[$i+1] .' até '.$lista_periodos[$i+2] .' de '.$data[2].'/'.$data[1].'/'.$data[0].' </td>  <td> <input type="submit" value="Remover" name="'. $lista_periodos[$i]. $lista_periodos[$i+1].$lista_periodos[$i+2].'"> </td>  </tr>';
      }
   }


   // retrasmitir os periodos
   $lperiodos = '';
   foreach($lista_periodos as $periodo)
   {
      $lperiodos .= '<input type="hidden" name="lista_periodos[]" value="'.$periodo.'">';
   }
   $html = str_replace('{{peridos-salvos}}', $lperiodos, $html);



}





$html = str_replace('{{retorno}}', $id_erro, $html );

$html = str_replace('{{Períodos}}', $marca_periodo, $html );

$html = str_replace('{{mensagem}}', $mensagem, $html );
$html = str_replace('{{RecursoCategoria}}', $salvos_recu_cate, $html);

$html = str_replace('{{op-categoria}}', $op_categorias, $html);
$html = str_replace('{{op-recurso}}', $op_recuso, $html);

echo $html;
?>
