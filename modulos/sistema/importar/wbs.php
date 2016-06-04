<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************

Classe ImportarWBS para importação de dados do WBS Pro
		
gpweb\modulos\sistema\importar\wbs.class.php																																		
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once(BASE_DIR.'/modulos/projetos/projetos.class.php');
include_once(BASE_DIR.'/modulos/tarefas/funcoes.php');

class ImportarWBS extends CImportar {
  public function import($Aplic) {
    $saida = '';
    $cia_id = (int) getParam($_REQUEST, 'cia_id', 0);
    if ($cia_id == 0) {
      $erro = 'O nome da organização está em branco, por favor selecione uma.';
      return $erro;
      }
    $resultado = $this->_processarProjeto($cia_id, $_REQUEST);
    if (is_array($resultado)) {
      $Aplic->setMsg($resultado, UI_MSG_ERROR);
      $Aplic->redirecionar('m=sistema&a=index&u=importar');
    	}
    $projeto_id = $resultado;
		$dono=getParam($_REQUEST, 'project_owner', $Aplic->usuario_id);
    $sql = new BDConsulta();
    $conversao_usuarios=(isset($_REQUEST['users']) ? getParam($_REQUEST, 'users', null) : null);
    $nivel=array();
    $dependencias=array();
    $id_unico=array();
    foreach ($_REQUEST['tasks'] as $k => $tarefa) {
      $resultado = $this->_processarTarefa($projeto_id, previnirXSS($tarefa), $dono);
      if (is_array($resultado)) {
        $Aplic->setMsg($resultado, UI_MSG_ERROR);
        $Aplic->redirecionar('m=sistema&a=index&u=importar');
    		}
      $tarefa_id = $resultado;
      $id_unico[$tarefa['UniqueID']]=$tarefa_id;
      $nivel[]=array('uid' => $tarefa['UID'], 'nivel'=> $tarefa['OutlineLevel'], 'tarefa_id'=> $tarefa_id);
      $_REQUEST['tasks'][$k]['task_id'] = $tarefa_id;
      $tarefa['task_id'] = $tarefa_id;
      //designados
      $tarefas[$tarefa['UID']] = $tarefa;
			$sql->setExcluir('tarefa_designados');
			$sql->adOnde('tarefa_id ='.$tarefa_id );
			$sql->exec();
			$sql->limpar();
      if (isset($tarefa['resources']) && count($tarefa['resources']) > 0) {
        foreach($tarefa['resources'] as $uk => $usuario) {
          $alloc = $tarefa['resources_alloc'][$uk];
          if ($alloc > 0 && isset($conversao_usuarios[$usuario]) && $conversao_usuarios[$usuario]) {
            $sql->adTabela('tarefa_designados');
            $sql->adInserir('usuario_id', $conversao_usuarios[$usuario]);
            $sql->adInserir('tarefa_id', $tarefa_id);
            $sql->adInserir('perc_designado', $alloc);
            $sql->sem_chave_estrangeira();
            $sql->exec();
            $sql->limpar();
        		}
      		}
    		}
    	$sql->setExcluir('tarefa_dependencias');
			$sql->adOnde('dependencias_tarefa_id ='.$tarefa_id );
			$sql->exec();
			$sql->limpar();	
    	//vetor com as dependencias	
    	 if (isset($tarefa['dependencies']) && is_array($tarefa['dependencies'])) {
        foreach($tarefa['dependencies'] as $tarefa_uid) {
      		$dependencias[]=array('tarefa_id'=> $tarefa_id , 'id_unico'=> $tarefa_uid);
      		}
    		}
  		}
  	//corrigir tarefa_superior
		$virtuais=array();
		foreach($nivel AS $chave => $linha){
			$superior=0;
			if ($linha['nivel']==1) $superior=$linha['tarefa_id'];
			else {
				for($i=$chave; $i>=0; $i--){
					if ($nivel[$i]['nivel'] < $linha['nivel']) {
						$superior=$nivel[$i]['tarefa_id'];
						$virtuais[$superior]=1;
						break;
						}
					}
				}
			$dinamica=1;
			$sql->adTabela('tarefas');
			$sql->adAtualizar('tarefa_superior', $superior);
			$sql->adOnde('tarefa_id = '.$linha['tarefa_id']);
			$sql->exec();
			$sql->limpar();
			}
  	 // dependencias das tarefas
    foreach($dependencias as $linha) {
      if ($linha['tarefa_id'] && $id_unico[$linha['id_unico']]){
	      $sql->adTabela('tarefa_dependencias');
	      $sql->adInserir('dependencias_tarefa_id', $linha['tarefa_id']);
	      $sql->adInserir('dependencias_req_tarefa_id', $id_unico[$linha['id_unico']]);
	      $sql->sem_chave_estrangeira();
	      $sql->exec();
	      $sql->limpar();
	    	}
  		}
  	//corrigir as tarefas  para serem dinamica
  	$vetor_virtual=array();
  	foreach($virtuais AS $chave => $valor) $vetor_virtual[]=$chave;
  	if (count($vetor_virtual)){
    	$sql->adTabela('tarefas');
			$sql->adAtualizar('tarefa_dinamica', 1);
			$sql->adOnde('tarefa_id IN ('.implode(',', $vetor_virtual).')');
			$sql->sem_chave_estrangeira();
			$resultado=$sql->exec();
			$sql->limpar();	
    	}
 	 	atualizar_percentagem($projeto_id);
  	return $saida;
		}

  public function visualizar() {
    global $Aplic, $config,$cia_id, $localidade_tipo_caract;

    $saida = '<tr><td colspan=20><table width="100%">';
    $data = $this->scrubbedData;
    
    //bug simplexml_load_string transforma &#231; que deveria ser ç em lixo e por ai vai
    $data=html_entity_decode($data, ENT_COMPAT, $localidade_tipo_caract);
    $data=utf8_encode($data);
    $arquivo_xml = simplexml_load_string($data);
    $projeto_nome = utf8_decode($arquivo_xml->proj->summary['Title']);
    if (empty($projeto_nome)) $projeto_nome=$this->proName;
    $sql = new BDConsulta();
		$saida .= '<tr><td align="right" nowrap="nowrap">'.ucfirst($config['organizacao']).':</td><td width="100%"><input type="hidden" name="cia_id" value="'.$cia_id.'">'.nome_cia($cia_id).'</td></tr>';
    $projetoClass = new CProjeto();
    $saida .= $this->_criarSelecaoProjeto($Aplic, $projeto_nome);
    $saida .= '<tr><td align="right" nowrap="nowrap">Dono do Projeto:</td><td>';
    $saida .=mudar_usuario($cia_id, $Aplic->usuario_id, 'project_owner','', 'class="texto" size=1 style="width:250px;"');
    $saida .= '<td/></tr>';
    $pstatus =  getSisValor('StatusProjeto');
    $saida .= '<tr><td align="right" nowrap="nowrap">Situação do Projeto:</td><td>';
    $saida .= selecionaVetor( $pstatus, 'project_status', 'size="1" class="texto"', '', true );
    $saida .= '<td/></tr>';
    $saida .= '<tr><td align="right" nowrap="nowrap">Data de Início:</td><td><input class="texto" type="text" name="project_start_date" value="'.$arquivo_xml->proj->summary['Start']. '" /></td></tr>';
  	$saida .= '<tr><td align="right" nowrap="nowrap">Data Final:</td><td><input type="text" class="texto" name="project_end_date" value="'.$arquivo_xml->proj->summary['Finish'].'" /></td></tr>';
  	$saida .= '<tr><td align="right"><b>'.ucfirst($config['usuarios']).':</b></td><td></td></tr>';
    $designados=array();
    $designados[0]='';
    $sql = new BDConsulta();
    $trabalhadores=$arquivo_xml->proj->resources->children();
		$q = new BDConsulta();
    $q->adCampo('u.*,co.*,concatenar_tres(co.contato_posto,\' \',co.contato_nomeguerra) as full_name,comp.cia_nome');
    $q->adTabela('usuarios', 'u');
    $q->esqUnir('contatos','co','co.contato_id = u.usuario_contato');
    $q->esqUnir('cias','comp','comp.cia_id=co.contato_cia');
    $q->adOnde('contato_cia='.(int)$cia_id);
    $q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
    $usuarios = $q->Lista();
		$q->limpar();
	
	$qnt=(is_array($trabalhadores) ? count($trabalhadores) : 0);	

		if ($qnt){
	    foreach($trabalhadores as $r) {
				$nome=previnirXSS(utf8_decode($r['name']));
	      $sql->adCampo('usuario_id');
	      $sql->adTabela('usuarios');
	      $sql->esqUnir('contatos', 'c', 'usuario_contato = contato_id');
	      $sql->adOnde('usuario_login LIKE \'%'.$nome.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra) LIKE \'%'.$nome.'%\'');
	      $r['lid'] = $sql->resultado();
	      $sql->limpar();
	      if (!empty($r['name'])) {
	      	$saida .= '<tr>';
	      	$saida .= '<td align="right" nowrap="nowrap">'.ucwords(strtolower($nome)).':</td>';
	      	$saida .= '<td nowrap="nowrap" align="left"><select name="users['.(int)$r['uid'].']" class="texto">';
	        if (empty($r['lid'])) {
	          $saida .= '<option value="0" selected>Mudar</option>\n';
	      		}
	        foreach ($usuarios as $usuario) {
	           if (!empty($r['lid']) && $usuario["usuario_id"]==$r['lid']) $designados[$r['UID']] = $usuario["contato_posto"].' '.$usuario["contato_nomeguerra"];
	          $saida .= '<option value="'.$usuario["usuario_id"].'"'.(!empty($r['lid']) && $usuario["usuario_id"]==$r['lid']?"selected":"").'>'.$usuario["contato_posto"].' '.$usuario["contato_nomeguerra"].'</option>\n';
	      		}
	        $saida .= '</select></td>';
	       	$saida .='</tr>';
	      	$designados[(int)$r['uid']] = ucwords(strtolower($nome));
	    		}
				}
			}

    $saida .= '<tr><td colspan="2">Tarefas:</td></tr>';
		$saida .= '<tr><td colspan="2"><table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
		$saida .= '<tr><th>Nome</th><th>Data de Início</th><th>Data de Término</th><th>Alocações do Usuário</th></tr>';
		foreach($arquivo_xml->proj->tasks->children() as $tarefa) {
      if ($tarefa['ID'] != 0) {
        $newWBS=$this->montar_wbs($tarefa['OutlineLevel']);
        $note=' ';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][UID]" value="'.$tarefa['ID'].'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][OutlineLevel]" value="'.$tarefa['OutlineLevel'].'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][OUTLINENUMBER]" value="'.$newWBS.'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_name]" value="'.utf8_decode($tarefa['Name']).'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_description]" value="'.utf8_decode($note).'" />';
        $prioridade = 0;
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_priority]" value="'.$prioridade.'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_start_date]" value="'.$tarefa['Start'].'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_end_date]" value="'.$tarefa['Finish'].'" />';
				$saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][UniqueID]" value="'.$tarefa['UniqueID'].'" />';
        $minhaDuracao = $this->dur($tarefa['Duration']);
        $percentualCompletado = isset($tarefa['PercentComplete']) ? $tarefa['PercentComplete'] : 0;
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_duration]" value="'.$minhaDuracao.'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_percent_complete]" value="'.$percentualCompletado.'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_description]" value="'.$note.'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_owner]" value="'.$Aplic->usuario_id.'" />';
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_type]" value="0" />';
        $marco = ($tarefa['Milestone'] == 'yes') ? 1 : 0;
        $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][task_milestone]" value="'.$marco.'" />';
        $temp = 0;
        if (!empty($tarefa['UniqueIDPredecessors'])) {
          $x=strpos($tarefa['UniqueIDPredecessors'],",");
          foreach ((array)$tarefa['UniqueIDPredecessors'] as $dependencia) {
            $saida .= '<input type="hidden" name="tasks['.$tarefa['ID'].'][dependencies][]" value="'.$dependencia['UniqueIDPredecessors'].'" />'; 
            ++$temp;
          	}
        	}
        $saida .= '<tr><td>';	
        for($i = 1; $i < $tarefa['OutlineLevel']; $i++) $saida .= '&nbsp;&nbsp;&nbsp;';
				if ($i>1) $saida .=imagem('icones/corner-dots.gif');
        $saida .= utf8_decode($tarefa['Name']).($tarefa['NOTES']? '<br /><hr />'.utf8_decode($tarefa['NOTES']).'<hr size="2" />' : '') .'</td><td align="center">'.substr($tarefa['Start'],0,10).'</td><td align="center">'.substr($tarefa['Finish'],0,10).'</td><td>';
        $recurso='';
        $perc = 100;
				//para inserir mais de um designado
        if (!empty($tarefa['Resources'])){
          $x=0;
          $y=strpos($tarefa['Resources'],';');
          while (!empty($y)){
            $recurso=substr($tarefa['Resources'],$x,($y-$x));
            $saida.= selecionaVetor($designados, 'tasks['.$tarefa['ID'].'][resources][]', 'class="texto"', $recurso);
            $saida.= '<input type="text" class="texto" name=tasks['.$tarefa['ID'].'][resources_alloc][]" value="'.sprintf("%.0f", $perc).'" size="3" />%<br />';
            $x=$y+1;
            $y=strpos($tarefa['Resources'],';',$x);
        		}
          $recurso=substr($tarefa['Resources'],$x);
          $saida.= selecionaVetor($designados, 'tasks['.$tarefa['ID'].'][resources][]', 'class="texto"', $recurso);
          $saida.= '<input type="text" class="texto" name=tasks['.$tarefa['ID'].'][resources_alloc][]" value="'.sprintf("%.0f", $perc).'" size="3" />%<br />';
      		}
        $saida .= '</td></tr>';
    		}
			}
		$saida .= '</table></td></tr>';
		$saida .= '</table></td></tr>';
		return $saida;
		}

  public function loadFile($Aplic) {
    $nome_arquivo = $_FILES['upload_file']['tmp_name'];
    $pos=strrpos($_FILES['upload_file']['name'],".");
    $nomeArquivo=substr($_FILES['upload_file']['name'],0,$pos);
    $arquivo = fopen($nome_arquivo, "r");
    $arquivodata = fread($arquivo, $_FILES['upload_file']['size']);
    fclose($arquivo);
    if (substr_count($arquivodata, '<tasks>') < 1) return false;
    $x = strpos($arquivodata, '<calendar>');
    $cabecalho = substr($arquivodata, 0, $x);
    $noduloSumario = $this->stripper("<summary ","/>",$arquivodata);
    $nodulosTarefa = $this->stripper("<tasks>","</tasks>",$arquivodata);
    $nodulosFim = "</proj></project>";
    if (substr_count($arquivodata, '<resources>') < 1) {
      echo '<tr><td><b>Nenhum usuário encontrado neste arquivo. Você pode adicionar um novo ou alocar algum já existente no sistema após importar o projeto.</td></tr>';
      $arquivodata=$cabecalho.$noduloSumario.$nodulosTarefa.$nodulosFim;
  		} 
    else {
      $usuarioNodes=$this->stripper("<resources>","</resources>",$arquivodata);
      $arquivodata=$cabecalho.$noduloSumario.$usuarioNodes.$nodulosTarefa.$nodulosFim;
    	}
    
    $this->scrubbedData = $arquivodata;
    $this->proName=$nomeArquivo;
    return true;
		}
  /* Extrai uma determinada tag xml de uma string que com
   * conteúdo de um arquivo
   * @param    string    $startTag Tag de inicio
   * string    $endTag    Tag de final
   * string    $data Escopo onde vai ser procurado a tag
  */
  private function stripper($startTag,$endTag,$data) {
    $x=strpos($data, $startTag);
    $y=strpos($data, $endTag,$x)+strlen($endTag);
    $data = substr($data, $x, ($y-$x));
    return $data;
		}
  private function dur($duracao) {
    //multiplica o numero de dias por 8
    $Offset = strpos($duracao, 'd');
    $x = substr($duracao, 0, $Offset);
    return ($x*8);
		}
  private function montar_wbs($outline){
    //Esta função cria o caminho wbs da tarefa.WBS Gantt Chart Pro não cria
    global $wbsAnt,$nivelAnt;
    $wbs='';
    if ($outline==0) { } 
    else if ($outline==1) $wbs= "1";
   	else if ($outline==$nivelAnt) {
      $x=strripos($wbsAnt,".")+1;
      $inicioWBS=substr($wbsAnt,0,$x);
      $fimWBS=substr($wbsAnt,$x);
      $fimWBS++;
      $wbs=$inicioWBS.$fimWBS;
  		} 
    else if ($outline > $nivelAnt) $wbs=$wbsAnt.".1";
    else if ($outline < $nivelAnt) {
      $y=0;
      $x=0;
      $n=0;
      while ($n < $outline) {
        $x=$y;
        $x++;
        $y=strpos($wbsAnt,".",$x);
        $n++;
      	}
      $inicioWBS=substr($wbsAnt,0,$x);
      $fimWBS=substr($wbsAnt,$x,($y-$x));
      $fimWBS++;
      $wbs=$inicioWBS.$fimWBS;
    	}
    $wbsAnt=$wbs;
    $nivelAnt=$outline;
    return $wbs;
		}
	}

?>
