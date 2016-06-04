<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

/********************************************************************************************

gpweb\classes\Modelo.class.php

Define a classe de Modelo que manipula os objetos criados módulo de criação de documentos
internos, tais como parte, MDO, etc.

********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

class Modelo {
	var $campo = array();
	var $modelo_tipo=0;
	var $edicao=false;
	var $impressao=false;
	var $modelo_id=0;
	var $paragrafo=0;
	var $modelo_dados_id=0;
	var $modelo=null;
	var $qnt=0;

	function Modelo() {
		}

	function set_modelo($modelo){
		$this->modelo=$modelo;
		}


	function set_modelo_tipo($modelo_tipo){
		$this->modelo_tipo=$modelo_tipo;
		}

	function set_modelo_id($modelo_id){
		$this->modelo_id=$modelo_id;
		}

	function set_campo($tipo, $dados=null, $posicao=null, $extra=null, $larg_max=null, $outro_campo=null){
		$this->qnt++;
		if (!$posicao) $pos=count($this->campo)+1;
		else $pos=$posicao;
		$this->campo[$pos]=array('tipo' => $tipo, 'dados' => $dados, 'extra' => $extra, 'larg_max'=> $larg_max, 'outro_campo'=> $outro_campo);
		}

	function get_campo($posicao){
		global $config, $Aplic, $sem_assinatura;
		$tipo=$this->campo[$posicao]['tipo'];
		$saida='';

		switch ($tipo) {
			

			case 'protocolo_secao':
				$campos_protocolo=(array)$this->campo[$posicao]['dados'];
				$sql = new BDConsulta;
				$sql->adTabela('modelos');
				$sql->adCampo('modelo_protocolo');
				$sql->adOnde('modelo_id = '.$this->modelo_id);
				$protocolo = $sql->Resultado();
				$num_protocolo=$protocolo;
				$sql->limpar();


				if (!$this->edicao){
					if (!$protocolo && isset($campos_protocolo[0]) &&  isset($campos_protocolo[1])){
						$sql->adTabela('depts');
						$sql->adCampo('dept_nup, dept_prefixo, dept_sufixo');
						$sql->adOnde('dept_id='.(int)$campos_protocolo[0]);
						$dept = $sql->Linha();
						$sql->limpar();

						if ($dept['dept_nup']){
							$protocolo=inserir_NUP($campos_protocolo[1], $dept['dept_nup']);

							$sql->adTabela('depts');
							$sql->adAtualizar('dept_qnt_nr', $campos_protocolo[1]);
							$sql->adOnde('dept_id = '.(int)$campos_protocolo[0]);
							$sql->exec();
							$sql->limpar();

							$sql->adTabela('modelos');
							$sql->adAtualizar('modelo_protocolo', $protocolo);
							$sql->adAtualizar('modelo_protocolista', $Aplic->usuario_id);
							$sql->adAtualizar('modelo_data_protocolo', date('Y-m-d H:i:s'));
							$sql->adOnde('modelo_id ='.(int)$this->modelo_id);
							$sql->exec();
							$sql->limpar();
							}
						else {
							$protocolo=$dept['dept_prefixo'].$campos_protocolo[1].$dept['dept_sufixo'];
							$sql->adTabela('depts');
							$sql->adAtualizar('dept_qnt_nr', $campos_protocolo[1]);
							$sql->adOnde('dept_id = '.(int)$campos_protocolo[0]);
							$sql->exec();
							$sql->limpar();
							$sql->adTabela('modelos');
							$sql->adAtualizar('modelo_protocolo', $protocolo);
							$sql->adAtualizar('modelo_protocolista', $Aplic->usuario_id);
							$sql->adAtualizar('modelo_data_protocolo', date('Y-m-d H:i:s'));
							$sql->adOnde('modelo_id ='.(int)$this->modelo_id);
							$sql->exec();
							$sql->limpar();
							}
						}
					}
				if ($this->edicao) {
					if (isset($campos_protocolo[0]) && isset($campos_protocolo[1]) && $campos_protocolo[1]){
						$saida.='<input type="hidden" class="texto" id="dept_protocolo" name="dept_protocolo" value="'.(int)$campos_protocolo[0].'" />';
						$saida.='<input type="hidden" class="texto" id="dept_qnt_nr" name="dept_qnt_nr" value="'.(int)$campos_protocolo[1].'" />';
						$saida.=$protocolo;
						}
					else $saida.='<input type="hidden" class="texto" id="dept_protocolo" name="dept_protocolo" value="" /><table><tr><td><div id="protocolo_secao"></div></td><td>'.($this->campo[$posicao]['dados'] ? '' : botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()')).'</td></tr></table>';
					}
				else $saida.=$protocolo;
				if (in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'tipo_modelo':
				$sql = new BDConsulta;
				$sql->adTabela('modelos_tipo');
				$sql->adCampo('modelo_tipo_id, modelo_tipo_nome');
				$sql->adOnde('organizacao='.(int)$config['militar']);
				$modelos_tipo = array(0 => '')+$sql->listaVetorChave('modelo_tipo_id','modelo_tipo_nome');
				$sql->limpar();
				if ($this->edicao)	$saida.=selecionaVetor($modelos_tipo, 'campo_'.$posicao, ($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="1" class="texto"') , ($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : ''));
				else $saida.=(isset($modelos_tipo[$this->campo[$posicao]['dados']]) ? $modelos_tipo[$this->campo[$posicao]['dados']] : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'fecho':
				$fechos = getSisValor('Fecho');
				if ($this->edicao)	$saida.=selecionaVetor($fechos, 'campo_'.$posicao, ($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="1" class="texto"') , ($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : ''));
				else $saida.=(isset($fechos[$this->campo[$posicao]['dados']]) ? $fechos[$this->campo[$posicao]['dados']] : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'vocativo':
				$vocativos = getSisValor('Vocativo');
				if ($this->edicao)	$saida.=selecionaVetor($vocativos, 'campo_'.$posicao, ($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="1" class="texto"') , ($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : ''));
				else $saida.=(isset($vocativos[$this->campo[$posicao]['dados']]) ? $vocativos[$this->campo[$posicao]['dados']] : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'vocativo_end':
				$vocativos_end = getSisValor('VocativoEnd');
				if ($this->edicao)	$saida.=selecionaVetor($vocativos_end , 'campo_'.$posicao, ($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="1" class="texto"') , ($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : ''));
				else $saida.=(isset($vocativos_end[$this->campo[$posicao]['dados']]) ? $vocativos_end[$this->campo[$posicao]['dados']] : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'legenda':
				if ($this->edicao)	$saida.=$this->campo[$posicao]['extra'];
				break;

			case 'numeracao_cresc':
				if ($this->edicao || !$this->campo[$posicao]['outro_campo'] || ($this->campo[$posicao]['outro_campo'] && $this->campo[$this->campo[$posicao]['outro_campo']]['dados'])){
					$this->paragrafo++;
					$saida.=$this->paragrafo;
					}
				break;

			case 'numeracao_aumentar':
				$this->paragrafo++;
				break;

			case 'numeracao_diminuir':
				$this->paragrafo--;
				break;

			case 'numeracao_zerar':
				$this->paragrafo=0;
				break;


			case 'botao_organizacao':
				if ($this->edicao) {
					$saida.=botao($config['organizacao'], ucfirst($config['organizacao']), 'Selecionar um'.$config['genero_organizacao'].' '.$config['organizacao'].'.','','popDadosOrganizacao('.$posicao.', \'tudo\');');
					}
				else $saida.='';
				if (($this->edicao) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_nome':
				if ($this->edicao) $saida.='<input type="text" class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_nome' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_end':
				$texto=$this->campo[$posicao]['dados'];
				if ($this->edicao) $saida.='<textarea class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_end' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : '').' />'.$texto.'</textarea>';
				else {
					$saida.=($this->campo[$posicao]['extra'] ? '<span '.$this->campo[$posicao]['extra'].'>' : '').$texto.($this->campo[$posicao]['extra'] ? '</span>' : '');
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_cep':
				if ($this->edicao) $saida.='<input type="text" class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_cep' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_tel1':
				if ($this->edicao) $saida.='<input type="text" class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_tel1' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_nome':
				if ($this->edicao) $saida.='<input type="text" class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_nome' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_fax':
				if ($this->edicao) $saida.='<input type="text" class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_fax' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_cidade':
				if ($this->edicao) $saida.='<input type="text" class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_cidade' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_estado':
				if ($this->edicao) $saida.='<input type="text" class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_estado' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;

			case 'organizacao_end_completo':
				$texto=$this->campo[$posicao]['dados'];
				if ($this->edicao) $saida.='<textarea class="texto" name="campo_'.$posicao.'" '.($this->campo[$posicao]['outro_campo'] ? 'id="campo'.$this->campo[$posicao]['outro_campo'].'_end_completo' : '').'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : '').' />'.$texto.'</textarea>';
				else {
					$saida.=($this->campo[$posicao]['extra'] ? '<span '.$this->campo[$posicao]['extra'].'>' : '').$texto.($this->campo[$posicao]['extra'] ? '</span>' : '');
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos()))	$this->modelo->bloco('bloco'.$posicao);
				break;
			
			case 'organizacao_logo': 
				$sql = new BDConsulta();
				$sql->adTabela('cias');
				$sql->adCampo('cia_logo');
				$sql->adOnde('cia_id = '.(int)$Aplic->usuario_cia);
				$saida = $sql->resultado(); 
				if (!$saida || !file_exists(($config['url_arquivo'] ? $config['url_arquivo'] : '.').'/arquivos/organizacoes/'.$saida)) $saida='';
				else $saida='<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : '.').'/arquivos/organizacoes/'.$saida.'" alt="" border=0 />';
				$sql->limpar();
				if ($this->campo[$posicao]['dados'] && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			
			
			case 'nome_organizacao':
				if ($this->edicao) {
					if (!$this->campo[$posicao]['dados']){
						$sql = new BDConsulta;
						$sql->adTabela('cias');
						$sql->adCampo('cia_nome');
						$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
						$this->campo[$posicao]['dados'] = $sql->Resultado();
						$sql->limpar();
						}
					$saida.='<input type="text" class="texto" id="campo_'.$posicao.'" name="campo_'.$posicao.'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />'.botao($config['organizacao'], ucfirst($config['organizacao']), 'Selecionar o nome de um'.$config['genero_organizacao'].' '.$config['organizacao'].'.','','popDadosOrganizacao('.$posicao.', \'nome\');');
					}
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'endereco':
				$texto=$this->campo[$posicao]['dados'];
				if ($this->edicao) $saida.='<textarea id="campo_'.$posicao.'" name="campo_'.$posicao.'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : '').'>'.($texto ? $texto : '').'</textarea>'.botao($config['organizacao'], ucfirst($config['organizacao']), 'Selecionar o endereço de um'.$config['genero_organizacao'].' '.$config['organizacao'].'.','','popDadosOrganizacao('.$posicao.', \'endereco\');');
				else {
					$saida.=($this->campo[$posicao]['extra'] ? '<span '.$this->campo[$posicao]['extra'].'>' : '').$texto.($this->campo[$posicao]['extra'] ? '</span>' : '');
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'paragrafo_num':
				$texto=$this->campo[$posicao]['dados'];
				if ($this->edicao) {
					$this->paragrafo++;
					$saida.='<b>'.$this->paragrafo.'.</b><textarea id="campo_'.$posicao.'" name="campo_'.$posicao.'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : '').'>'.($texto ? $texto : '').'</textarea>';
					}
				else {
					if ($this->campo[$posicao]['dados']) {
						$this->paragrafo++;
						if ($this->campo[$posicao]['larg_max']) $texto=wordwrap( $texto, $this->campo[$posicao]['larg_max'], "<BR>", 1);
						$saida.=($this->campo[$posicao]['extra'] ? '<span '.$this->campo[$posicao]['extra'].'>' : '').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$this->paragrafo.'.</b>'.$texto.($this->campo[$posicao]['extra'] ? '</span>' : '');
						}
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'bloco_sem_paragrafo':
				if ($this->edicao) $saida.='<textarea data-gpweb-cmp="ckeditor" id="campo_'.$posicao.'" name="campo_'.$posicao.'">'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'</textarea>';
				else {
					$this->campo[$posicao]['dados']=str_ireplace("<p>","",$this->campo[$posicao]['dados']);
					$this->campo[$posicao]['dados']=str_ireplace("</p>","",$this->campo[$posicao]['dados']);
					$saida.=($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '');
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'paragrafo_num_for':
				if ($this->edicao) {
					$this->paragrafo++;
					$saida.='<b>'.$this->paragrafo.'.</b>';
					$saida.='<textarea data-gpweb-cmp="ckeditor" id="campo_'.$posicao.'" name="campo_'.$posicao.'">'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'</textarea>';
					}
				else {
					if ($this->campo[$posicao]['dados']) {
						$this->paragrafo++;
						//retirar os parágrafos internos
						$this->campo[$posicao]['dados']=str_ireplace("<p>","",$this->campo[$posicao]['dados']);
						$this->campo[$posicao]['dados']=str_ireplace("</p>","",$this->campo[$posicao]['dados']);
						}
					$saida.=($this->campo[$posicao]['dados'] ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$this->paragrafo.'.</b>'.$this->campo[$posicao]['dados'] : '');
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'remetente':
				$remetente=(array)$this->campo[$posicao]['dados'];
				if ($this->edicao) {
					$saida.='<input type="hidden" class="texto" id="remetente_'.$posicao.'" name="remetente_'.$posicao.'" value="'.(isset($remetente[0]) ? $remetente[0] : $Aplic->usuario_id).'" />';
					$saida.='<input type="text" '.($this->campo[$posicao]['larg_max']? 'maxlength="'.$this->campo[$posicao]['larg_max'].'" ' : '').'class="texto" id="remetente_funcao_'.$posicao.'" name="remetente_funcao_'.$posicao.'" value="'.(isset($remetente[1]) ? $remetente[1] : $Aplic->usuario_funcao).'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />'.botao($config['usuarios'], ucfirst($config['usuarios']), 'Selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' para ser '.$config['genero_usuario'].' responsável pela assinatura.','','popRemetente('.$posicao.');');
					}
				else $saida.=(isset($remetente[1]) ? $remetente[1] : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;


			case 'urgente':
				$vetor=array('' => '', 'URGENTE' => 'URGENTE', 'URGENTÍSSIMO' => 'URGENTÍSSIMO');
				if ($this->edicao) $saida.=selecionaVetor($vetor, 'campo_'.$posicao, 'size="1" class="texto"', $this->campo[$posicao]['dados']);
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'protocolo':
			if (!$this->campo[$posicao]['dados']){
					$sql = new BDConsulta;
					$sql->adTabela('modelos');
					$sql->adCampo('modelo_protocolo');
					$sql->adOnde('modelo_id = '.$this->modelo_id);
					$protocolo = $sql->Resultado();
					$num_protocolo=$protocolo;
					$sql->limpar();

					if (!$protocolo && $config['protocolo_automatico'] && !$this->edicao && !$this->campo[$posicao]['dados']){
						$sql->adTabela('cias');
						$sql->adCampo('cia_nup, cia_qnt_nup, cia_qnt_nr, cia_prefixo, cia_sufixo');
						$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
						$linha = $sql->linha();
						$sql->limpar();

						if ($linha['cia_nup']){
							$protocolo=inserir_NUP($linha['cia_qnt_nup']+1, $linha['cia_nup']);

							$sql->adTabela('cias');
							$sql->adAtualizar('cia_qnt_nup', ($linha['cia_qnt_nup']+1));
							$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
							$sql->exec();
							$sql->limpar();

							$sql->adTabela('modelos');
							$sql->adAtualizar('modelo_protocolo', $protocolo);
							$sql->adAtualizar('modelo_protocolista', $Aplic->usuario_id);
							$sql->adAtualizar('modelo_data_protocolo', date('Y-m-d H:i:s'));
							$sql->adOnde('modelo_id ='.(int)$this->modelo_id);
							$sql->exec();
							$sql->limpar();
							}
						else {
							$protocolo=$linha['cia_prefixo'].($linha['cia_qnt_nr']+1).$linha['cia_sufixo'];
							$sql->adTabela('cias');
							$sql->adAtualizar('cia_qnt_nr', ($linha['cia_qnt_nr']+1));
							$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
							$sql->exec();
							$sql->limpar();
							$sql->adTabela('modelos');
							$sql->adAtualizar('modelo_protocolo', $protocolo);
							$sql->adAtualizar('modelo_protocolista', $Aplic->usuario_id);
							$sql->adAtualizar('modelo_data_protocolo', date('Y-m-d H:i:s'));
							$sql->adOnde('modelo_id ='.(int)$this->modelo_id);
							$sql->exec();
							$sql->limpar();
							}
						}

					if (!$protocolo && !$this->impressao) $protocolo=dica('Automático','A numeração será incluida automaticamente após este documento ser protocolado').'auto'.dicaF();
					elseif (!$protocolo && $this->impressao ) $protocolo ='auto';
					}

				if ($this->edicao) {
					$saida.='<table><tr><td>'.($this->campo[$posicao]['dados'] ? 's/nº' : $protocolo).'</td><td>&nbsp;&nbsp;&nbsp;'.dica('Sem Número', 'Caso esta opção seja marcada, este documento não terá número de protocolo.').'<input type="checkbox" name="campo_'.$posicao.'" value="1" '.($this->campo[$posicao]['dados'] ? 'checked="checked" ' : '').($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : '').' />sem nº'.dicaF().'</td></tr></table>';
					}
				else $saida.=($this->campo[$posicao]['dados'] ? 's/nº' : $protocolo);
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'cidade':
				if(!$this->campo[$posicao]['dados']){
					$sql = new BDConsulta;
					$sql->adTabela('cias');
					$sql->esqUnir('municipios','municipios','municipio_id=cia_cidade');
					$sql->adCampo('municipio_nome');
					$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
					$this->campo[$posicao]['dados'] = $sql->Resultado();
					$sql->limpar();
					}

				if ($this->edicao) $saida.='<input type="text" '.($this->campo[$posicao]['larg_max']? 'maxlength="'.$this->campo[$posicao]['larg_max'].'" ' : '').'class="texto" name="campo_'.$posicao.'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : '').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'cabecalho':
				if(!$this->campo[$posicao]['dados']){
					$sql = new BDConsulta;
					$sql->adTabela('cias');
					$sql->adCampo('cia_cabacalho');
					$sql->adOnde('cia_id = '.$Aplic->usuario_cia);
					$this->campo[$posicao]['dados'] = $sql->Resultado();
					$sql->limpar();
					}

				if ($this->edicao) $saida.='<textarea data-gpweb-cmp="ckeditor" id="campo_'.$posicao.'" name="campo_'.$posicao.'">'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'</textarea>';
				else $saida.=($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;


			case 'assinatura':
				$assinatura=(array)$this->campo[$posicao]['dados'];
				$caminho='';
				$aprovado ='';
				

				if ($this->edicao) {				
					$saida.='<input type="hidden" class="texto" name="campo_'.$posicao.'" value="" />';
					$saida.='<table><tr><td>'.($config['militar'] < 10 ? dica('Posto/Grad', 'Escreva o posto/graduação.').'Posto/Grad:' : dica('Pronome de Tratamento', 'Selecione o pronome de tratamento.').'Pron. Trat.:').dicaF().'<input type="text" class="texto" id="posto_'.$posicao.'" name="posto_'.$posicao.'" value="'.(isset($assinatura[0]) && $assinatura[0] ? $assinatura[0] : $Aplic->usuario_posto).'" size="5" />'.($config['militar'] < 10 ? dica('Nome de Guerra', 'Escreva o nome de guerra.').'Nome:' : dica('Nome', 'Escreva o nome.').'Nome:').dicaF().'<input type="text" '.($this->campo[$posicao]['larg_max']? 'maxlength="'.$this->campo[$posicao]['larg_max'].'" ' : '').'class="texto" id="nomeguerra_'.$posicao.'" name="nomeguerra_'.$posicao.'" value="'.(isset($assinatura[1]) && $assinatura[1] ? $assinatura[1] : ($config['militar'] < 10 ? strtoupper($Aplic->usuario_nome_completo) : $Aplic->usuario_nome_completo)).'" size="50" /></td><td><input name="ordem_postonome_'.$posicao.'" type="checkbox" value="1" '.((isset($assinatura[4]) && $assinatura[4]) || ($config['militar'] < 10 && !isset($assinatura[0])) ? 'checked="checked" ' : '').'>'.imagem('icones/nome_posto.png','Nome - '.($config['militar'] < 10 ? 'Posto/Grad' : 'Pron. Trat.'), 'Clique neste ícone '.imagem('icones/nome_posto.png').' para que o '.($config['militar'] < 10 ? 'posto/graduação' : 'pronome de tratameto').' seja exibido após o nome.').'</td></tr></table>';
					$saida.='<table><tr><td colspan=2 align="center"><table><tr><td>'.botao($config['usuarios'], ucfirst($config['usuarios']), 'Selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' para ser '.$config['genero_usuario'].' responsável pela assinatura.','','popAssinatura('.$posicao.');').'</td><td>'.dica('Função', 'Escreva a função que exerce.').'Função:'.dicaF().'<input type="text" class="texto" id="funcao_'.$posicao.'" name="funcao_'.$posicao.'" value="'.(isset($assinatura[2]) && $assinatura[2] ? $assinatura[2] : $Aplic->usuario_funcao).'" size="25" /><input type="hidden" class="texto" id="assinante_'.$posicao.'" name="assinante_'.$posicao.'" value="'.(isset($assinatura[3]) && $assinatura[3] ? $assinatura[3] : $Aplic->usuario_id).'" /></td></tr></table></td></tr></table>';
					}
				else {
					
					$sql = new BDConsulta;
					$sql->adTabela('modelos');
					$sql->esqUnir('usuarios', 'usuarios', 'usuario_id=modelo_autoridade_aprovou');
					$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
					$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS responsavel, contato_nomecompleto');
					$sql->adCampo('modelo_autoridade_aprovou AS usuario_assinou, usuario_assinatura, modelo_aprovou_nome AS nome_assinatura, modelo_aprovou_funcao AS funcao_assinatura');
					$sql->adOnde('modelo_id = '.$this->modelo_id);
					$aprovado = $sql->linha();
					$sql->limpar();
					
					$sql->adTabela('modelos');
					$sql->esqUnir('usuarios', 'usuarios', 'usuario_id=modelo_autoridade_assinou');
					$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
					$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS responsavel, contato_nomecompleto');
					$sql->adCampo('modelo_autoridade_assinou AS usuario_assinou, usuario_assinatura, modelo_assinatura_nome AS nome_assinatura, modelo_assinatura_funcao AS funcao_assinatura');
					$sql->adOnde('modelo_id = '.$this->modelo_id);
					$assinado = $sql->linha();
					$sql->limpar();
					
					
					$assinatura=(isset($assinado['usuario_assinou']) && $assinado['usuario_assinou'] ? $assinado : $aprovado);
					
					$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
					$saida.='<table cellspacing=0 cellpadding=0><tr><td align="center" height="70" valign="bottom">'.(!$sem_assinatura && $assinatura['usuario_assinou'] && $assinatura['usuario_assinatura'] && file_exists($base_dir.'/arquivos/assinaturas/'.$assinatura['usuario_assinatura']) ? '<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/assinaturas/'.$assinatura['usuario_assinatura'].'" />' : '___________________________________________').'</td></tr>';
					$saida.='<tr><td align="center" style="font-weight:bold; font-weight:bold; font-family:Times New Roman, Times, serif; font-size:12pt;">'.($assinatura['contato_nomecompleto'] ? $assinatura['contato_nomecompleto'] : $assinatura['responsavel']).'</td></tr>';
					$saida.='<tr><td align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$assinatura['funcao_assinatura'].'</td></tr></table>';
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;


			case 'impedimento':
				$assinatura=(array)$this->campo[$posicao]['dados'];
				$caminho='';
				$aprovado ='';
				if (isset($assinatura[7]) && $assinatura[7]){
					$sql = new BDConsulta;
					$sql->adTabela('usuarios');
					$sql->adCampo('usuario_assinatura');
					$sql->adOnde('usuario_id = '.$assinatura[7]);
					$caminho = $sql->Resultado();
					$sql->limpar();

					$sql->adTabela('modelos');
					$sql->adCampo('modelo_autoridade_aprovou');
					$sql->adOnde('modelo_id = '.$this->modelo_id);
					$aprovado = $sql->Resultado();
					$sql->limpar();
					}
				$caminhor='';
				if (isset($assinatura[8]) && $assinatura[8]){
					$sql = new BDConsulta;
					$sql->adTabela('usuarios');
					$sql->adCampo('usuario_assinatura');
					$sql->adOnde('usuario_id = '.$assinatura[8]);
					$caminhor = $sql->Resultado();
					$sql->limpar();

					$sql->adTabela('modelos');
					$sql->adCampo('modelo_autoridade_aprovou');
					$sql->adOnde('modelo_id = '.$this->modelo_id);
					$aprovado = $sql->Resultado();
					$sql->limpar();
					}

				if ($this->edicao) {
					$saida.='<table cellspacing=0 cellpadding=0><tr><td height="30" valign="top"><input name="impedimento_'.$posicao.'" type="checkbox" onclick="var a=document.getElementById(\'assina_no_impedimento_'.$posicao.'\'); if (a.style.display) a.style.display=\'\'; else a.style.display=\'none\';" value="1" '.(isset($assinatura[0]) && $assinatura[0] ? 'checked="checked" ' : '').'>impedimento</td></tr>';
					$saida.='<tr><td align="center" valign="bottom">___________________________________________________________________</td></tr>';
					$saida.='<tr><td><table><tr><td>'.($config['militar'] < 10 ? dica('Posto/Grad', 'Escreva o posto/graduação.').'Posto/Grad:' : dica('Pronome de Tratamento', 'Selecione o pronome de tratamento.').'Pron. Trat.:').dicaF().'<input type="text" class="texto" id="posto_'.$posicao.'" name="posto_'.$posicao.'" value="'.(isset($assinatura[1]) && $assinatura[1] ? $assinatura[1] : $Aplic->usuario_posto).'" size="5" />'.($config['militar'] < 10 ? dica('Nome de Guerra', 'Escreva o nome de guerra.').'Nome:' : dica('Nome', 'Escreva o nome.').'Nome:').dicaF().'<input type="text" '.($this->campo[$posicao]['larg_max']? 'maxlength="'.$this->campo[$posicao]['larg_max'].'" ' : '').'class="texto" id="nomeguerra_'.$posicao.'" name="nomeguerra_'.$posicao.'" value="'.(isset($assinatura[2]) && $assinatura[2] ? $assinatura[2] : ($config['militar'] < 10 ? strtoupper($Aplic->usuario_nome_completo) : $Aplic->usuario_nome_completo)).'" size="50" /></td><td><input name="ordem_postonome_'.$posicao.'" type="checkbox" value="1" '.((isset($assinatura[9]) && $assinatura[9]) || ($config['militar'] < 10 && !isset($assinatura[1]))  ? 'checked="checked" ' : '').'>'.imagem('icones/nome_posto.png','Nome - '.($config['militar'] < 10 ? 'Posto/Grad' : 'Pron. Trat.'), 'Clique neste ícone '.imagem('icones/nome_posto.png').' para que o '.($config['militar'] < 10 ? 'posto/graduação' : 'pronome de tratameto').' seja exibido após o nome.').'</td></tr></table></td></tr>';
					$saida.='<table><tr><td colspan=2 align="center"><table><tr><td>'.botao($config['usuarios'], ucfirst($config['usuarios']), 'Selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' para ser '.$config['genero_usuario'].' responsável pela assinatura.','','popAssinatura('.$posicao.');').'</td><td>'.dica('Função', 'Escreva a função que exerce.').'Função:'.dicaF().'<input type="text" '.($this->campo[$posicao]['larg_max']? 'maxlength="'.$this->campo[$posicao]['larg_max'].'" ' : '').'class="texto" id="funcao_'.$posicao.'" name="funcao_'.$posicao.'" value="'.(isset($assinatura[3]) && $assinatura[3] ? $assinatura[3] : $Aplic->usuario_funcao).'" size="25" /><input type="hidden" class="texto" id="assinante_'.$posicao.'" name="assinante_'.$posicao.'" value="'.(isset($assinatura[7]) && $assinatura[7] ? $assinatura[7] : $Aplic->usuario_id).'" /></td></tr></table></td></tr>';



					$saida.='<tr id="assina_no_impedimento_'.$posicao.'" '.($assinatura[0] ? '' : 'style="display:none"').'><td><table cellspacing=0 cellpadding=0>';
					$saida.='<tr><td align="left" valign="bottom"><b>Assina pelo impedido:</b></td></tr>';
					$saida.='<tr><td align="center" height="70" valign="bottom">___________________________________________________________________</td></tr>';
					$saida.='<tr><td><table><tr><td>'.($config['militar'] < 10 ? dica('Posto/Grad', 'Escreva o posto/graduação.').'Posto/Grad:' : dica('Pronome de Tratamento', 'Selecione o pronome de tratamento.').'Pron. Trat.:').dicaF().'<input type="text" class="texto" id="postor_'.$posicao.'" name="postor_'.$posicao.'" value="'.(isset($assinatura[4]) && $assinatura[4] ? $assinatura[4] : $Aplic->usuario_posto).'" size="5" />'.($config['militar'] < 10 ? dica('Nome de Guerra', 'Escreva o nome de guerra.').'Nome:' : dica('Nome', 'Escreva o nome.').'Nome:').dicaF().'<input type="text" '.($this->campo[$posicao]['larg_max']? 'maxlength="'.$this->campo[$posicao]['larg_max'].'" ' : '').'class="texto" id="nomeguerrar_'.$posicao.'" name="nomeguerrar_'.$posicao.'" value="'.(isset($assinatura[5]) && $assinatura[5] ? $assinatura[5] : ($config['militar'] < 10 ? strtoupper($Aplic->usuario_nome_completo) : $Aplic->usuario_nome_completo)).'" size="50" /><input type="hidden" class="texto" id="assinanter_'.$posicao.'" name="assinanter_'.$posicao.'" value="'.(isset($assinatura[8]) && $assinatura[8] ? $assinatura[8] : $Aplic->usuario_id).'" /></td><td><input name="ordem_postonomer_'.$posicao.'" type="checkbox" value="1" '.((isset($assinatura[10]) && $assinatura[10]) || ($config['militar'] < 10 && !isset($assinatura[4]))  ? 'checked="checked" ' : '').'>'.imagem('icones/nome_posto.png','Nome - '.($config['militar'] < 10 ? 'Posto/Grad' : 'Pron. Trat.'), 'Clique neste ícone '.imagem('icones/nome_posto.png').' para que o '.($config['militar'] < 10 ? 'posto/graduação' : 'pronome de tratameto').' seja exibido após o nome.').'</td></tr></table></td></tr>';
					$saida.='<table><tr><td colspan=2 align="center"><table><tr><td>'.botao($config['usuarios'], ucfirst($config['usuarios']), 'Selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' para ser '.$config['genero_usuario'].' responsável pela assinatura.','','popAssinaturaImpedido('.$posicao.');').'</td><td>'.dica('Função', 'Escreva a função que exerce.').'Função:'.dicaF().'<input type="text" '.($this->campo[$posicao]['larg_max']? 'maxlength="'.$this->campo[$posicao]['larg_max'].'" ' : '').'class="texto" id="funcaor_'.$posicao.'" name="funcaor_'.$posicao.'" value="'.(isset($assinatura[6]) && $assinatura[6] ? $assinatura[6] : $Aplic->usuario_funcao).'" size="25" /></td></tr></table></td></tr>';
					$saida.='</table></td></tr>';
					$saida.='</table>';
					}
				else {
					if (isset($assinatura[0]) && $assinatura[0]){
						$saida.='<table cellspacing=0 cellpadding=0><tr><td align="center" valign="bottom" style="font-family:Times New Roman, Times, serif; font-size:12pt;">No impedimento de</td></tr>';
						$saida.='<tr><td align="center" style="font-weight:bold; font-family:Times New Roman, Times, serif; font-size:12pt;">'.(isset($assinatura[9]) && $assinatura[9] ? (isset($assinatura[2]) && $assinatura[2] ? $assinatura[2] : '').(isset($assinatura[1]) && $assinatura[1] ? ' - '.$assinatura[1] : '') : (isset($assinatura[1]) && $assinatura[1] ? $assinatura[1].' ' : '').(isset($assinatura[2]) && $assinatura[2] ? $assinatura[2] : '')).'</td></tr>';
						$saida.='<tr><td align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.(isset($assinatura[3]) && $assinatura[3] ? $assinatura[3] : '').'</td></tr>';

						$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

						$saida.='<tr><td align="center" height="70" valign="bottom">'.(!$sem_assinatura && $aprovado && $caminhor && file_exists($base_dir.'/arquivos/assinaturas/'.$caminhor) ? '<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/assinaturas/'.$caminhor.'" />' : '___________________________________________').'</td></tr>';
						$saida.='<tr><td align="center" style="font-weight:bold; font-family:Times New Roman, Times, serif; font-size:12pt;">'.(isset($assinatura[10]) && $assinatura[10] ? (isset($assinatura[5]) && $assinatura[5] ? $assinatura[5] : '').(isset($assinatura[4]) && $assinatura[4] ? ' - '.$assinatura[4] : '') : (isset($assinatura[4]) && $assinatura[4] ? $assinatura[4].' ' : '').(isset($assinatura[5]) && $assinatura[5] ? $assinatura[5] : '')).'</td></tr>';
						$saida.='<tr><td align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.(isset($assinatura[6]) && $assinatura[6] ? $assinatura[6] : '').'</td></tr></table>';
						}
					else{
						//sem no impedimento
						$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
						$saida.='<table cellspacing=0 cellpadding=0><tr><td align="center" height="70" valign="bottom">'.(!$sem_assinatura && $aprovado && $caminho && file_exists($base_dir.'/arquivos/assinaturas/'.$caminho) ? '<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/assinaturas/'.$caminho.'" />' : '___________________________________________').'</td></tr>';
						$saida.='<tr><td align="center" style="font-weight:bold; font-family:Times New Roman, Times, serif; font-size:12pt;">'.
						(isset($assinatura[9]) && $assinatura[9] ? ((isset($assinatura[2]) && $assinatura[2] ? $assinatura[2] : '')).(isset($assinatura[1]) && $assinatura[1] ? ' - '.$assinatura[1] : '') : ((isset($assinatura[1]) && $assinatura[1] ? $assinatura[1] : '').' '.(isset($assinatura[2]) && $assinatura[2] ? $assinatura[2] : ''))).'</td></tr>';
						$saida.='<tr><td align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.(isset($assinatura[3]) && $assinatura[3] ? $assinatura[3] : '').'</td></tr></table>';
						}
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'destinatarios':
				if ($this->edicao) {
					$destinararios=(array)$this->campo[$posicao]['dados'];
					$saida.='<div id="destinatarios_'.$posicao.'">';
					if (count($destinararios)>1){
						for ($i=1; $i <count($destinararios); $i++)	$saida.='<font size=1>&nbsp;'.nome_usuario($destinararios[$i][0]).' - </font><input type="text" class="texto" name="funcao_'.$posicao.'" style="width:100px" value="'.$destinararios[$i][1].'"><input type="hidden" name="nome_dest_'.$posicao.'" value="'.$destinararios[$i][0].'"><a  href="javascript: void(0);" onclick=\'var divIdName="atual_'.$posicao.'_'.$i.'"; env.campo_atual.value='.(int)$posicao.'; removerElemento("atual_'.$posicao.'_'.$i.'")\'>'.imagem("icones/excluir.gif").'</a><br>';
						}
					$saida.='</div>';
					$saida.='<textarea name="campo_'.$posicao.'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : '').'>'.(isset($destinararios[0]) ? $destinararios[0] : '').'</textarea>';
					$saida.=botao('destinatários', 'Destinatários', 'Selecionar destinatários.','','popContatos('.$posicao.');');
					$saida.='<input type=hidden name="lista_destinatarios_'.$posicao.'" id="lista_destinatarios_'.$posicao.'"  value="">';
					$saida.='<input type=hidden name="funcao_destinatarios_'.$posicao.'" id="funcao_destinatarios_'.$posicao.'"  value="">';
					$saida.='<input type=hidden name="campos_destinatario" id="campos_destinatario" value="'.$posicao.'">';
					}
				else {
					$destinararios=(array)$this->campo[$posicao]['dados'];
					$saida.=$destinararios[0];
					if (count($destinararios)>1){
						for ($i=1; $i <count($destinararios); $i++)	$saida.=($destinararios[0] || $i>1 ? ', ':'').$destinararios[$i][1];
						}
					}
				if (($this->edicao || count($this->campo[$posicao]['dados'])) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'do':
				if ($this->edicao) $saida.='<select class="texto" name="campo_'.$posicao.'" size="1"><option value="Do" '.($this->campo[$posicao]['dados']=='Do' ? 'checked="checked"' : '').'>Do</option><option value="Da" '.($this->campo[$posicao]['dados']=='Da' ? 'checked="checked"' : '').'>Da</option></select>';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'ao':
				if ($this->edicao) $saida.='<select class="texto" name="campo_'.$posicao.'" size="1"><option value="Ao" '.($this->campo[$posicao]['dados']=='Ao' ? 'checked="checked"' : '').'>Ao</option><option value="À" '.($this->campo[$posicao]['dados']=='À' ? 'checked="checked"' : '').'>À</option></select>';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;


			case 'em_no_na':
				if ($this->edicao) $saida.='<select class="texto" name="campo_'.$posicao.'" size="1"><option value="em" '.($this->campo[$posicao]['dados']=='em' ? 'checked="checked"' : '').'>em</option><option value="no" '.($this->campo[$posicao]['dados']=='no' ? 'checked="checked"' : '').'>no</option><option value="na" '.($this->campo[$posicao]['dados']=='na' ? 'checked="checked"' : '').'>na</option></select>';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;


			case 'texto':
				if ($this->edicao) $saida.='<input type="text" class="texto" name="campo_'.$posicao.'" value="'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').' />';
				else $saida.=$this->campo[$posicao]['dados'];
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) {
					$this->modelo->bloco('bloco'.$posicao);
					}
				break;

			case 'anexo':
				$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
				$sql = new BDConsulta;
				$sql->adTabela('modelos_anexos');
				$sql->adUnir('usuarios','usuarios', 'modelos_anexos.usuario_id=usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adCampo('nome_fantasia, modelos_anexos.modelo_id, modelo_anexo_id, nome, caminho, modelos_anexos.usuario_id, nome_de, funcao_de, tipo_doc, doc_nr, data_envio, contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
				$sql->adOnde('modelo_id = '.$this->modelo_id);
				$anexos = $sql->Lista();
				$sql->limpar();
				$qnt=0;
				$saida2='';
				$saida3='';
				foreach((array)$anexos as $rs_anexo){
					$qnt++;
					$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
					$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.($Aplic->usuario_prefs['nomefuncao'] ? $rs_anexo['nome_usuario'].($rs_anexo['contato_funcao'] && $rs_anexo['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs_anexo['contato_funcao'] : '') : $rs_anexo['contato_funcao'].($rs_anexo['nome_usuario'] && $rs_anexo['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs_anexo['nome_usuario'] : '')).($rs_anexo['data_envio']? ' em '.retorna_data($rs_anexo['data_envio']):'').'</td></tr>';
					if ($rs_anexo['doc_nr']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Referência</b></td><td>'.$rs_anexo['doc_nr'].'</td></tr>';
					if ($rs_anexo['tipo_doc']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.$rs_anexo['tipo_doc'].'</td></tr>';
					$dentro .= '</table>';
					$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
					if ($this->edicao) $saida3.='<div><a href="javascript:void(0);">&nbsp;</a>'.dica(($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']),$dentro).'<a href="javascript:void(0);" onclick="window.open(\''.$base_url.'/'.($config['pasta_anexos'] ? $config['pasta_anexos'].'_modelos/':'').$rs_anexo['caminho'].'\')">'.($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']).'</a>'.dicaF().dica('Excluir o Arquivo','Clique neste ícone '.imagem('icones/excluir.gif').' para excluir o arquivo.').'&nbsp;<a href="javascript:void(0);" onclick="popExcluir('.$rs_anexo['modelo_anexo_id'].', '.$posicao.')">'.imagem('icones/excluir.gif').'</a>'.dicaF().dica('Renomear o Arquivo','Clique neste ícone '.imagem('icones/editar.gif').' para renomear o arquivo.').'&nbsp;<a href="javascript:void(0);" onclick="popRenomear('.$rs_anexo['modelo_anexo_id'].', '.$posicao.')">'.imagem('icones/editar.gif').'</a>'.dicaF().'</div>';
					elseif(!$this->impressao) $saida3.='<div><a href="javascript:void(0);" onclick="window.open(\''.$base_url.'/'.($config['pasta_anexos'] ? $config['pasta_anexos'].'_modelos/':'').$rs_anexo['caminho'].'\')">'.dica(($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']),$dentro).($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']).'</a>'.dica('Download do Arquivo','Clique neste icone '.imagem('icones/salvar.gif').' para fazer o download do arquivo.').'<a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_arquivo\'; env.sem_cabecalho.value=1; env.anexo.value='.(int)$rs_anexo['modelo_anexo_id'].'; env.submit();">&nbsp;'.imagem('icones/salvar.gif').'</a>'.dicaF().'</div>';
					else $saida3.='<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']).'</div>';
					}
				foreach((array)$this->campo[$posicao]['dados'] as $modelo_id => $nome_fantasia){
					if ($modelo_id){
						$qnt++;
						$sql->adTabela('modelos');
						$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo_id = modelo_tipo');
						$sql->adCampo('modelo_tipo_nome, modelo_id, modelo_criador_original, modelo_data, modelo_protocolo, modelo_data_protocolo, modelo_autoridade_aprovou, modelo_data_aprovado, modelo_assunto');
						$sql->adOnde('modelo_id='.(int)$modelo_id);
						$linha=$sql->Linha();
						$sql->limpar();
						$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
						if ($linha['modelo_criador_original']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Criador</b></td><td>'.nome_funcao('','','','',$linha['modelo_criador_original']).($linha['modelo_data']? ' em '.retorna_data($linha['modelo_data']):'').'</td></tr>';
						if ($linha['modelo_autoridade_aprovou'] && $linha['modelo_autoridade_aprovou'] !=$linha['modelo_criador_original']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Aprovou</b></td><td>'.nome_funcao('','','','',$linha['modelo_autoridade_aprovou']).($linha['modelo_data_aprovado']? ' em '.retorna_data($linha['modelo_data_aprovado']):'').'</td></tr>';
						if ($linha['modelo_tipo_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Tipo</b></td><td>'.$linha['modelo_tipo_nome'].'</td></tr>';
						if ($linha['modelo_assunto']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Assunto</b></td><td>'.$linha['modelo_assunto'].'</td></tr>';
						if ($linha['modelo_protocolo']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Protocolo</b></td><td>'.$linha['modelo_protocolo'].($linha['modelo_protocolo']? ' em '.retorna_data($linha['modelo_data_protocolo']):'').'</td></tr>';
						$dentro .= '</table>';
						$dentro .= '<br>Clique neste link para visualizar o documento no Navegador Web.';

						if ($this->edicao) $saida2.='<div id="anexo_'.$posicao.'_'.$qnt.'">&nbsp;<input type="text" class="texto" name="nome_fantasia_'.$posicao.'[]" value="'.$nome_fantasia.'"><input type="hidden" name="anexo_'.$posicao.'[]" value="'.$modelo_id.'"><a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.(int)$modelo_id.'&dialogo=1\')">'.imagem("icones/postagem.gif", $linha['modelo_assunto'], $dentro).'</a><a  href="javascript: void(0);" onclick=\'var divIdNome="anexos_'.$posicao.'"; env.campo_atual.value='.(int)$posicao.'; removerAnexo('.$posicao.', '.$qnt.')\'>'.imagem("icones/excluir.gif").'</a></div>';
						elseif(!$this->impressao) $saida2.='<div id="anexo_'.$posicao.'_'.$qnt.'"><a href="javascript:void(0);" onclick="window.open(\'?m=email&a=modelo_editar&modelo_id='.(int)$modelo_id.($linha['modelo_autoridade_aprovou'] > 0 ? '&dialogo=1\'' : '\', \'_self\'').')">'.dica($linha['modelo_assunto'],$dentro).$nome_fantasia.dicaF().'</a></div>';
						else $saida2.='<div>'.$nome_fantasia.'</div>';
						}
					}
				if ($this->edicao) {
					$saida.='<table width=100%><tr><td colspan=3><div id="bloco_anexo_'.$posicao.'">'.$saida3.'<div></td></tr>';
					$saida.='<tr><td colspan=3><div id="anexos_'.$posicao.'">'.$saida2.'</div></td></tr>';
					$saida.='<tr><td>'.botao('arquivo', 'Anexar Arquivo', 'Clique neste botão para abrir o painel onde poderá anexar arquivos.','','popAnexar('.$this->modelo_id.', '.$posicao.')').'</td><td>'.botao('documento', 'Anexar Documento', 'Abre uma janela para procurar o documento criado neste Sistema que deseja anexar.','','popDocumentos('.$posicao.');').'</td></tr></table>';
					$saida.='<input type=hidden name="campo_'.$posicao.'" id="campo_'.$posicao.'" value="">';
					$saida.='<input type=hidden name="campo_modelos_nomes_'.$posicao.'" id="campo_modelos_nomes_'.$posicao.'" value="">';
					$saida.='<input type=hidden name="campos_anexos" id="campos_anexos" value="'.$posicao.'">';
					}
				else $saida.='<table cellspacing=0 cellpadding=0 width=100% id="bloco_anexo" name="bloco_anexo"><tr><td colspan=3><div id="bloco_anexo_'.$posicao.'">'.$saida3.'</div></td></tr><tr><td colspan=3>'.$saida2.'</td></tr></table>';
				if (($this->edicao || $qnt > 0) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'data':
				$df = '%d/%m/%Y';
				$data = ($this->campo[$posicao]['dados'] ? new CData($this->campo[$posicao]['dados']) : new CData());
				$nome_meses=array('01'=>'janeiro', '02'=>'fevereiro', '03'=>'março', '04'=>'abril', '05'=>'maio', '06'=>'junho', '07'=>'julho', '08'=>'agosto', '09'=>'setembro', '10'=>'outubro', '11'=>'novembro', '12'=>'dezembro');
				if ($this->edicao){
					$saida.= '<input type="hidden" name="campo_'.$posicao.'" id="campo_'.$posicao.'" value="'.($data ? $data->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data'.$posicao.'" style="width:70px;" id="data'.$posicao.'" onchange="setData(\'env\', \'data'.$posicao.'\');" value="'.($data ? $data->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de início da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'<a href="javascript: void(0);" ><img id="f_btn'.$posicao.'" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF();
					$saida.= '<script type="text/javascript">var cal'.$posicao.' = Calendario.setup({ trigger  : "f_btn'.$posicao.'", inputField : "campo_'.$posicao.'", date :  '.$data->format("%Y%m%d").', selection: '.$data->format("%Y%m%d").',   onSelect: function(cal'.$posicao.') { var date = cal'.$posicao.'.selection.get();  if (date){ date = Calendario.intToDate(date);  document.getElementById("data'.$posicao.'").value = Calendario.printDate(date, "%d/%m/%Y");  document.getElementById("campo_'.$posicao.'").value = Calendario.printDate(date, "%Y-%m-%d"); }	cal'.$posicao.'.hide(); 	}  });</script>';
					}
				else {
					$dia_mes=array('01'=>'1º', '02'=>'2', '03'=>'3', '04'=>'4', '05'=>'5', '06'=>'6', '07'=>'7', '08'=>'8', '09'=>'9');
					if ($data->dia < 10) $dia=$dia_mes[$data->dia];
					else  $dia=$data->dia;
					$saida.=$dia.' de '.$nome_meses[$data->mes].' de '.$data->ano;
					}
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
			case 'bloco':
				if ($this->edicao) $saida.='<textarea data-gpweb-cmp="ckeditor" id="campo_'.$posicao.'" name="campo_'.$posicao.'">'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'</textarea>';
				else $saida.=($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'bloco_poucobotao':
				if ($this->edicao) $saida.='<textarea data-gpweb-cmp="ckeditor" id="campo_'.$posicao.'" name="campo_'.$posicao.'">'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'</textarea>';
				else $saida.=($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;

			case 'bloco_simples':
			
				if ($Aplic->profissional){
					if ($this->edicao) $saida.='<textarea data-gpweb-cmp="ckeditor" id="campo_'.$posicao.'" name="campo_'.$posicao.'">'.($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '').'</textarea>';
					else $saida.=($this->campo[$posicao]['dados'] ? $this->campo[$posicao]['dados'] : '');
					if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
					}
				else {
					$texto=$this->campo[$posicao]['dados'];
					if ($this->edicao) $saida.='<textarea id="campo_'.$posicao.'" name="campo_'.$posicao.'" '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : '').'>'.($texto ? $texto : '').'</textarea>';
					else {
						if ($this->campo[$posicao]['larg_max']) $texto=wordwrap($texto, $this->campo[$posicao]['larg_max'], "<BR>", 1);
						$saida.=($this->campo[$posicao]['extra'] ? '<span '.$this->campo[$posicao]['extra'].'>' : '').$texto.($this->campo[$posicao]['extra'] ? '</span>' : '');
						}
					if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);	
					}	
				break;

			case 'checar':
				if ($this->edicao) $saida.='<input type="checkbox" name="campo_'.$posicao.'" class="texto" '.($this->campo[$posicao]['dados'] ? 'checked="checked"' : '').' '.($this->campo[$posicao]['extra'] ? $this->campo[$posicao]['extra'] : 'size="35"').'/>';
				else $saida.=($this->campo[$posicao]['dados'] ? '<b>X</b>' : '');
				if (($this->edicao || $this->campo[$posicao]['dados']) && in_array('bloco'.$posicao , $this->modelo->lista_blocos())) $this->modelo->bloco('bloco'.$posicao);
				break;
				}
		return $saida;
		}

	function quantidade(){
		return count($this->campo);
		}
	}
?>
