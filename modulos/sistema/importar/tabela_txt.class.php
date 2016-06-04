<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
  
include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';


class CTabela_txt{

	protected $cia_id = 0;
	protected $arquivo = '';

		
	public function setCia($cia_id=0) {
		$this->cia_id=$cia_id;
		}		

	public function criar_xml($arquivo='exemplo') {
		$this->arquivo=$arquivo;
		
		global $config;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		
		//limpar o arquivo se j� existir
		@unlink($base_dir.'/arquivos/temp/'.$arquivo.'.txt');
		@unlink($base_dir.'/arquivos/temp/'.$arquivo.'.txt.gz');
		
		$sql = new BDConsulta();
		$sql->adTabela('plano_gestao');
	  $sql->adCampo('pg_id');
	  $sql->adOnde('pg_cia='.(int)$this->cia_id);
	  $lista = $sql->ListaChaveSimples();
	  $sql->limpar();
	  $lista_plano_gestao=array();
	  foreach($lista as $linha) $lista_plano_gestao[]=$linha['pg_id'];
	  $lista_plano_gestao=implode(',',$lista_plano_gestao); 
		

		$this->inserir_arquivo('plano_gestao', 'pg_cia='.(int)$this->cia_id);
		if ($lista_plano_gestao){
			$this->inserir_arquivo('plano_gestao2', 'pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_ameacas', 'pg_ameaca_pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_diretrizes', 'pg_diretriz_pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_diretrizes_superiores', 'pg_diretriz_superior_pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('estrategias', 'pg_estrategia_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('fatores_criticos', 'pg_fator_critico_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_fornecedores', 'pg_fornecedor_pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('metas', 'pg_meta_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('objetivos_estrategicos', 'pg_objetivo_estrategico_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_oportunidade', 'pg_oportunidade_pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_oportunidade_melhorias', 'pg_oportunidade_melhoria_pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_pessoal', 'pg_pessoal_pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_pontosfortes', 'pg_ponto_forte_pg_id IN ('.$lista_plano_gestao.')');
			$this->inserir_arquivo('plano_gestao_premiacoes', 'pg_premiacao_pg_id IN ('.$lista_plano_gestao.')');
			}

		$sql->adTabela('praticas');
	  $sql->adCampo('pratica_id');
	  $sql->adOnde('pratica_cia='.(int)$this->cia_id);
	  $lista = $sql->ListaChaveSimples();
	  $sql->limpar();
	  $lista_praticas=array();
	  foreach($lista as $linha) $lista_praticas[]=$linha['pratica_id'];
	  $lista_praticas=implode(',',$lista_praticas); 	
			
		$sql->adTabela('pratica_indicador');
	  $sql->adCampo('pratica_indicador_id');
	  $sql->adOnde('pratica_indicador_cia='.(int)$this->cia_id);
	  $lista = $sql->ListaChaveSimples();
	  $sql->limpar();
	  $lista_indicadores=array();
	  foreach($lista as $linha) $lista_indicadores[]=$linha['pratica_indicador_id'];
	  $lista_indicadores=implode(',',$lista_indicadores); 	


		$this->inserir_arquivo('praticas', 'pratica_cia='.(int)$this->cia_id);
		$this->inserir_arquivo('pratica_indicador', 'pratica_indicador_cia='.(int)$this->cia_id);

		if ($lista_indicadores){
			$this->inserir_arquivo('pratica_indicador_nos_marcadores', 'pratica_indicador_id IN ('.$lista_indicadores.')');
		
			$sql->adTabela('pratica_indicador_valor');
			$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_id=pratica_indicador_valor_indicador');
	    $sql->adCampo('pratica_indicador_valor_indicador, pratica_indicador_valor_data, pratica_indicador_valor_valor, pratica_indicador_valor_responsavel, pratica_indicador_valor_obs');
	    $sql->adOnde('pratica_indicador_valor_indicador IN ('.$lista_indicadores.')'); 
	    $sql->adOnde('pratica_indicador_composicao=0'); 
	    $lista = $sql->lista();
	    $sql->limpar();

			$sql->adTabela('pratica_indicador');
	    $sql->adCampo('DISTINCT pratica_indicador_id, pratica_indicador_nr_pontos, pratica_indicador_agrupar');
	    $sql->adOnde('pratica_indicador_id IN ('.$lista_indicadores.')'); 
	    $sql->adOnde('pratica_indicador_composicao=1'); 
	    $lista_composicao = $sql->lista();
	    $sql->limpar();
			
			foreach($lista_composicao as $linha){
				$obj_indicador = new Indicador($linha['pratica_indicador_id']);
				$data = new CData();
				$valores=$obj_indicador->Pontos($linha['pratica_indicador_nr_pontos'], $linha['pratica_indicador_agrupar'], $data->format("%Y-%m-%d"));
				foreach($valores as $data=> $valor)	$lista[]=array('pratica_indicador_valor_indicador'=> $linha['pratica_indicador_id'], 'pratica_indicador_valor_data'=> $this->converter_data($data), 'pratica_indicador_valor_valor'=> $valor['valor'], 'pratica_indicador_valor_responsavel'=> '0', 'pratica_indicador_valor_obs'=> '');
				}	
			$this->inserir_arquivo('pratica_indicador_valor', '', $lista);
			
			$this->inserir_arquivo('pratica_indicador_nos_marcadores', 'pratica_indicador_id IN ('.$lista_indicadores.')');
			$this->inserir_arquivo('pratica_indicador_nos_marcadores', 'pratica_indicador_id IN ('.$lista_indicadores.')');
			
			//verificar depois a composi��o (ideia transformar o de composi��o em simples, exportando os valores atuais)
			$this->inserir_arquivo('pratica_indicador_composicao', 'pratica_indicador_composicao_pai IN ('.$lista_indicadores.')');
			}

		if ($lista_indicadores)	$this->inserir_arquivo('pratica_nos_marcadores', 'pratica IN ('.$lista_praticas.')');
		

		$sql->adTabela('projetos');
	  $sql->adCampo('projeto_id');
	  $sql->adOnde('projeto_cia='.(int)$this->cia_id);
	  $lista = $sql->ListaChaveSimples();
	  $sql->limpar();
	  $lista_projetos=array();
	  foreach($lista as $linha) $lista_projetos[]=$linha['projeto_id'];
	  $lista_projetos=implode(',',$lista_projetos); 


		$this->inserir_arquivo('projetos', 'projeto_cia='.(int)$this->cia_id);
		if($lista_projetos){
			$this->inserir_arquivo('tarefas', 'tarefa_projeto IN ('.$lista_projetos.')');
			}
		
		$this->inserir_arquivo('recursos', 'recurso_cia='.(int)$this->cia_id);
		
		
		$lista_tarefas='';
		if($lista_projetos){
			$sql->adTabela('tarefas');
		  $sql->adCampo('tarefa_id');
		  $sql->adOnde('tarefa_projeto IN ('.$lista_projetos.')');
		  $lista = $sql->ListaChaveSimples();
		  $sql->limpar();
		  $lista_tarefas=array();
		  foreach($lista as $linha) $lista_tarefas[]=$linha['tarefa_id'];
		  $lista_tarefas=implode(',',$lista_tarefas); 
			}
		
		if($lista_tarefas){
			$this->inserir_arquivo('recurso_tarefas', 'tarefa_id IN ('.$lista_tarefas.')');
			$this->inserir_arquivo('tarefa_custos', 'tarefa_custos_tarefa IN ('.$lista_tarefas.')');
			$this->inserir_arquivo('tarefa_dependencias', 'dependencias_tarefa_id IN ('.$lista_tarefas.')');
			$this->inserir_arquivo('tarefa_gastos', 'tarefa_gastos_tarefa IN ('.$lista_tarefas.')');
			$this->inserir_arquivo('tarefa_h_custos', 'h_custos_tarefa IN ('.$lista_tarefas.')');
			$this->inserir_arquivo('tarefa_h_gastos', 'h_gastos_tarefa IN ('.$lista_tarefas.')');
			$this->inserir_arquivo('tarefa_log', 'tarefa_log_tarefa IN ('.$lista_tarefas.')');
			}
		$this->inserir_arquivo('links', 'link_cia='.(int)$this->cia_id);
		$this->inserir_arquivo('eventos', 'evento_cia='.(int)$this->cia_id);

		$this->gzcompressfile($base_dir.'/arquivos/temp/'.$arquivo.'.txt');
		}	

	protected function gzcompressfile($source,$level=false){
    $dest=$source.'.gz';
    $mode='wb'.$level;
    $error=false;
    if($fp_out=gzopen($dest,$mode)){
    	if($fp_in=fopen($source,'rb')){
      	while(!feof($fp_in)) gzwrite($fp_out,fread($fp_in,1024*512));
        fclose($fp_in);
        }
      else $error=true;
      gzclose($fp_out);
      }
    else $error=true;
    if($error) return false;
    else return $dest;
    } 


	protected function converter_data($data){
		if (strlen($data)==4) return $data.'-01-01';
		elseif (strlen($data)==6) return $data.'-01';
		else return $data;
		}
	
	protected function inserir_arquivo($tabela, $onde='', $vetor=array()){
		global $sql,$config;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		
		if(!count($vetor)){
			$sql->adTabela($tabela);
	    $sql->adCampo('*');
	    if ($onde) $sql->adOnde($onde); 
	    $lista = $sql->lista();
	    $sql->limpar();
	  	}
	  else $lista=$vetor;
	  
   	if (count($lista)){
	   	file_put_contents($base_dir.'/arquivos/temp/'.$this->arquivo.'.txt', 'TABELA|*|'.$tabela."|*|\n", FILE_APPEND | LOCK_EX);
	   	$saida='';
   		foreach($lista[0] as $chave=> $valor) $saida.=$chave.'|*|';
			$saida.="\n";
	   	file_put_contents($base_dir.'/arquivos/temp/'.$this->arquivo.'.txt', $saida, FILE_APPEND | LOCK_EX);
			foreach($lista as $linha){
				$saida='';
				foreach($linha as $chave=> $valor) $saida.=html_para_javascript($valor, true).'|*|';
				$saida.="\n";
				file_put_contents($base_dir.'/arquivos/temp/'.$this->arquivo.'.txt', $saida, FILE_APPEND | LOCK_EX);
				}
			}
		}

	
		

	}


?>