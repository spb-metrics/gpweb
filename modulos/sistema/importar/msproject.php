<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************

Classe ImportarMSProject para importação de dados do MSProject
		
gpweb\modulos\sistema\importar\msproject.class.php																																		
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
include_once('xml.inc.php');
include_once(BASE_DIR.'/modulos/tarefas/funcoes.php');

class ImportarMSProject extends CImportar {

  public function import($Aplic) {
    $saida = '';
    $cia_id = getParam($_REQUEST, 'cia_id', null);
    if (!$cia_id) {
      $erro = 'O nome da organização está em branco, por favor selecione uma.';
      return $erro;
      }
		
		$dono=getParam($_REQUEST, 'project_owner', $Aplic->usuario_id);
		
    $resultado = $this->_processarProjeto($cia_id, $_REQUEST);
    if (is_array($resultado)) {
      $Aplic->setMsg($resultado, UI_MSG_ERROR);
      $Aplic->redirecionar('m=sistema&a=index&u=importar');
  		}
    $projeto_id = $resultado;
    $sql = new BDConsulta();
    
    $conversao_usuarios=(isset($_REQUEST['users']) ? getParam($_REQUEST, 'users', null) : array());
    $nivel=array();
    $dependencias=array();
    $id_unico=array();

  		
    foreach ($_REQUEST['tasks'] as $k => $tarefa) {
      $resultado = $this->_processarTarefa($projeto_id, previnirXSS($tarefa), $dono, $cia_id);
      if (is_array($resultado)) {
        $Aplic->setMsg($resultado, UI_MSG_ERROR);
        $Aplic->redirecionar('m=sistema&a=index&u=importar');
    		}

      $tarefa_id = $resultado;
			$id_unico[$tarefa['UID']]=$tarefa_id;
     
      // tarefa superior
      $outline[$tarefa['OUTLINENUMBER']] = $tarefa_id;
      $sql->limpar();

      if (!strpos($tarefa['OUTLINENUMBER'], '.')) {
        $sql->adTabela('tarefas');
        $sql->adAtualizar('tarefa_superior', $tarefa_id);
        $sql->adOnde('tarefa_id = '.$tarefa_id);
    		} 
      else {
        $texto_superior = substr($tarefa['OUTLINENUMBER'], 0, strrpos($tarefa['OUTLINENUMBER'], '.'));
        $tarefa_superior = isset($outline[$texto_superior]) ? $outline[$texto_superior] : $tarefa_id;
        $sql->adTabela('tarefas');
        $sql->adAtualizar('tarefa_superior', $tarefa_superior);
        $sql->adOnde('tarefa_id = '.$tarefa_id);
      	}
      $sql->exec();

      $_REQUEST['tasks'][$k]['task_id'] = $tarefa_id;
      $tarefa['task_id'] = $tarefa_id;

      // designados
      $tarefas[$tarefa['UID']] = $tarefa;

			$sql->setExcluir('tarefa_designados');
			$sql->adOnde('tarefa_id ='.$tarefa_id );
			$sql->exec();
			$sql->limpar();

			
      if (isset($tarefa['resources']) && count($tarefa['resources']) > 0) {
        foreach($tarefa['resources'] as $uk => $usuario) {
          $alloc = $tarefa['resources_alloc'][$uk];
          if ($alloc > 0 && isset($conversao_usuarios[$usuario]) && $conversao_usuarios[$usuario] > 0) {
            $sql->adTabela('tarefa_designados');
            $sql->adInserir('usuario_id', $conversao_usuarios[$usuario]);
            $sql->adInserir('tarefa_id', $tarefa_id);
            $sql->adInserir('perc_designado', $alloc);
          	$sql->exec();
          	$sql->limpar();
      			}
  				}
				}
			
			
		   if (isset($tarefa['gasto_nome']) && count($tarefa['gasto_nome']) > 0) {
        foreach($tarefa['gasto_nome'] as $chave => $nome) {
        	$quantidade=(isset($tarefa['gasto_quantidade'][$chave])? $tarefa['gasto_quantidade'][$chave] : 0);
        	$tarefa_custos_custo=($quantidade !=0 ? (isset($tarefa['gasto_custo'][$chave]) ? $tarefa['gasto_custo'][$chave] : 0)/($quantidade*100) : 0);
        	if ($tarefa_custos_custo){
            $sql->adTabela('tarefa_custos');
            $sql->adInserir('tarefa_custos_nome', $nome);
            $sql->adInserir('tarefa_custos_quantidade', $quantidade);
            $sql->adInserir('tarefa_custos_custo', $tarefa_custos_custo);
            $sql->adInserir('tarefa_custos_tarefa', $tarefa_id);
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
        foreach($tarefa['dependencies'] as $campos) {
      	
      		$campo=explode(',',$campos);

      		$dependencias[]=array('tarefa_id'=> $tarefa_id , 'id_unico'=> $campo[0], 'tipo'=> $campo[1], 'latencia'=> $campo[2], 'tipo_latencia'=> $campo[3]);
      		}
    		}
			}

		 // dependencias das tarefas
			 
    foreach($dependencias as $linha) {
    	
    	if ($linha['tipo']=='0') $tipo='TT';
    	elseif ($linha['tipo']=='1') $tipo='TI';
    	elseif ($linha['tipo']=='2') $tipo='IT';
    	elseif ($linha['tipo']=='3') $tipo='II';
    	else $tipo='TI';
   	
    	if ($linha['tipo_latencia']=='3'){
    		//minuto
    		$tipo_latencia='h';
    		$latencia=(int)($linha['latencia']/600);
    		}
    	elseif ($linha['tipo_latencia']=='5'){
    		//hora
    		$tipo_latencia='h';
    		$latencia=(int)($linha['latencia']/600);
    		}
    	elseif ($linha['tipo_latencia']=='7'){
    		//dia
    		$tipo_latencia='d';
    		$latencia=(int)($linha['latencia']/4800);
    		}
    	elseif ($linha['tipo_latencia']=='9'){
    		//semana
    		$tipo_latencia='s';
    		$latencia=(int)($linha['latencia']/24000);
    		}		
    	else{
    		$tipo_latencia='d';
    		$latencia=(int)($linha['latencia']/4800);
    		}
    	if ($linha['tarefa_id'] && isset($id_unico[$linha['id_unico']]) && $id_unico[$linha['id_unico']]){
        $sql->adTabela('tarefa_dependencias');
        $sql->adInserir('dependencias_tarefa_id', (int)$linha['tarefa_id']);
        $sql->adInserir('dependencias_req_tarefa_id', (int)$id_unico[$linha['id_unico']]);
        $sql->adInserir('tipo_dependencia', $tipo);
        $sql->adInserir('latencia', $latencia);
        $sql->adInserir('tipo_latencia', $tipo_latencia);
        $sql->exec();
        $sql->limpar();
      	}
  		}
	
		//corrigir dinamicas
		$sql->adTabela('tarefas');
	  $sql->adAtualizar('tarefa_dinamica',0);
	  $sql->adOnde('tarefa_projeto = '.$projeto_id);
		$sql->exec();
	  $sql->limpar();
	  
	  
		$sql->adTabela('tarefas');
	  $sql->adCampo('DISTINCT tarefa_id');
	  $sql->adOnde('tarefa_projeto='.$projeto_id);
	  $lista_tarefas = $sql->carregarColuna();
		$sql->limpar();
		
		foreach($lista_tarefas as $tarefa_id){
			$sql->adTabela('tarefas');
	    $sql->adCampo('count(tarefa_id)');
	    $sql->adOnde('tarefa_projeto='.$projeto_id);
	    $sql->adOnde('tarefa_superior='.$tarefa_id);
	    $sql->adOnde('tarefa_id!='.$tarefa_id);
	    $qnt = $sql->Resultado();
			$sql->limpar();
			
			if ($qnt){
				$sql->adTabela('tarefas');
	      $sql->adAtualizar('tarefa_dinamica',1);
	      $sql->adOnde('tarefa_id = '.$tarefa_id);
				$sql->exec();
	      $sql->limpar();
				}
			}
	
	  recalcular_duracao_projeto($projeto_id);
	  atualizar_percentagem($projeto_id);
	  return $saida;
		}

	public function tira_quebra($texto){
		return strtr($texto, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
		}
		
  public function visualizar() {
    global $Aplic, $config, $cia_id, $perms;
    $saida = '';
    $data = $this->scrubbedData;
		$data = ($this->formatacao=='utf' ? iconv("UTF-8","UTF-8//IGNORE",$data) : iconv("ISO-8859-1","ISO-8859-1//IGNORE",$data));
    $arquivo_xml = simplexml_load_string($data);
    $projeto_nome =($this->formatacao=='utf' ? utf8_decode($arquivo_xml->Title) : $arquivo_xml->Title);
   
    $saida .= '<tr><td align="right" nowrap="nowrap">'.ucfirst($config['organizacao']).':</td><td width="100%"><input type="hidden" name="cia_id" value="'.$cia_id.'">'.nome_cia($cia_id).'</td></tr>';
    $saida .= $this->_criarSelecaoProjeto($Aplic, $projeto_nome);
    $saida .= '<tr><td nowrap="nowrap" align="right">Dono do Projeto:</td><td>'.mudar_usuario($cia_id, $Aplic->usuario_id, 'project_owner','', 'class="texto" size=1 style="width:250px;"').'<td/></tr>';
    $pstatus =  getSisValor('StatusProjeto');  
    $saida .= '<tr><td align="right" nowrap="nowrap">Situação do Projeto:</td><td>';
    $saida .= selecionaVetor($pstatus, 'project_status', 'size="1" class="text"', 0);
    $saida .= '<td/></tr>';
    $saida .= '<tr><td align="right" nowrap="nowrap">Data de Início:</td><td><input class="texto" type="text" name="project_start_date" value="'.$arquivo_xml->StartDate.'" /></td></tr>';
    $saida .= '<tr><td align="right" nowrap="nowrap">Data Final:</td><td><input class="texto" type="text" name="project_end_date" value="'.$arquivo_xml->FinishDate.'" /></td></tr>';
    $saida .= '<tr><td align="right" nowrap="nowrap">Não importar usuários:</td><td><input class="texto" type="checkbox" name="nouserimport" value="true" onclick="ToggleUserFields()" /></td></tr>';
    $saida .= '<tr><td colspan=20><table width="100%" name="userRelated">';
    $saida .= '<tr><td colspan="2">Usuários:</td></tr>';
   	$saida .= '<tr><td colspan="20"><div ><em>Informação d'.$config['genero_usuario'].'s '.$config['usuarios'].'</em></tr>';
		$sql = new BDConsulta();
    
    $sql->adTabela('usuarios', 'u');
    $sql->esqUnir('contatos','co','co.contato_id = u.usuario_contato');
    $sql->esqUnir('cias','comp','comp.cia_id=co.contato_cia');
    $sql->adCampo('u.*,co.*,concatenar_tres(co.contato_posto,\' \',co.contato_nomeguerra) as full_name,comp.cia_nome');
    $sql->adOnde('contato_cia='.(int)$cia_id);
    $sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
    $usuarios = $sql->Lista();
		$sql->limpar();
    $percent = array(0 => '0', 5 => '5', 10 => '10', 15 => '15', 20 => '20', 25 => '25', 30 => '30', 35 => '35', 40 => '40', 45 => '45', 50 => '50', 55 => '55', 60 => '60', 65 => '65', 70 => '70', 75 => '75', 80 => '80', 85 => '85', 90 => '90', 95 => '95', 100 => '100');
    // designados
    $designados = array();
    $designados[0] = '';
    $gasto = array();
    $tipo_material = array();
   	foreach($arquivo_xml->Resources->children() as $r) {
    	if ($r->Name && $r->Type==1){ 	
    		//é uma pessoa
        $nome=($this->formatacao=='utf' ? utf8_decode($r->Name) : $r->Name);
        $sql->adCampo('usuario_id');
        $sql->adTabela('usuarios');
        $sql->esqUnir('contatos', 'c', 'usuario_contato = contato_id');
        $sql->adOnde('usuario_login LIKE \'%'.addslashes($nome).'%\' OR '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' LIKE \'%'.addslashes($nome).'%\'');
        $r['lid'] = $sql->resultado();
        $sql->limpar();
        $saida .= '<tr>';
      	$saida .= '<td align="right">'.ucwords(strtolower($nome)).':</td>';
      	$saida .= '<td nowrap="nowrap" align="left"><select class="texto" name="users['.(int)$r->UID.']">';
        if (empty($r['lid'])) $saida .= '<option value="0" selected>Mudar</option>\n';
        foreach ($usuarios as $usuario) {
          if ($r['lid'] && $usuario["usuario_id"]==$r['lid']) $designados[(int)$r->UID] = $usuario["contato_posto"].' '.$usuario["contato_nomeguerra"];
          $saida .= '<option value="'.$usuario["usuario_id"].'"'.(!empty($r['lid']) && $usuario["usuario_id"]==$r['lid']?"selected":"").'>'.$usuario["contato_posto"].' '.$usuario["contato_nomeguerra"].'</option>\n';
      		}
        $saida .= '</select></td>';
       	$saida .='</tr>';
       	$designados[(int)$r->UID] = ucwords(strtolower($nome));
  			}
  		elseif ($r->Name){
  			//é um matetial
  			$gasto[(int)$r->UID]=$r->Name;
  			$tipo_material[(int)$r->UID]=$r->MaterialLabel;
  			}
  		}
		$saida .= '</table></td></tr>';
    //Inserir tarefas
    $saida .= '</td></tr>';
    $saida .= '<tr><td colspan="20">Tarefas:</td></tr>';
    $saida .= '<tr><td colspan="20"><table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
    $saida .= '<tr><th>Nome</th><th nowrap="nowrap">Data Inicial</th><th nowrap="nowrap">Data Final</th><th nowrap="nowrap">Alocações de '.$config['usuario'].'</th></tr>';
		$k=-1;
    foreach($arquivo_xml->Tasks->children() as $tarefa) {	
      if ((int)$tarefa->UID>0) {
				$k++;

        $saida .= '<tr style="border:1px solid #000; margin-bottom:4px;">';
        $saida .= '<input type="hidden" name="tasks['.$k.'][UID]" value="'.$tarefa->UID.'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][OUTLINENUMBER]" value="'.$tarefa->OutlineNumber.'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_name]" value="'.($this->formatacao=='utf' ? utf8_decode($tarefa->Name) : $tarefa->Name).'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_description]" value="'.$this->tira_quebra((isset($tarefa->Notes) ? ($this->formatacao=='utf' ? utf8_decode($tarefa->Notes) : $tarefa->Notes) : '')).'" />';
        $prioridade = ($tarefa->Priority> 0) ? 1 : 0;
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_priority]" value="'.$prioridade.'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_start_date]" value="'.$tarefa->Start.'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_end_date]" value="'.$tarefa->Finish.'" />';
        $minhaDuracao = $this->_calcularTrabalho($tarefa->RegularWork, $tarefa->Duration);
        if (isset($tarefa->PercentComplete))$percentualCompletado =$tarefa->PercentComplete;
        elseif (isset($tarefa->Duration) && isset($tarefa->RemainingDuration)){
        
        	$tempo1=explode('H',$tarefa->RemainingDuration);
        	$horas=$tempo1[0];
        	$horas = ltrim($horas, 'PT'); 
        	$tempo2=explode('M',$tempo1[1]);
        	$minutos=$tempo2[0];
					$resta=$horas+($minutos/60);

        	$tempo1=explode('H',$tarefa->Duration);
        	$horas=$tempo1[0];
        	$horas = ltrim($horas, 'PT'); 
        	$tempo2=explode('M',$tempo1[1]);
        	$minutos=$tempo2[0];
					$duracao=$horas+($minutos/60);
					$percentualCompletado=($duracao !=0 ?(100*($duracao-$resta))/$duracao : 0);
        	}
        else $percentualCompletado=0;	
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_duration]" value="'.$minhaDuracao.'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_percent_complete]" value="'.$percentualCompletado.'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_dynamic]" value="'.$tarefa->Type.'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_owner]" value="'.$Aplic->usuario_id.'" />';
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_type]" value="0" />';
        $marco = ($tarefa->Milestone== '1') ? 1 : 0;
        $saida .= '<input type="hidden" name="tasks['.$k.'][task_milestone]" value="'.$marco.'" />';
        if (isset($tarefa->PredecessorLink)) {
          foreach ($tarefa->PredecessorLink as $dependencia) {
          	$saida .= '<input type="hidden" name="tasks['.$k.'][dependencies][]" value="'.$dependencia->PredecessorUID.','.$dependencia->Type.','.$dependencia->LinkLag.','.$dependencia->LagFormat.'" />';
      			}
      		}
        $saida .= '<td>';
        $tarefa_nivel = substr_count($tarefa->OutlineNumber, '.');
        for($i = 0; $i < $tarefa_nivel; $i++) $saida .= '&nbsp;&nbsp;&nbsp;';
				if ($i>1) $saida .=imagem('icones/corner-dots.gif');
				
        $saida .= ($this->formatacao=='utf' ? utf8_decode($tarefa->Name) : $tarefa->Name);
        if ($marco) $saida .= imagem('icones/marco.gif');
        if (!empty($tarefa->Notes)) $saida .= '<br /><hr />'.$this->tira_quebra(($this->formatacao=='utf' ? utf8_decode($tarefa->Notes) : $tarefa->Notes)).'<hr size="2" />';
        $saida .='</td><td>'.substr($tarefa->Start, 0, 10).'</td><td>'.substr($tarefa->Finish, 0, 10).'</td><td nowrap="nowrap">';

        foreach($arquivo_xml->Assignments->children() as $a) {	
          if ((int)$a->TaskUID == (int)$tarefa->UID && array_key_exists((int)$a->ResourceUID, $designados)) {
         	 //pessoa
            if ($this->_calcularTrabalho($tarefa->RegularWork, $tarefa->Duration) > 0) $perc = 100 * $a->Units;
            else $perc =0;
            $saida .= '<div name="userRelated">';
            $saida .= selecionaVetor($designados, 'tasks['.$k.'][resources][]', 'class="texto"', $a->ResourceUID);
            $saida .= '&nbsp;';
            $saida .= selecionaVetor($percent, 'tasks['.$k.'][resources_alloc][]', 'size="1" class="texto"', intval(round($perc/5))*5).'%';
            $saida .= '</div>';
        		}
        	else if ((int)$a->TaskUID == (int)$tarefa->UID) {
         		//material
        		$saida .= '<input type="hidden" name="tasks['.$k.'][gasto_nome][]" value="'.(isset($gasto[(int)$a->ResourceUID]) ? $gasto[(int)$a->ResourceUID] : 'indefinido').(isset($tipo_material[(int)$a->ResourceUID]) ? ' ('.$tipo_material[(int)$a->ResourceUID].')' : '').'" />';
        		$saida .= '<input type="hidden" name="tasks['.$k.'][gasto_quantidade][]" value="'.$a->Units.'" />';
        		$saida .= '<input type="hidden" name="tasks['.$k.'][gasto_custo][]" value="'.$a->Cost.'" />';
        		}	
      		}
        $saida .= '</td></tr>';
        }
  		}
    $saida .= '</table></td></tr>';
    return $saida;
		}

public function loadFile($Aplic) {
	global $config;

  $nome_arquivo = $_FILES['upload_file']['tmp_name'];
  $pos=strrpos($_FILES['upload_file']['name'],".");
  $nomeArquivo=substr($_FILES['upload_file']['name'],0,$pos);
  $arquivo = fopen($nome_arquivo, "r");
  $this->scrubbedData = fread($arquivo, $_FILES['upload_file']['size']);
  fclose($arquivo);
  if (substr_count($this->scrubbedData, '<Resource>') <= 1) echo '<tr><td colspan=20>Nenhum usuário encontrado neste arquivo. Você pode adicionar um novo ou alocar algum já existente no '.$config['gpweb'].' após importar o projeto.</td></tr>';
  $this->proName=$nomeArquivo;
  return true;
	}

  private function _calcularTrabalho($trabalhoPadrao, $duracaoPadrao = '') {
    $offsetHora = strpos($trabalhoPadrao, 'H', 0);
    $offsetMin = strpos($trabalhoPadrao, 'M', 0);
    $horas = substr($trabalhoPadrao, 2, $offsetHora - 2);
    $minutos = substr($trabalhoPadrao, $offsetHora + 1, $offsetMin - $offsetHora - 1);
    $horasTrabalho = $horas + $minutos/60;

    if ($horasTrabalho == 0 && $duracaoPadrao != '') {
      $horasTrabalho = $this->_calcularTrabalho($duracaoPadrao);
  		}

    return round($horasTrabalho, 2);
  	}
	}