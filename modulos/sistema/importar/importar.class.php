<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************

Classe CImportar para importa��o de dados ao Sistema de aplicativos externos
		
gpweb\modulos\sistema\importar\importar.class.php																																		
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if($Aplic->profissional) include_once 'msproject_pro.php';
else include_once 'msproject.php';
include_once 'wbs.php';         

class CImportar {
  protected $scrubbedData = '';
  public $arquivoType = '';
  public $importType='';
  public $formatacao='';
  protected $importClassname = '';
  protected $proName='';
  protected $usuario_control='';
  public static function resolverTipoArquivo($arquivotipo = '') {
  	global $Aplic;
  	switch($arquivotipo) {
      case '.wbs':
          $importar = new ImportarWBS();
          $importar->fileType = '.wbs';
          break;
      case '.xml':
      	  if($Aplic->profissional) $importar = new ImportarMSProjectPro();
      	  else $importar = new ImportarMSProject();
          $importar->fileType = '.xml';
          break;
      default:
          $importar = null;
      }
    return $importar;
  	}

 
  protected function _criarSelecaoProjeto($Aplic, $projeto_nome) {
    $saida = '<tr><td align="right">Nome Projeto:</td>';
    $sql = new BDConsulta();
    $sql->adCampo('projeto_id');
    $sql->adTabela('projetos');
    $sql->adOnde('projeto_nome = \''.$projeto_nome.'\'');
    $projeto_id = $sql->resultado();
    $saida .= '<td><input type="text" name="new_project" class="texto" style="width:250px;" value="'.$projeto_nome.'"/>';
    if ($projeto_id) {
      $saida .= '<input type="hidden" name="project_id" value="'.$projeto_id.'" />';
      $saida .= 'projeto j� existente!';
    	}
    $saida .= '</td></tr>';
    return $saida;
 	 	}

  protected function _deDynamicLeafNodes($projetoId) {
    $sql = "UPDATE tarefas SET tarefa_dinamica = 0 WHERE tarefa_projeto = $projetoId";
    db_exec($sql);
    $sql = new BDConsulta();
    $sql->adTabela('tarefas');
    $sql->adCampo('distinct(tarefa_superior)');
    $sql->adOnde('tarefa_projeto = '.$projetoId);
    $sql->adOnde("tarefa_id != tarefa_superior");
    $listaTarefas = $sql->lista();
		$dynamiCTarefas='';
    foreach($listaTarefas as $id => $nothing) $dynamiCTarefas .= $id.',';
    $dynamiCTarefas .= '0';
    $sql->limpar();
    $sql->adAtualizar('tarefa_dinamica', 1);
    $sql->adOnde('tarefa_projeto = '.$projetoId);
    $sql->adOnde('tarefa_id IN ('.$dynamiCTarefas.')');
    $sql->adTabela('tarefas');
    $sql->sem_chave_estrangeira();
    $sql->exec();
		}

  protected function _processarContatos(CAppUI $Aplic, $usuarioname) {
    $space = strrpos($usuarioname, ' ');
    if ($space === false) {
      $first_name = '';
      $last_name = $usuarioname;
    	} 
    else {
      $first_name = substr($usuarioname, 0, $space);
      $last_name = substr($usuarioname, $space + 1);
    	}
    $contact = new CContact;
    $contact->contact_first_name = ucwords($first_name);
    $contact->contact_last_name = ucwords($last_name);
    $contact->contact_order_by = $usuarioname;
    $resultado = $contact->armazenar($Aplic);
    return (is_array($resultado)) ? $resultado : $contact->contact_id;
		}

  protected function _processarTarefa($projeto_id, $tarefa, $dono=null, $cia_id=null) {
  	global $Aplic, $bd;
  	$sql = new BDConsulta();
  	$sql->adTabela('tarefas');
		$sql->adInserir('tarefa_nome', getParam($tarefa, 'task_name', null));
		$sql->adInserir('tarefa_projeto', $projeto_id);
		$sql->adInserir('tarefa_cia', $cia_id);
		$sql->adInserir('tarefa_descricao', getParam($tarefa, 'task_description', ''));
		$sql->adInserir('tarefa_inicio', (array_key_exists('task_start_date', $tarefa) ? $tarefa['task_start_date'] : ''));
		$sql->adInserir('tarefa_fim', (array_key_exists('task_end_date', $tarefa) ? $tarefa['task_end_date'] : ''));
		$sql->adInserir('tarefa_duracao', (array_key_exists('task_duration', $tarefa) ? $tarefa['task_duration'] : ''));
		if (array_key_exists('task_duration', $tarefa) && !$tarefa['task_duration']) $sql->adInserir('tarefa_marco', 1);
		$sql->adInserir('tarefa_marco', 0);
		//$sql->adInserir('tarefa_marco', $tarefa['task_milestone']);
		$sql->adInserir('tarefa_dono', $dono);
		$sql->adInserir('tarefa_dinamica', (isset($tarefa['task_dynamic']) ? (int) $tarefa['task_dynamic'] : 0));
		$sql->adInserir('tarefa_prioridade', (array_key_exists('task_priority', $tarefa) ? $tarefa['task_priority'] : ''));
		if (isset($tarefa['task_percent_complete']))$sql->adInserir('tarefa_percentagem', $tarefa['task_percent_complete']);
		$sql->adInserir('tarefa_duracao_tipo', 1);
		$sql->sem_chave_estrangeira();
		$resultado=$sql->exec();
		$tarefa_id=$bd->Insert_ID('tarefas','tarefa_id');
  	$sql->limpar();
  	return ($tarefa_id ? $tarefa_id : array(0 => $resultado));
  	}

  protected function _processarProjeto($cia_id, $projetoInfo) {
		global $Aplic;
    $projetoNome = getParam( $projetoInfo, 'new_project', '');
    $projetoInicio = getParam( $projetoInfo, 'project_start_date', '');
    $projetoFim = getParam( $projetoInfo, 'project_end_date', '');
    $donoProjeto = getParam( $projetoInfo, 'project_owner', $Aplic->usuario_id);
    $projetoStatus = getParam($projetoInfo, 'project_status', 0);
		$cia_id= getParam( $projetoInfo, 'cia_id', 0);
    $projeto = new CProjeto;
    $projeto->projeto_nome = $projetoNome;
    $projeto->projeto_nome_curto = substr($projetoNome, 0, 19);
    $projeto->projeto_cia = $cia_id;
    $projeto->projeto_data_inicio = $projetoInicio;
    $projeto->projeto_data_fim = $projetoFim;
    if ($donoProjeto) $projeto->projeto_responsavel = $donoProjeto;
    $projeto->projeto_criador = $Aplic->usuario_id;
    $projeto->projeto_status = (int)$projetoStatus;
    $projeto->projeto_ativo = 1;
    $projeto->projeto_prioridade = '0';
    $projeto->projeto_tipo = '0';
    $projeto->projeto_cor = 'FFFFFF';
    $resultado = $projeto->armazenar($Aplic);
		$projeto->projeto_superior = $projeto->projeto_id;
		$projeto->projeto_superior_original = $projeto->projeto_id;
		$projeto->armazenar();
    return (is_array($resultado)) ? $resultado : $projeto->projeto_id;
  	}
	}