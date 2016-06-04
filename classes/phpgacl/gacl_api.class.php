<?php
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
class gacl_api extends gacl{


 function mostrarVetor($array) {
		echo"<br><pre>\n";
		var_dump($array);
		echo"</pre><br>\n";
		}

 function contar_todos($arg=NULL) {
	 switch(TRUE) {
		 case is_scalar($arg):
		 case is_object($arg):
		 return 1;
		 case is_array($arg):
		 $contagem=0;
		 foreach($arg AS $val) {
		 $contagem+= $this->contar_todos($val);
		 }
		 return $contagem;
		 }
	 return FALSE;
	 }

 function get_versao() {
	 $comando_sql= "select valor from ".$this->_bd_tabela_prefixo."phpgacl where nome = 'versao'";
	 $versao= $this->db->GetOne($comando_sql);
	 return $versao;
	 }

 function get_versao_modelo() {
	 $comando_sql= "select valor from ".$this->_bd_tabela_prefixo."phpgacl WHERE nome = 'versao_esquema'";
	 $versao= $this->db->GetOne($comando_sql);
	 return $versao;
	 }


 function acl_editar_consolidado($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor, $valor_retorno) {
	 $this->texto_depanagem("acl_editar_consolidado(): Valor da seção ACO: $aco_valor_secao  Valor ACO: $aco_valor  Valor da seção ARO: $aro_valor_secao  Valor ARO: $aro_valor Valor Retorno: $valor_retorno");
	 $acl_ids=array();
	 if(empty($aco_valor_secao) ) {
		 $this->texto_depanagem("acl_editar_consolidado(): Valor da seção ACO ($aco_valor_secao) está vazio, e é requerido!");
		 return false;
		 }
	 if(empty($aco_valor) ) {
		 $this->texto_depanagem("acl_editar_consolidado(): Valor ACO ($aco_valor) está vazio, e é requerido!");
		 return false;
		 }
	 if(empty($aro_valor_secao) ) {
		 $this->texto_depanagem("acl_editar_consolidado(): Valor da seção ARO ($aro_valor_secao) está vazio, e é requerido!");
		 return false;
		 }
	 if(empty($aro_valor) ) {
		 $this->texto_depanagem("acl_editar_consolidado(): Valor ARO ($aro_valor) está vazio, e é requerido!");
		 return false;
		 }
	 if(empty($valor_retorno) ) {
		 $this->texto_depanagem("acl_editar_consolidado(): Valor Retorno ($valor_retorno) está vazio, e é requerido!");
		 return false;
		 }
	 $atual_acl_ids= $this->busca_acl($aco_valor_secao, $aco_valor, $aro_valor_secao, $aro_valor,FALSE,FALSE,FALSE,FALSE,FALSE);
	 
	 if(is_array($atual_acl_ids)) {
		 $this->texto_depanagem("add_consolidated_acl(): Achado o ACL_IDs atual, contando os ACOs");
		 foreach($atual_acl_ids AS $atual_acl_id) {
			 $vetor_acl_atual= &$this->get_acl($atual_acl_id);
			 $this->texto_depanagem("add_consolidated_acl(): Contagem atual: ".$this->contar_todos($vetor_acl_atual['aco'])."");
			 if( $this->contar_todos($vetor_acl_atual['aco']) ==1) {
				 $this->texto_depanagem("add_consolidated_acl(): ID ACL: $atual_acl_id tem 1 ACO.");
				 if($vetor_acl_atual['valor_retorno'] == $valor_retorno) {
					 $this->texto_depanagem("add_consolidated_acl(): ID ACL: $atual_acl_id tem 1 ACO, e o mesmo valor de retorno. Não é necessário modificar.");
					 return true;
					 }
				 $acl_ids[] = $atual_acl_id;
				 }
			 }
		 }
	 $acl_ids_contagem=count($acl_ids);
	 if(is_array($acl_ids) && $acl_ids_contagem>0) {
		 $this->texto_depanagem("add_consolidated_acl(): Removendo o ARO especificado do ACL existente.");
		 foreach($acl_ids AS $acl_id) {
			 if(!$this->shift_acl($acl_id,array($aro_valor_secao=>array($aro_valor)) ) ) {
				 $this->texto_depanagem("add_consolidated_acl(): Erro ao remover o ARO especificado do ID ACL: $acl_id");
				 return false;
				 }
			 }
		 }
	 else $this->texto_depanagem("add_consolidated_acl(): Não foi encontrado nenhum ACLs atual com um único ACO. ");
	
	 $acl_ids=array();
	 unset($acl_ids_contagem);
	 $novo_acl_ids= $this->busca_acl($aco_valor_secao, $aco_valor,FALSE,FALSE,NULL,NULL,NULL,NULL, $valor_retorno);
	 $novo_acl_contagem=count($novo_acl_ids);
	 
	 if(is_array($novo_acl_ids)) {
		 $this->texto_depanagem("add_consolidated_acl(): Achado novo ACL_IDs, contando ACOs");
		 foreach($novo_acl_ids AS $novo_acl_id) {
			 $novo_vetor_acl= &$this->get_acl($novo_acl_id);
			 $this->texto_depanagem("add_consolidated_acl(): Nova contagem: ".$this->contar_todos($novo_vetor_acl['aco'])."");
			 if( $this->contar_todos($novo_vetor_acl['aco']) ==1) {
				 $this->texto_depanagem("add_consolidated_acl(): ID ACL: $novo_acl_id tem 1 ACO, anexo deverá ser possível de ocorrer.");
				 $acl_ids[] = $novo_acl_id;
				 }
			 }
		 }
	 $acl_ids_contagem=count($acl_ids);
	 if(is_array($acl_ids) && $acl_ids_contagem==1) {
		 $this->texto_depanagem("add_consolidated_acl(): Concatenando o ARO especificado ao ACL existente.");
		 $acl_id=$acl_ids[0];
		 if(!$this->juntar_acl($acl_id,array($aro_valor_secao=>array($aro_valor)) ) ) {
			 $this->texto_depanagem("add_consolidated_acl(): Error appending specified ARO to ACL ID: $acl_id");
			 return false;
			 }
		 $this->texto_depanagem("add_consolidated_acl(): Hot damn, ACL consolidated!");
		 return true;
		 }
	 elseif($acl_ids_contagem>1) {
		 $this->texto_depanagem("add_consolidated_acl(): Found more then one ACL with a single ACO. Possible conflicting ACLs.");
		 return false;
		 }
	 elseif($acl_ids_contagem==0) {
		 $this->texto_depanagem("add_consolidated_acl(): No existing ACLs found, create a new one.");
		 if(!$this->adiciona_acl(array( $aco_valor_secao=>array($aco_valor) ), array( $aro_valor_secao=>array($aro_valor) ), NULL, NULL, NULL, TRUE, TRUE, $valor_retorno, NULL) ) {
			 $this->texto_depanagem("add_consolidated_acl(): Error adding new ACL for ACO Seção: $aco_valor_secao ACO Valor: $aco_valor Return Valor: $valor_retorno");
			 return false;
			 }
		 $this->texto_depanagem("add_consolidated_acl(): ADD_ACL() successfull, returning True.");
		 return true;
		 }
	 $this->texto_depanagem("add_consolidated_acl(): Returning false.");
	 return false;
	 }

 function busca_acl($aco_valor_secao=NULL, $aco_valor=NULL, $aro_valor_secao=NULL, $aro_valor=NULL, $aro_grupo_nome=NULL, $axo_valor_secao=NULL, $axo_valor=NULL, $axo_grupo_nome=NULL, $valor_retorno=NULL) {
	 $this->texto_depanagem("busca_acl(): aco_valor_secao: $aco_valor_secao aco_valor: $aco_valor, aro_valor_secao: $aro_valor_secao, aro_valor: $aro_valor, aro_grupo_nome: $aro_grupo_nome, axo_valor_secao: $axo_valor_secao, axo_valor: $axo_valor, axo_grupo_nome: $axo_grupo_nome, valor_retorno: $valor_retorno");
	 $comando_sql= ' select	a.id FROM	'. $this->_bd_tabela_prefixo.'acl a';
	 $onde_sql=array();
	 if($aco_valor_secao!==FALSE && $aco_valor!==FALSE) {
		 $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'aco_mapa ac ON a.id=ac.acl_id';
		 if($aco_valor_secao==NULL && $aco_valor==NULL) $onde_sql[] = '(ac.valor_secao IS NULL AND ac.valor IS NULL)';
		 else $onde_sql[] = '(ac.valor_secao='. $this->db->quote($aco_valor_secao) .' AND ac.valor='. $this->db->quote($aco_valor) .')';
		 }
	 if($aro_valor_secao!==FALSE && $aro_valor!==FALSE) {
		 $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'aro_mapa ar ON a.id=ar.acl_id';
		 if($aro_valor_secao==NULL && $aro_valor==NULL) $onde_sql[] = '(ar.valor_secao IS NULL AND ar.valor IS NULL)';
		 else $onde_sql[] = '(ar.valor_secao='. $this->db->quote($aro_valor_secao) .' AND ar.valor='. $this->db->quote($aro_valor) .')';
		 }
	 if($axo_valor_secao!==FALSE && $axo_valor!==FALSE) {
		 $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'axo_mapa ax ON a.id=ax.acl_id';
		 if($axo_valor_secao==NULL && $axo_valor==NULL) $onde_sql[] = '(ax.valor_secao IS NULL AND ax.valor IS NULL)';
		 else $onde_sql[] = '(ax.valor_secao='. $this->db->quote($axo_valor_secao) .' AND ax.valor='. $this->db->quote($axo_valor) .')';
		 }
	 if($aro_grupo_nome!==FALSE) {
		 $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'aro_grupos_mapa arg ON a.id=arg.acl_id	LEFT JOIN	'. $this->_bd_tabela_prefixo.'aro_grupos rg ON arg.grupo_id=rg.id';
		 if($aro_grupo_nome==NULL) $onde_sql[] = '(rg.nome IS NULL)';
		 else $onde_sql[] = '(rg.nome='. $this->db->quote($aro_grupo_nome) .')';
		 }
	 if($axo_grupo_nome!==FALSE) {
		 $comando_sql.= '	LEFT JOIN	'. $this->_bd_tabela_prefixo.'axo_grupos_mapa axg ON a.id=axg.acl_id	LEFT JOIN	'. $this->_bd_tabela_prefixo.'axo_grupos xg ON axg.grupo_id=xg.id';
		 if($axo_grupo_nome==NULL) $onde_sql[] = '(xg.nome IS NULL)';
		 else $onde_sql[] = '(xg.nome='. $this->db->quote($axo_grupo_nome) .')';
		 }
	 if($valor_retorno!=FALSE) {
		 if($valor_retorno==NULL) $onde_sql[] = '(a.valor_retorno IS NULL)';
		 else $onde_sql[] = '(a.valor_retorno='. $this->db->quote($valor_retorno) .')';
		 }
	 if(count($onde_sql) >0) $comando_sql.= ' WHERE	'.implode(' AND ', $onde_sql);
	 return $this->db->GetCol($comando_sql);
	 }

 function juntar_acl($acl_id, $vetor_aro=NULL, $aro_grupo_ids=NULL, $vetor_axo=NULL, $axo_grupo_ids=NULL, $vetor_aco=NULL) {
	 $this->texto_depanagem("juntar_acl(): ACL_ID: $acl_id");
	 $atualizado=0;
	 if(empty($acl_id)) {
		 $this->texto_depanagem("juntar_acl(): No ACL_ID specified! ACL_ID: $acl_id");
		 return false;
		 }
	 $vetor_acl= &$this->get_acl($acl_id);
	 if(is_array($vetor_aro) && count($vetor_aro) > 0) {
		 $this->texto_depanagem("juntar_acl(): Appending ARO's");
		 while(list($aro_valor_secao,$vetor_aro_valor) = @each($vetor_aro)) {
			 foreach($vetor_aro_valor AS $aro_valor) {
				 if(count($vetor_acl['aro'][$aro_valor_secao]) !=0) {
					 if(!in_array($aro_valor, $vetor_acl['aro'][$aro_valor_secao])) {
						 $this->texto_depanagem("juntar_acl(): ARO Valor da Seção: $aro_valor_secao ARO Valor: $aro_valor");
						 $vetor_acl['aro'][$aro_valor_secao][] = $aro_valor;
						 $atualizado=1;
						 }
					 else $this->texto_depanagem("juntar_acl(): Duplicate ARO, ignoring... ");
					 }
			 	 else{ 
				 	 $vetor_acl['aro'][$aro_valor_secao][] = $aro_valor;
					 $atualizado=1;
					 }
				 }
			 }
		 }
	 if(is_array($aro_grupo_ids) && count($aro_grupo_ids) >0) {
		 $this->texto_depanagem("juntar_acl(): Appending ARO_GROUP_ID's");
		 while(list(,$aro_grupo_id) = @each($aro_grupo_ids)) {
			 if(!is_array($vetor_acl['aro_grupos']) || !in_array($aro_grupo_id, $vetor_acl['aro_grupos'])) {
				 $this->texto_depanagem("juntar_acl(): ARO ID do Grupo: $aro_grupo_id");
				 $vetor_acl['aro_grupos'][] = $aro_grupo_id;
				 $atualizado=1;
				 }
			 else $this->texto_depanagem("juntar_acl(): Duplicate ARO_Group_ID, ignoring... ");
			 }
		 }
	 if(is_array($vetor_axo) && count($vetor_axo) >0) {
		 $this->texto_depanagem("juntar_acl(): Appending AXO's");
		 while(list($axo_valor_secao,$vetor_axo_valor) = @each($vetor_axo)) {
			 foreach($vetor_axo_valor AS $axo_valor) {
				 if(!in_array($axo_valor, $vetor_acl['axo'][$axo_valor_secao])) {
					 $this->texto_depanagem("juntar_acl(): AXO Valor da Seção: $axo_valor_secao AXO Valor: $axo_valor");
					 $vetor_acl['axo'][$axo_valor_secao][] = $axo_valor;
					 $atualizado=1;
					 }
				 else $this->texto_depanagem("juntar_acl(): Duplicate AXO, ignoring... ");
				 }
			 }
		 }
	 if(is_array($axo_grupo_ids) && count($axo_grupo_ids) >0) {
		 $this->texto_depanagem("juntar_acl(): Appending AXO_GROUP_ID's");
		 while(list(,$axo_grupo_id) = @each($axo_grupo_ids)) {
			 if(!is_array($vetor_acl['axo_grupos']) || !in_array($axo_grupo_id, $vetor_acl['axo_grupos'])) {
				 $this->texto_depanagem("juntar_acl(): AXO ID do Grupo: $axo_grupo_id");
				 $vetor_acl['axo_grupos'][] = $axo_grupo_id;
				 $atualizado=1;
				 }
			 else $this->texto_depanagem("juntar_acl(): Duplicate ARO_Group_ID, ignoring... ");
			 }
		 }
	 if(is_array($vetor_aco) && count($vetor_aco) >0) {
		 $this->texto_depanagem("juntar_acl(): Appending ACO's");
		 while(list($aco_valor_secao,$vetor_valor_aco) = @each($vetor_aco)) {
			 foreach($vetor_valor_aco AS $aco_valor) {
				 if(!in_array($aco_valor, $vetor_acl['aco'][$aco_valor_secao])) {
					 $this->texto_depanagem("juntar_acl(): ACO Valor da Seção: $aco_valor_secao ACO Valor: $aco_valor");
					 $vetor_acl['aco'][$aco_valor_secao][] = $aco_valor;
					 $atualizado=1;
					 }
			   else $this->texto_depanagem("juntar_acl(): Duplicate ACO, ignoring... ");
				 }
			 }
		 }
	 if($atualizado==1) {
		 $this->texto_depanagem("juntar_acl(): Update flag set, updating ACL.");
		 return $this->editar_acl($acl_id, $vetor_acl['aco'], $vetor_acl['aro'], $vetor_acl['aro_grupos'], $vetor_acl['axo'], $vetor_acl['axo_grupos'], $vetor_acl['permitir'], $vetor_acl['habilitado'], $vetor_acl['valor_retorno'], $vetor_acl['nota']);
		 }
	 $this->texto_depanagem("juntar_acl(): Update flag not set, NOT updating ACL.");
	 return true;
	 }

 function shift_acl($acl_id, $vetor_aro = NULL, $aro_grupo_ids = NULL, $vetor_axo = NULL, $axo_grupo_ids = NULL, $vetor_aco = NULL) {
	$this->texto_depanagem("shift_acl(): ACL_ID: $acl_id");
	$atualizado = 0;
	if (empty($acl_id)) {
		$this->texto_depanagem("shift_acl(): No ACL_ID specified! ACL_ID: $acl_id");
		return false;
		}
	$vetor_acl = &$this->get_acl($acl_id);
	if (is_array($vetor_aro) && count($vetor_aro) > 0) {
		$this->texto_depanagem("shift_acl(): Removing ARO's");
		while (list($aro_valor_secao, $vetor_aro_valor) = @each($vetor_aro)) {
			foreach($vetor_aro_valor AS $aro_valor) {
				$this->texto_depanagem("shift_acl(): ARO Valor da Seção: $aro_valor_secao ARO Valor: $aro_valor");
				if (count($vetor_acl['aro'][$aro_valor_secao]) != 0) {
					$aro_chave = array_search($aro_valor, $vetor_acl['aro'][$aro_valor_secao]);
					if ($aro_chave !== FALSE) {
						$this->texto_depanagem("shift_acl(): Removing ARO. ($aro_chave)");
						unset($vetor_acl['aro'][$aro_valor_secao][$aro_chave]);
						$atualizado = 1;
					} else {
						$this->texto_depanagem("shift_acl(): ARO não existe, não pode remove-lo.");
					}
				}
			}
		}
	}
	if (is_array($aro_grupo_ids) && count($aro_grupo_ids) > 0) {
		$this->texto_depanagem("shift_acl(): Removing ARO_GROUP_ID's");
		while (list(, $aro_grupo_id) = @each($aro_grupo_ids)) {
			$this->texto_depanagem("shift_acl(): ARO ID do Grupo: $aro_grupo_id");
			$aro_grupo_chave = array_search($aro_grupo_id, $vetor_acl['aro_grupos']);
			if ($aro_grupo_chave !== FALSE) {
				$this->texto_depanagem("shift_acl(): Removing ARO Group. ($aro_grupo_chave)");
				unset($vetor_acl['aro_grupos'][$aro_grupo_chave]);
				$atualizado = 1;
			} else {
				$this->texto_depanagem("shift_acl(): ARO Group não existe, não pode remove-lo.");
			}
		}
	}
	if (is_array($vetor_axo) && count($vetor_axo) > 0) {
		$this->texto_depanagem("shift_acl(): Removing AXO's");
		while (list($axo_valor_secao, $vetor_axo_valor) = @each($vetor_axo)) {
			foreach($vetor_axo_valor AS $axo_valor) {
				$this->texto_depanagem("shift_acl(): AXO Valor da Seção: $axo_valor_secao AXO Valor: $axo_valor");
				$axo_chave = array_search($axo_valor, $vetor_acl['axo'][$axo_valor_secao]);
				if ($axo_chave !== FALSE) {
					$this->texto_depanagem("shift_acl(): Removing AXO. ($axo_chave)");
					unset($vetor_acl['axo'][$axo_valor_secao][$axo_chave]);
					$atualizado = 1;
				} else {
					$this->texto_depanagem("shift_acl(): AXO não existe, não pode remove-lo.");
				}
			}
		}
	}
	if (is_array($axo_grupo_ids) && count($axo_grupo_ids) > 0) {
		$this->texto_depanagem("shift_acl(): Removing AXO_GROUP_ID's");
		while (list(, $axo_grupo_id) = @each($axo_grupo_ids)) {
			$this->texto_depanagem("shift_acl(): AXO ID do Grupo: $axo_grupo_id");
			$axo_grupo_chave = array_search($axo_grupo_id, $vetor_acl['axo_grupos']);
			if ($axo_grupo_chave !== FALSE) {
				$this->texto_depanagem("shift_acl(): Removing AXO Group. ($axo_grupo_chave)");
				unset($vetor_acl['axo_grupos'][$axo_grupo_chave]);
				$atualizado = 1;
			} else {
				$this->texto_depanagem("shift_acl(): AXO Group não existe, não pode remove-lo.");
			}
		}
	}
	if (is_array($vetor_aco) && count($vetor_aco) > 0) {
		$this->texto_depanagem("shift_acl(): Removing ACO's");
		while (list($aco_valor_secao, $vetor_valor_aco) = @each($vetor_aco)) {
			foreach($vetor_valor_aco AS $aco_valor) {
				$this->texto_depanagem("shift_acl(): ACO Valor da Seção: $aco_valor_secao ACO Valor: $aco_valor");
				$aco_chave = array_search($aco_valor, $vetor_acl['aco'][$aco_valor_secao]);
				if ($aco_chave !== FALSE) {
					$this->texto_depanagem("shift_acl(): Removing ACO. ($aco_chave)");
					unset($vetor_acl['aco'][$aco_valor_secao][$aco_chave]);
					$atualizado = 1;
				} else {
					$this->texto_depanagem("shift_acl(): ACO não existe, não pode remove-lo.");
				}
			}
		}
	}
	if ($atualizado == 1) {
		$this->texto_depanagem("shift_acl(): ACOs: ".$this->contar_todos($vetor_acl['aco'])." AROs: ".$this->contar_todos($vetor_acl['aro'])."");
		if ($this->contar_todos($vetor_acl['aco']) == 0 || ($this->contar_todos($vetor_acl['aro']) == 0 AND($this->contar_todos($vetor_acl['axo']) == 0 || $vetor_acl['axo'] == FALSE) AND(count($vetor_acl['aro_grupos']) == 0 || $vetor_acl['aro_grupos'] == FALSE) AND(count($vetor_acl['axo_grupos']) == 0 || $vetor_acl['axo_grupos'] == FALSE))) {
			$this->texto_depanagem("shift_acl(): No ACOs or ( AROs AND AXOs AND ARO Groups AND AXO Groups) left assigned to this ACL (ID: $acl_id), deleting ACL.");
			return $this->excluir_acl($acl_id);
		}
		$this->texto_depanagem("shift_acl(): Update flag set, updating ACL.");
		return $this->editar_acl($acl_id, $vetor_acl['aco'], $vetor_acl['aro'], $vetor_acl['aro_grupos'], $vetor_acl['axo'], $vetor_acl['axo_grupos'], $vetor_acl['permitir'], $vetor_acl['habilitado'], $vetor_acl['valor_retorno'], $vetor_acl['nota']);
	}
	$this->texto_depanagem("shift_acl(): Update flag not set, NOT updating ACL.");
	return true;
	}
function get_acl($acl_id) {
	$this->texto_depanagem("get_acl(): ACL_ID: $acl_id");
	if (empty($acl_id)) {
		$this->texto_depanagem("get_acl(): No ACL_ID specified! ACL_ID: $acl_id");
		return false;
		}
	$comando_sql = 'select id, permitir, habilitado, valor_retorno, nota FROM '.$this->_bd_tabela_prefixo.'acl WHERE id = '.$acl_id;
	$acl_row = $this->db->GetRow($comando_sql);
	if (!$acl_row) {
		$this->texto_depanagem("get_acl(): No ACL found for that ID! ACL_ID: $acl_id");
		return false;
	}
	list($retornar['acl_id'], $retornar['permitir'], $retornar['habilitado'], $retornar['valor_retorno'], $retornar['nota']) = $acl_row;
	$comando_sql = 'select DISTINCT a.valor_secao, a.valor, c.nome, b.nome FROM '.$this->_bd_tabela_prefixo.'aco_mapa a, '.$this->_bd_tabela_prefixo.'aco b, '.$this->_bd_tabela_prefixo.'aco_secoes c WHERE ( a.valor_secao=b.valor_secao AND a.valor = b.valor) AND b.valor_secao=c.valor AND a.acl_id = '.$acl_id;
	$rs = $this->db->Execute($comando_sql);
	$linhas = $rs->GetRows();
	$retornar['aco'] = array();
	while (list(, $linha) = @each($linhas)) {
		list($secao_valor, $valor, $secao, $aco) = $linha;
		$this->texto_depanagem("Valor da Seção: $secao_valor Valor: $valor Seção: $secao ACO: $aco");
		$retornar['aco'][$secao_valor][] = $valor;
	}
	$comando_sql = "select DISTINCT a.valor_secao, a.valor, c.nome, b.nome FROM ".$this->_bd_tabela_prefixo."aro_mapa a, ".$this->_bd_tabela_prefixo."aro b, ".$this->_bd_tabela_prefixo."aro_secoes c WHERE ( a.valor_secao=b.valor_secao AND a.valor = b.valor) AND b.valor_secao=c.valor AND a.acl_id = $acl_id";
	$rs = $this->db->Execute($comando_sql);
	$linhas = $rs->GetRows();
	$retornar['aro'] = array();
	while (list(, $linha) = @each($linhas)) {
		list($secao_valor, $valor, $secao, $aro) = $linha;
		$this->texto_depanagem("Valor da Seção: $secao_valor Valor: $valor Seção: $secao ARO: $aro");
		$retornar['aro'][$secao_valor][] = $valor;
	}
	$comando_sql = "select DISTINCT a.valor_secao, a.valor, c.nome, b.nome FROM ".$this->_bd_tabela_prefixo."axo_mapa a, ".$this->_bd_tabela_prefixo."axo b, ".$this->_bd_tabela_prefixo."axo_secoes c WHERE ( a.valor_secao=b.valor_secao AND a.valor = b.valor) AND b.valor_secao=c.valor AND a.acl_id = $acl_id";
	$rs = $this->db->Execute($comando_sql);
	$linhas = $rs->GetRows();
	$retornar['axo'] = array();
	while (list(, $linha) = @each($linhas)) {
		list($secao_valor, $valor, $secao, $axo) = $linha;
		$this->texto_depanagem("Valor da Seção: $secao_valor Valor: $valor Seção: $secao AXO: $axo");
		$retornar['axo'][$secao_valor][] = $valor;
	}
	$retornar['aro_grupos'] = array();
	$comando_sql = "select DISTINCT grupo_id FROM ".$this->_bd_tabela_prefixo."aro_grupos_mapa WHERE  acl_id = $acl_id";
	$retornar['aro_grupos'] = $this->db->GetCol($comando_sql);
	$retornar['axo_grupos'] = array();
	$comando_sql = "select DISTINCT grupo_id FROM ".$this->_bd_tabela_prefixo."axo_grupos_mapa WHERE  acl_id = $acl_id";
	$retornar['axo_grupos'] = $this->db->GetCol($comando_sql);
	return $retornar;
}
function eh_acl_em_conflito($vetor_aco, $vetor_aro, $aro_grupo_ids = NULL, $vetor_axo = NULL, $axo_grupo_ids = NULL, $ignore_acl_ids = NULL) {
	if (!is_array($vetor_aco)) {
		$this->texto_depanagem('eh_acl_em_conflito(): Invalid ACO Array.');
		return FALSE;
		}
	if (!is_array($vetor_aro)) {
		$this->texto_depanagem('eh_acl_em_conflito(): Invalid ARO Array.');
		return FALSE;
		}
	$comando_sql = ' select a.id FROM	'.$this->_bd_tabela_prefixo.'acl a LEFT JOIN	'.$this->_bd_tabela_prefixo.'aco_mapa ac ON ac.acl_id=a.id	LEFT JOIN	'.$this->_bd_tabela_prefixo.'aro_mapa ar ON ar.acl_id=a.id	LEFT JOIN	'.$this->_bd_tabela_prefixo.'axo_mapa ax ON ax.acl_id=a.id	LEFT JOIN	'.$this->_bd_tabela_prefixo.'axo_grupos_mapa axg ON axg.acl_id=a.id LEFT JOIN	'.$this->_bd_tabela_prefixo.'axo_grupos xg ON xg.id=axg.grupo_id';
	foreach($vetor_aco AS $aco_valor_secao =>$vetor_valor_aco) {
		$this->texto_depanagem("eh_acl_em_conflito(): ACO Valor da Seção: $aco_valor_secao ACO Valor: $vetor_valor_aco");
		if (!is_array($vetor_valor_aco)) {
			$this->texto_depanagem('eh_acl_em_conflito(): Formato inválido para vetor ACO. Pulando...');
			continue;
			}
		$onde_sql = array('ac2' =>'(ac.valor_secao='.$this->db->quote($aco_valor_secao).' AND ac.valor IN (\''.implode('\',\'', $vetor_valor_aco).'\'))');
		foreach($vetor_aro AS $aro_valor_secao => $vetor_aro_valor) {
			$this->texto_depanagem("eh_acl_em_conflito(): ARO Valor da Seção: $aro_valor_secao ARO Valor: $vetor_aro_valor");
			if (!is_array($vetor_aro_valor)) {
				$this->texto_depanagem('eh_acl_em_conflito(): Formato inválido para vetor ARO. Pulando...');
				continue;
				}
			$this->texto_depanagem("eh_acl_em_conflito(): Procurar: ACO Seção: $aco_valor_secao ACO Valor: $vetor_valor_aco ARO Seção: $aro_valor_secao ARO Valor: $vetor_aro_valor");
			$onde_sql['ar2'] = '(ar.valor_secao='.$this->db->quote($aro_valor_secao).' AND ar.valor IN (\''.implode('\',\'', $vetor_aro_valor).'\'))';
			if (is_array($vetor_axo) && count($vetor_axo) > 0) {
				foreach($vetor_axo AS $axo_valor_secao =>$vetor_axo_valor) {
					$this->texto_depanagem("eh_acl_em_conflito(): AXO Valor da Seção: $axo_valor_secao AXO Valor: $vetor_axo_valor");
					if (!is_array($vetor_axo_valor)) {
						$this->texto_depanagem('eh_acl_em_conflito(): Formato inválido para vetor AXO. Pulando...');
						continue;
						}
					$this->texto_depanagem("eh_acl_em_conflito(): Procurar: ACO Seção: $aco_valor_secao ACO Valor: $vetor_valor_aco ARO Seção: $aro_valor_secao ARO Valor: $vetor_aro_valor AXO Seção: $axo_valor_secao AXO Valor: $vetor_axo_valor");
					$onde_sql['ax1'] = 'ax.acl_id=a.id';
					$onde_sql['ax2'] = '(ax.valor_secao='.$this->db->quote($axo_valor_secao).' AND ax.valor IN (\''.implode('\',\'', $vetor_axo_valor).'\'))';
					$onde = ' WHERE '.implode(' AND ', $onde_sql);
					$resultado_conflito = $this->db->GetCol($comando_sql.$onde);
					if (is_array($resultado_conflito) && ! empty($resultado_conflito)) {
						if (is_array($ignore_acl_ids)) $resultado_conflito = array_diff($resultado_conflito, $ignore_acl_ids);
						if (count($resultado_conflito) > 0) {
							$str_acls_conflito = implode(',', $resultado_conflito);
							$this->texto_depanagem("eh_acl_em_conflito(): Conflito encontrado!!! ACL_IDS: ($str_acls_conflito)");
							return TRUE;
							}
						}
					}
				} 
			else {
				$onde_sql['ax1'] = '(ax.valor_secao IS NULL AND ax.valor IS NULL)';
				$onde_sql['ax2'] = 'xg.nome IS NULL';
				$onde = ' WHERE '.implode(' AND ', $onde_sql);
				$resultado_conflito = $this->db->GetCol($comando_sql.$onde);
				if (is_array($resultado_conflito) && !empty($resultado_conflito)) {
					if (is_array($ignore_acl_ids)) $resultado_conflito = array_diff($resultado_conflito, $ignore_acl_ids);
					if (count($resultado_conflito) > 0) {
						$str_acls_conflito = implode(',', $resultado_conflito);
						$this->texto_depanagem("eh_acl_em_conflito(): Conflito encontrado!!! ACL_IDS: ($str_acls_conflito)");
						return TRUE;
						}
					}
				}
			}
		}
	$this->texto_depanagem('eh_acl_em_conflito(): Nenhum ACL em conflito encontrado.');
	return FALSE;
	}
	
function adiciona_acl($vetor_aco, $vetor_aro, $aro_grupo_ids = NULL, $vetor_axo = NULL, $axo_grupo_ids = NULL, $permitir = 1, $habilitado = 1, $valor_retorno = NULL, $nota = NULL, $secao_valor = NULL, $acl_id = FALSE) {
	$this->texto_depanagem("adiciona_acl():");
	if (count($vetor_aco) == 0) {
		$this->texto_depanagem("Necessita selecionar ao menos um objeto de controle de acesso");
		return false;
		}
	if (count($vetor_aro) == 0 && count($aro_grupo_ids) == 0) {
		$this->texto_depanagem("Necessita selecionar ao menos um Objeto de Solicitação de Acesso ou Grupo");
		return false;
		}
	if (empty($permitir)) $permitir = 0;
	if (empty($habilitado)) $habilitado = 0;
	if (!empty($secao_valor) && ! $this->get_objeto_secao_secao_id(NULL, $secao_valor, 'ACL')) {
		$this->texto_depanagem("adiciona_acl(): Valor da Seção: $secao_valor não existe na base de dados.");
		return false;
		}
	if (is_array($aro_grupo_ids)) $aro_grupo_ids = array_unique($aro_grupo_ids);
	if (is_array($axo_grupo_ids))	$axo_grupo_ids = array_unique($axo_grupo_ids);
	if ($this->eh_acl_em_conflito($vetor_aco, $vetor_aro, $aro_grupo_ids, $vetor_axo, $axo_grupo_ids, array($acl_id))) {
		$this->texto_depanagem("adiciona_acl(): Detectado possível conflito ACL, abortado adição de ACL!");
		return false;
		}
	if ($this->get_acl($acl_id) == FALSE) {
		if (empty($secao_valor)) {
			$secao_valor = 'sistema';
			if (!$this->get_objeto_secao_secao_id(NULL, $secao_valor, 'ACL')) {
				$acl_secoes_table = $this->_bd_tabela_prefixo.'acl_secoes';
				$acl_secao_valor_ordem = $this->db->GetOne('select min(valor_ordem) FROM '.$acl_secoes_table);
				$comando_sql = 'select valor FROM '.$acl_secoes_table.'	WHERE valor_ordem = '.$acl_secao_valor_ordem;
				$secao_valor = $this->db->GetOne($comando_sql);
				if (empty($secao_valor)) {
					$this->texto_depanagem("adiciona_acl(): Nenhuma  secao acl valida foi encontrada.");
					return false;
					} 
				else $this->texto_depanagem("adiciona_acl(): Usando o valor default para secao: $secao_valor.");
				}
			}
		if (empty($acl_id)) {
			$acl_id = $this->db->GenID($this->_bd_tabela_prefixo.'acl_seq', 10);
			if (empty($acl_id)) {
				$this->texto_depanagem("adiciona_acl(): geração de ACL_ID falhou!");
				return false;
				}
			}
		$this->db->BeginTrans();
		$comando_sql = 'INSERT INTO '.$this->_bd_tabela_prefixo.'acl (id, valor_secao, permitir, habilitado, valor_retorno, nota, data_atualizacao) values('.$acl_id.', '.$this->db->quote($secao_valor).', '.$permitir.', '.$habilitado.', '.$this->db->quote($valor_retorno).', '.$this->db->quote($nota).', '.time().')';
		$resultado = $this->db->Execute($comando_sql);
		} 
	else {
		$secao_sql = '';
		if (!empty($secao_valor))	$secao_sql = 'valor_secao='.$this->db->quote($secao_valor).',';
		$this->db->BeginTrans();
		$comando_sql = ' UPDATE	'.$this->_bd_tabela_prefixo.'acl SET '.$secao_sql.' permitir='.$permitir.',	habilitado='.$habilitado.',	valor_retorno='.$this->db->quote($valor_retorno).', nota='.$this->db->quote($nota).', data_atualizacao='.time().'	WHERE	id='.$acl_id;
		$resultado = $this->db->Execute($comando_sql);
		if ($resultado) {
			$this->texto_depanagem("Atualização completada sem erro, excluindo mapas...");
			foreach(array('aco_mapa', 'aro_mapa', 'axo_mapa', 'aro_grupos_mapa', 'axo_grupos_mapa') AS $mapa) {
				$comando_sql = 'DELETE FROM '.$this->_bd_tabela_prefixo.$mapa.' WHERE acl_id='.$acl_id;
				$rs = $this->db->Execute($comando_sql);
				if (!is_object($rs)) {
					$this->debug_db('adiciona_acl');
					$this->db->RollBackTrans();
					return FALSE;
					}
				}
			}
		}
	if (!is_object($resultado)) {
		$this->debug_db('adiciona_acl');
		$this->db->RollBackTrans();
		return false;
		}
	$this->texto_depanagem("Inserir ou atualizar completado sem erros, inserindo novos mapas.");
	foreach(array('aco', 'aro', 'axo') AS $mapa) {
		$vetor_mapa = ${'vetor_'.$mapa};
		if (!is_array($vetor_mapa)) continue;
		foreach($vetor_mapa AS $secao_valor => $vetor_valor) {
			$this->texto_depanagem('Insert: '.strtoupper($mapa).' Valor da Seção: '.$secao_valor.' '.strtoupper($mapa).' Valor: '.$vetor_valor);
			if (!is_array($vetor_valor)) {
				$this->texto_depanagem('adiciona_acl (): Formato inválido para vetor '.strtoupper($mapa).'. Pulando...');
				continue;
				}
			$vetor_valor = array_unique($vetor_valor);
			foreach($vetor_valor AS $valor) {
				$objeto_id = &$this->get_objeto_id($secao_valor, $valor, $mapa);
				if (empty($objeto_id)) {
					$this->texto_depanagem('adiciona_acl(): '.strtoupper($mapa)." Object Valor da Seção: $secao_valor Valor: $valor DOES NOT exist in the database. Pulando...");
					$this->db->RollBackTrans();
					return false;
					}
				$comando_sql = 'INSERT INTO '.$this->_bd_tabela_prefixo.$mapa.'_mapa (acl_id,valor_secao,valor) values ('.$acl_id.', '.$this->db->quote($secao_valor).', '.$this->db->quote($valor).')';
				$rs = $this->db->Execute($comando_sql);
				if (!is_object($rs)) {
					$this->debug_db('adiciona_acl');
					$this->db->RollBackTrans();
					return false;
					}
				}
			}
		}
	foreach(array('aro', 'axo') AS $mapa) {
		$map_grupo_ids = ${$mapa.'_grupo_ids'};
		if (!is_array($map_grupo_ids)) continue;
		foreach($map_grupo_ids AS $grupo_id) {
			$this->texto_depanagem('Insert: '.strtoupper($mapa).' GROUP ID: '.$grupo_id);
			$grupo_data = &$this->get_grupo_dados($grupo_id, $mapa);
			if (empty($grupo_data)) {
				$this->texto_depanagem('adiciona_acl(): '.strtoupper($mapa)." Group: $grupo_id DOES NOT exist in the database. Pulando...");
				$this->db->RollBackTrans();
				return false;
				}
			$comando_sql = 'INSERT INTO '.$this->_bd_tabela_prefixo.$mapa.'_grupos_mapa (acl_id,grupo_id) values ('.$acl_id.', '.$grupo_id.')';
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) {
				$this->debug_db('adiciona_acl');
				$this->db->RollBackTrans();
				return false;
				}
			}
		}
	$this->db->CommitTrans();
	if ($this->_caching == TRUE AND $this->_forcar_expirar_cache == TRUE) $this->Cache_Lite->clean('default');
	return $acl_id;
	}
	
function editar_acl($acl_id, $vetor_aco, $vetor_aro, $aro_grupo_ids = NULL, $vetor_axo = NULL, $axo_grupo_ids = NULL, $permitir = 1, $habilitado = 1, $valor_retorno = NULL, $nota = NULL, $secao_valor = NULL) {
	$this->texto_depanagem("editar_acl():");
	if (empty($acl_id)) {
		$this->texto_depanagem("editar_acl(): Must specify a single ACL_ID to edit");
		return false;
		}
	if (count($vetor_aco) == 0) {
		$this->texto_depanagem("editar_acl(): Must select at least one Access Control Object");
		return false;
		}
	if (count($vetor_aro) == 0 AND count($aro_grupo_ids) == 0) {
		$this->texto_depanagem("editar_acl(): Must select at least one Access Request Object or Group");
		return false;
		}
	if (empty($permitir)) $permitir = 0;
	if (empty($habilitado)) $habilitado = 0;

	if ($this->adiciona_acl($vetor_aco, $vetor_aro, $aro_grupo_ids, $vetor_axo, $axo_grupo_ids, $permitir, $habilitado, $valor_retorno, $nota, $secao_valor, $acl_id)) return true;
	else {
		$this->texto_depanagem("editar_acl(): error in adiciona_acl()");
		return false;
		}
	}
	
function excluir_acl($acl_id) {
	$this->texto_depanagem("excluir_acl(): ID: $acl_id");
	if (empty($acl_id)) {
		$this->texto_depanagem("excluir_acl(): ACL_ID ($acl_id) está vazio, e é requerido");
		return false;
		}
	$this->db->BeginTrans();
	foreach(array('aco_mapa', 'aro_mapa', 'axo_mapa', 'aro_grupos_mapa', 'axo_grupos_mapa') AS $mapa) {
		$comando_sql = 'DELETE FROM '.$this->_bd_tabela_prefixo.$mapa.' WHERE acl_id='.$acl_id;
		$rs = $this->db->Execute($comando_sql);
		if (!is_object($rs)) {
			$this->debug_db('excluir_acl');
			$this->db->RollBackTrans();
			return false;
			}
		}
	$comando_sql = 'DELETE FROM '.$this->_bd_tabela_prefixo.'acl WHERE id='.$acl_id;
	$this->texto_depanagem('excluir query: '.$comando_sql);
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('excluir_acl');
		$this->db->RollBackTrans();
		return false;
		}
	$this->texto_depanagem("excluir_acl(): excluído ACL ID: $acl_id");
	$this->db->CommitTrans();
	if ($this->_caching == TRUE && $this->_forcar_expirar_cache == TRUE) $this->Cache_Lite->clean('default');
	return TRUE;
	}
	
function ordenar_grupos($grupo_tipo = 'ARO') {
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		}
	$comando_sql = 'select id, superior_id, nome FROM '.$tabela.' ORDER BY superior_id, nome';
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('ordenar_grupos');
		return false;
		}
	$sorted_grupos = array();
	while ($linha = $rs->FetchRow()) {
		$id = &$linha[0];
		$superior_id = &$linha[1];
		$nome = &$linha[2];
		$sorted_grupos[$superior_id][$id] = $nome;
		}
	return $sorted_grupos;
	}
	
function formatar_grupos($sorted_grupos, $type = 'TEXT', $raiz_id = 0, $nivel = 0, $grupos_formatados = NULL) {
	if (!is_array($sorted_grupos)) return FALSE;
	if (!is_array($grupos_formatados)) $grupos_formatados = array();
	if (isset($sorted_grupos[$raiz_id])) {
		$chaves = array_keys($sorted_grupos[$raiz_id]);
		$ultimo_id = end($chaves);
		unset($chaves);
		foreach($sorted_grupos[$raiz_id] AS $id =>$nome) {
			switch (strtoupper($type)) {
				case 'TEXT':
					if (is_numeric($nivel)) $nivel = str_repeat('&nbsp;&nbsp; ', $nivel);
					if (strlen($nivel) >= 8) {
						if ($id == $ultimo_id) {
							$espacamento = substr($nivel, 0, -8).'\'- ';
							$nivel = substr($nivel, 0, -8).'&nbsp;&nbsp; ';
							} 
						else $espacamento = substr($nivel, 0, -8).'|- ';
						} 
					else $espacamento = $nivel;
					$next = $nivel.'|&nbsp; ';
					$text = $espacamento.$nome;
					break;
				case 'HTML':
					$width = $nivel * 20;
					$espacamento = "<img src=\"s.gif\" width=\"$width\">";
					$next = $nivel + 1;
					$text = $espacamento." ".$nome;
					break;
				case 'ARRAY':
					$next = $nivel;
					$text = $nome;
					break;
				default:
					return FALSE;
				}
			$grupos_formatados[$id] = $text;
			if (isset($sorted_grupos[$id])) $grupos_formatados = $this->formatar_grupos($sorted_grupos, $type, $id, $next, $grupos_formatados);
			}
		}
	return $grupos_formatados;
	}
	
function get_grupo_id($valor = NULL, $nome = NULL, $grupo_tipo = 'ARO') {
	$this->texto_depanagem("get_grupo_id(): Valor: $valor, Nome: $nome, Tipo: $grupo_tipo");
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		}
	$nome = trim($nome);
	$valor = trim($valor);
	if (empty($nome) && empty($valor)) {
		$this->texto_depanagem("get_grupo_id(): nome ou valor, ao menos um é requirido");
		return false;
		}
	$comando_sql = 'select id FROM '.$tabela.' WHERE ';
	if (!empty($valor)) $comando_sql.= 'valor='.$this->db->quote($valor);
	else $comando_sql.= 'nome='.$this->db->quote($nome);
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_grupo_id');
		return false;
		}
	$contagem_linha = $rs->RecordCount();
	if ($contagem_linha > 1) {
		$this->texto_depanagem("get_grupo_id(): Retornou $contagem_linha linhas, mas só deveria retornar uma. Faça nomes únicos.");
		return false;
		}
	if ($contagem_linha == 0) {
		$this->texto_depanagem("get_grupo_id(): Retornou nenhuma linha");
		return false;
		}
	$linha = $rs->FetchRow();
	return $linha[0];
	}
	
function get_grupo_subordinado($grupo_id, $grupo_tipo = 'ARO', $recurso = 'NO_RECURSE') {
	$this->texto_depanagem("get_grupo_subordinado(): ID do grupo: $grupo_id Tipo do grupo: $grupo_tipo Recurso: $recurso");
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$grupo_tipo = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
		}
	if (empty($grupo_id)) {
		$this->texto_depanagem("get_grupo_subordinado(): ID ($grupo_id) está vazio, mas é requirido");
		return FALSE;
		}
	$comando_sql = ' select g1.id FROM '.$tabela.'g1';
	switch (strtoupper($recurso)) {
		case 'RECURSE':
			$comando_sql.= ' LEFT JOIN '.$tabela.'g2 ON g2.esq<g1.esq AND g2.dir>g1.dir WHERE g2.id='.$grupo_id;
			break;
		default:
			$comando_sql.= ' WHERE g1.superior_id='.$grupo_id;
		}
	$comando_sql.= ' ORDER BY g1.valor';
	return $this->db->GetCol($comando_sql);
	}
	
function get_grupo_dados($grupo_id, $grupo_tipo = 'ARO') {
	$this->texto_depanagem("get_grupo_dados(): Group_ID: $grupo_id Group Type: $grupo_tipo");
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$grupo_tipo = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		}
	if (empty($grupo_id)) {
		$this->texto_depanagem("get_grupo_dados(): ID ($grupo_id) está vazio, e é requerido");
		return false;
		}
	$comando_sql = 'select id,superior_id,valor,nome,esq,dir FROM '.$tabela.' WHERE id='.$grupo_id;
	$linha = $this->db->GetRow($comando_sql);
	if ($linha) return $linha;

	$this->texto_depanagem("get_objeto_dados(): Group não existe.");
	return false;
	}
	
function get_grupo_superior_id($id, $grupo_tipo = 'ARO') {
	$this->texto_depanagem("get_grupo_superior_id(): ID: $id Group Type: $grupo_tipo");
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		}
	if (empty($id)) {
		$this->texto_depanagem("get_grupo_superior_id(): ID ($id) está vazio, e é requerido");
		return false;
		}
	$comando_sql = 'select superior_id FROM '.$tabela.' WHERE id='.$id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_grupo_superior_id');
		return false;
		}
	$contagem_linha = $rs->RecordCount();
	if ($contagem_linha > 1) {
		$this->texto_depanagem("get_grupo_superior_id(): Retornou $contagem_linha linhas, mas só deveria retornar uma. Tenha a certeza dos nomes serem únicos.");
		return false;
		}
	if ($contagem_linha == 0) {
		$this->texto_depanagem("get_grupo_superior_id(): Retornou $contagem_linha linhas");
		return false;
		}
	$linha = $rs->FetchRow();
	return $linha[0];
	}
	
function get_raiz_grupo_id($grupo_tipo = 'ARO') {
	$this->texto_depanagem('get_raiz_grupo_id():Group Type: '.$grupo_tipo);
	switch (strtolower($grupo_tipo)) {
		case 'axo':
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		case 'aro':
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		default:
			$this->texto_depanagem('get_raiz_grupo_id():Invalid Group Type: '.$grupo_tipo);
			return FALSE;
		}
	$comando_sql = 'select id FROM '.$tabela.' WHERE superior_id=0';
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_raiz_grupo_id');
		return FALSE;
		}
	$contagem_linha = $rs->RecordCount();
	switch ($contagem_linha) {
		case 1:
			$linha = $rs->FetchRow();
			return $linha[0];
		case 0:
			$this->texto_depanagem('get_raiz_grupo_id():Returned 0 rows,you do not have a root grupo defined yet.');
			return FALSE;
		}
	$this->texto_depanagem('get_raiz_grupo_id():Returned'.$contagem_linha.'rows,can only return one.Your tree is very broken.');
	return FALSE;
	}
	
function adicionar_grupo($valor, $nome, $superior_id = 0, $grupo_tipo = 'aro') {
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$grupo_tipo = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		}
	$this->texto_depanagem("adicionar_grupo(): Nome: $nome Valor: $valor ID do Superior: $superior_id Tipo de Grupo: $grupo_tipo");
	$nome = trim($nome);
	$valor = trim($valor);
	if (!$nome) {
		$this->texto_depanagem("adicionar_grupo(): nome ($nome) ou ID do superior ($superior_id) está vazio, e é requirido");
		return false;
		}
	$id_inserido = $this->db->GenID($this->_bd_tabela_prefixo.$grupo_tipo.'_grupos_id_seq', 10);
	if ($valor === '') $valor = $id_inserido;
	$this->db->BeginTrans();
	if ($superior_id == 0) {
		$comando_sql = 'select id FROM '.$tabela.' WHERE superior_id=0';
		$rs = $this->db->Execute($comando_sql);
		if (!is_object($rs)) {
			$this->debug_db('adicionar_grupo');
			$this->db->RollBackTrans();
			return FALSE;
			}
		if ($rs->RowCount() > 0) {
			$this->texto_depanagem('adicionar_grupo():Um grupo raiz já existe.');
			$this->db->RollBackTrans();
			return FALSE;
			}
		$superior_esq = 0;
		$superior_dir = 1;
		} 
	else {
		if (empty($superior_id)) {
			$this->texto_depanagem("adicionar_grupo (): ID do superior ($superior_id) está vazio, e é requerido");
			$this->db->RollbackTrans();
			return FALSE;
			}
		$comando_sql = 'select id,esq,dir FROM '.$tabela.' WHERE id='.$superior_id;
		$linha = $this->db->GetRow($comando_sql);
		if (!is_array($linha)) {
			$this->debug_db('adicionar_grupo');
			$this->db->RollBackTrans();
			return FALSE;
			}
		if (empty($linha)) {
			$this->texto_depanagem('adicionar_grupo():ID do superior: '.$superior_id.' não encontrado.');
			$this->db->RollBackTrans();
			return FALSE;
			}
		$superior_esq = &$linha[1];
		$superior_dir = &$linha[2];
		$comando_sql = 'UPDATE '.$tabela.' SET dir=dir+2 WHERE dir >='.$superior_dir;
		$rs = $this->db->Execute($comando_sql);
		if (!is_object($rs)) {
			$this->debug_db('adicionar_grupo');
			$this->db->RollBackTrans();
			return FALSE;
			}
		$comando_sql = 'UPDATE '.$tabela.' SET esq=esq+2 WHERE esq >'.$superior_dir;
		$rs = $this->db->Execute($comando_sql);
		if (!is_object($rs)) {
			$this->debug_db('adicionar_grupo');
			$this->db->RollBackTrans();
			return FALSE;
			}
		}
			
	$comando_sql = 'INSERT INTO '.$tabela.'(id,superior_id,nome,valor,esq,dir)values('.$id_inserido.','.$superior_id.','.$this->db->quote($nome).','.$this->db->quote($valor).','.$superior_dir.','. ($superior_dir + 1).')';
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('adicionar_grupo');
		$this->db->RollBackTrans();
		return FALSE;
		}
	$this->db->CommitTrans();
	$this->texto_depanagem('adicionar_grupo():Adicionado grupo com ID: '.$id_inserido);
	return $id_inserido;
	}
	
function get_grupo_objetos($grupo_id, $grupo_tipo = 'ARO', $opcao = 'NO_RECURSE') {

	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$objeto_tabela = $this->_bd_tabela_prefixo.'axo';
			$tabela_grupo = $this->_bd_tabela_prefixo.'axo_grupos';
			$tabela_mapa = $this->_bd_tabela_prefixo.'grupos_axo_mapa';
			break;
		default:
			$grupo_tipo = 'aro';
			$objeto_tabela = $this->_bd_tabela_prefixo.'aro';
			$tabela_grupo = $this->_bd_tabela_prefixo.'aro_grupos';
			$tabela_mapa = $this->_bd_tabela_prefixo.'grupos_aro_mapa';
			break;
		}
	$this->texto_depanagem("get_grupo_objetos(): ID do grupo: $grupo_id");
	if (empty($grupo_id)) {
		$this->texto_depanagem("get_grupo_objetos(): ID do grupo:  ($grupo_id) está vazio, e é requerido");
		return false;
		}
	
	$comando_sql  = '	SELECT o.valor_secao, o.valor';
	if ($opcao == 'RECURSE') $comando_sql .= '	FROM '.$tabela_grupo.' g2 JOIN	'.$tabela_grupo.' g1 ON g1.lft>=g2.lft AND g1.rgt<=g2.rgt	JOIN '.$tabela_mapa.' gm ON gm.grupo_id=g1.id	JOIN '.$objeto_tabela.' o ON o.id=gm.'.$grupo_tipo.'_id	WHERE	g2.id='.$grupo_id;
	else $comando_sql .= ' FROM '.$tabela_mapa.' gm JOIN '.$objeto_tabela.' o ON o.id=gm.'.$grupo_tipo.'_id WHERE gm.grupo_id='.$grupo_id;

	
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_grupo_objetos');
		return false;
		}
	$this->texto_depanagem("get_grupo_objetos(): Got grupo objects, formatting array.");
	$retornar = array();
	while ($linha = $rs->FetchRow()) {
		$secao = &$linha[0];
		$valor = &$linha[1];
		$retornar[$secao][] = $valor;
		}
	return $retornar;
	}
	
function adicionar_grupo_objeto($grupo_id, $objeto_valor_secao, $objeto_valor, $grupo_tipo = 'ARO') {
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'grupos_axo_mapa';
			$objeto_tabela = $this->_bd_tabela_prefixo.'axo';
			$tabela_grupo = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$grupo_tipo = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'grupos_aro_mapa';
			$objeto_tabela = $this->_bd_tabela_prefixo.'aro';
			$tabela_grupo = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		}
	
	$this->texto_depanagem("adicionar_grupo_objeto(): ID do Grupo: $grupo_id Valor da Seção: $objeto_valor_secao Valor: $objeto_valor Group Type: $grupo_tipo");
	$objeto_valor_secao = trim($objeto_valor_secao);
	$objeto_valor = trim($objeto_valor);
	if (empty($grupo_id) || empty($objeto_valor) || empty($objeto_valor_secao)) {
		$this->texto_depanagem("adicionar_grupo_objeto(): ID do Grupo: ($grupo_id) OR Value ($objeto_valor) OR Section valor ($objeto_valor_secao) está vazio, e é requerido");
		return false;
		}
	$comando_sql = 'SELECT o.id AS id, g.id AS grupo_id, gm.grupo_id AS membro FROM '.$objeto_tabela.' o LEFT JOIN '.$tabela_grupo.' g ON g.id='.$grupo_id.' LEFT JOIN '.$tabela.' gm ON (gm.'.$grupo_tipo.'_id=o.id AND gm.grupo_id=g.id) WHERE (o.valor_secao='.$this->db->quote($objeto_valor_secao).' AND o.valor='.$this->db->quote($objeto_valor).')';
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('adicionar_grupo_objeto');
		return FALSE;
		}
	if ($rs->RecordCount() != 1) {
		$this->texto_depanagem('adicionar_grupo_objeto():Value('.$objeto_valor.') ou Section valor('.$objeto_valor_secao.')is invalid.Does this object exist?');
		return FALSE;
		}
	$linha = $rs->FetchRow();
	if ($linha[1] != $grupo_id) {
		$this->texto_depanagem('adicionar_grupo_objeto():Group ID('.$grupo_id.')is invalid.Does this grupo exist?');
		return FALSE;
		}
	if ($linha[1] == $linha[2]) {
		$this->texto_depanagem('adicionar_grupo_objeto():Object: ('.$objeto_valor_secao.'-> '.$objeto_valor.')is already a membro of Group: ('.$grupo_id.')');
		return TRUE;
		}
	$objeto_id = $linha[0];
	$comando_sql = 'INSERT INTO '.$tabela.'(grupo_id,'.$grupo_tipo.'_id) VALUES ('.$grupo_id.','.$objeto_id.')';
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('adicionar_grupo_objeto');
		return FALSE;
		}
	$this->texto_depanagem('adicionar_grupo_objeto():Added Object: '.$objeto_id.'to ID do Grupo: '.$grupo_id);
	if ($this->_caching == TRUE && $this->_forcar_expirar_cache == TRUE) {
		$this->Cache_Lite->clean('default');
		}
	return TRUE;
	}
	
function excluir_grupo_objeto($grupo_id, $objeto_valor_secao, $objeto_valor, $grupo_tipo = 'ARO') {
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'grupos_axo_mapa';
			break;
		default:
			$grupo_tipo = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'grupos_aro_mapa';
			break;
		}
	$this->texto_depanagem("excluir_grupo_objeto(): ID do Grupo: $grupo_id Section valor: $objeto_valor_secao Valor: $objeto_valor");
	$objeto_valor_secao = trim($objeto_valor_secao);
	$objeto_valor = trim($objeto_valor);
	if (empty($grupo_id) || empty($objeto_valor) || empty($objeto_valor_secao)) {
		$this->texto_depanagem("excluir_grupo_objeto(): ID do Grupo:  ($grupo_id) OR Section valor: $objeto_valor_secao OR Value ($objeto_valor) está vazio, e é requerido");
		return false;
		}
	if (!$objeto_id = $this->get_objeto_id($objeto_valor_secao, $objeto_valor, $grupo_tipo)) {
		$this->texto_depanagem("excluir_grupo_objeto (): Group ID ($grupo_id) OR Value ($objeto_valor) OR Section valor ($objeto_valor_secao) is invalid. Does this object exist?");
		return FALSE;
		}
	$comando_sql = 'DELETE FROM '.$tabela.' WHERE grupo_id='.$grupo_id.' AND '.$grupo_tipo.'_id='.$objeto_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('excluir_grupo_objeto');
		return false;
		}
	$this->texto_depanagem("excluir_grupo_objeto(): Deleted Valor: $objeto_valor to ID do Grupo: $grupo_id assignment");
	if ($this->_caching == TRUE && $this->_forcar_expirar_cache == TRUE) $this->Cache_Lite->clean('default');
	return true;
	}
	
function edit_grupo($grupo_id, $valor = NULL, $nome = NULL, $superior_id = NULL, $grupo_tipo = 'ARO') {
	$this->texto_depanagem("edit_grupo(): ID: $grupo_id Name: $nome Valor: $valor Parent ID: $superior_id Group Type: $grupo_tipo");
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$grupo_tipo = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		}
	if (empty($grupo_id)) {
		$this->texto_depanagem('edit_grupo():Group ID('.$grupo_id.')is empty,this is required');
		return FALSE;
		}
	if (!is_array($curr = $this->get_grupo_dados($grupo_id, $grupo_tipo))) {
		$this->texto_depanagem('edit_grupo():Invalid ID do Grupo: '.$grupo_id);
		return FALSE;
		}
	$nome = trim($nome);
	if ($nome == $curr[3]) unset($nome);
	if ($superior_id == $curr[1]) unset($superior_id);
	if (!empty($superior_id)) {
		if ($grupo_id == $superior_id) {
			$this->texto_depanagem('edit_grupo():Groups can\'t be a parent to themselves. Incest is bad. ;)');
			return FALSE;
			}
		$subordinado_ids = $this->get_grupo_subordinado($grupo_id, $grupo_tipo, 'RECURSE');
		if (is_array($subordinado_ids)) {
			if (@in_array($superior_id, $subordinado_ids)) {
				$this->texto_depanagem('edit_grupo(): Groups can\'t be re-parented to their own children,this would be incestuous!');
				return FALSE;
				}
			}
		unset($subordinado_ids);
		if (!$this->get_grupo_dados($superior_id, $grupo_tipo)) {
			$this->texto_depanagem('edit_grupo():Parent Group('.$superior_id.')doesn\'t exist');
			return FALSE;
			}
		}
	$set = array();
	if (!empty($nome)) $set[] = 'nome='.$this->db->quote($nome);
	if (!empty($superior_id)) $set[] = 'superior_id='.$superior_id;
	if (!empty($valor)) $set[] = 'valor='.$this->db->quote($valor);
	if (empty($set)) {
		$this->texto_depanagem('edit_grupo(): Nothing to update.');
		return FALSE;
		}
	$this->db->BeginTrans();
	$comando_sql = 'UPDATE '.$tabela.' SET '.implode(',', $set).' WHERE id='.$grupo_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('edit_grupo');
		$this->db->RollbackTrans();
		return FALSE;
		}
	$this->texto_depanagem('edit_grupo(): Modified grupo ID: '.$grupo_id);
	if (!empty($superior_id)) {
		if (!$this->_rebuild_tree($tabela, $this->get_raiz_grupo_id($grupo_tipo))) {
			$this->db->RollbackTrans();
			return FALSE;
			}
		}
	$this->db->CommitTrans();
	if ($this->_caching == TRUE && $this->_forcar_expirar_cache == TRUE) {
		$this->Cache_Lite->clean('default');
		}
	return TRUE;
	}
	
function rebuild_tree($grupo_tipo = 'ARO', $grupo_id = NULL, $esquerda = 1) {
	$this->texto_depanagem("rebuild_tree(): Group Type: $grupo_tipo ID do Grupo: $grupo_id Left: $esquerda");
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			break;
		default:
			$grupo_tipo = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			break;
		}
	if (!isset($grupo_id)) {
		if ($grupo_id = $this->get_raiz_grupo_id($grupo_tipo)) {
			$esquerda = 1;
			$this->texto_depanagem('rebuild_tree(): No Group ID Specified, using Root ID do Grupo: '.$grupo_id);
			} 
		else {
			$this->texto_depanagem('rebuild_tree(): A Root grupo could not be found, are there any grupos defined?');
			return FALSE;
			}
		}
	$this->db->BeginTrans();
	$rebuilt = $this->_rebuild_tree($tabela, $grupo_id, $esquerda);
	if ($rebuilt === FALSE) {
		$this->texto_depanagem('rebuild_tree(): Error rebuilding tree!');
		$this->db->RollBackTrans();
		return FALSE;
		}
	$this->db->CommitTrans();
	$this->texto_depanagem('rebuild_tree(): Tree rebuilt.');
	return TRUE;
	}
	
function _rebuild_tree($tabela, $grupo_id, $esquerda = 1) {
	$this->texto_depanagem("_rebuild_tree(): Tabela: $tabela ID do Group: $grupo_id Esquerdo: $esquerda");
	$comando_sql = 'SELECT id FROM '.$tabela.' WHERE superior_id='.$grupo_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('_rebuild_tree');
		return FALSE;
		}
	$direita = $esquerda + 1;
	while ($linha = $rs->FetchRow()) {
		$direita = $this->_rebuild_tree($tabela, $linha[0], $direita);
		if ($direita === FALSE)	return FALSE;
		}
	$comando_sql = 'UPDATE '.$tabela.' SET esq='.$esquerda.', dir='.$direita.' WHERE id='.$grupo_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('_rebuild_tree');
		return FALSE;
		}
	return $direita + 1;
	}
	
function excluir_grupo($grupo_id, $recolocar_superior = TRUE, $grupo_tipo = 'ARO') {
	switch (strtolower(trim($grupo_tipo))) {
		case 'axo':
			$grupo_tipo = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo_grupos';
			$grupos_mapa_tabela = $this->_bd_tabela_prefixo.'axo_grupos_mapa';
			$grupos_objeto_mapa_tabela = $this->_bd_tabela_prefixo.'grupos_axo_mapa';
			break;
		default:
			$grupo_tipo = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro_grupos';
			$grupos_mapa_tabela = $this->_bd_tabela_prefixo.'aro_grupos_mapa';
			$grupos_objeto_mapa_tabela = $this->_bd_tabela_prefixo.'grupos_aro_mapa';
			break;
		}
	$this->texto_depanagem("excluir_grupo(): ID: $grupo_id Reparent Children: $recolocar_superior Group Type: $grupo_tipo");
	if (empty($grupo_id)) {
		$this->texto_depanagem("excluir_grupo(): Group ID ($grupo_id) está vazio, e é requerido");
		return false;
		}
	$comando_sql = 'SELECT id, superior_id, nome, esq, dir FROM '.$tabela.' WHERE id='.$grupo_id;
	$detalhes_grupo = $this->db->GetRow($comando_sql);
	if (!is_array($detalhes_grupo)) {
		$this->debug_db('excluir_grupo');
		return false;
		}

	$superior_id = $detalhes_grupo[1];
	$esquerda = $detalhes_grupo[3];
	$direita = $detalhes_grupo[4];
	$this->db->BeginTrans();
	$subordinado_ids = $this->get_grupo_subordinado($grupo_id, $grupo_tipo, 'RECURSE');
	if ($superior_id == 0) {
		$comando_sql = 'SELECT COUNT(*) FROM '.$tabela.' WHERE superior_id='.$grupo_id;
		$subordinados_contagem = $this->db->GetOne($comando_sql);
		if (($subordinados_contagem > 1) && $recolocar_superior) {
			$this->texto_depanagem('excluir_grupo (): Você não pode excluir o grupo raiz e redefinir as dependencias, pois criaria múltiplos grupos raízes.');
			$this->db->RollbackTrans();
			return FALSE;
			}
		}
	$sucesso = FALSE;
	switch (TRUE) {
		case !is_array($subordinado_ids):
		case count($subordinado_ids) == 0:
			$comando_sql = 'DELETE FROM '.$grupos_mapa_tabela.' WHERE grupo_id='.$grupo_id;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'DELETE FROM '.$grupos_objeto_mapa_tabela.' WHERE grupo_id='.$grupo_id;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'DELETE FROM '.$tabela.' WHERE id='.$grupo_id;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'UPDATE '.$tabela.' SET esq=esq-'. ($direita - $esquerda + 1).' WHERE esq>'.$direita;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'UPDATE '.$tabela.' SET dir=dir-'. ($direita - $esquerda + 1).' WHERE dir>'.$direita;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$sucesso = TRUE;
			break;
		case $recolocar_superior == TRUE: 
			$comando_sql = 'DELETE FROM '.$grupos_mapa_tabela.' WHERE grupo_id='.$grupo_id;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'DELETE FROM '.$grupos_objeto_mapa_tabela.' WHERE grupo_id='.$grupo_id;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'DELETE FROM '.$tabela.' WHERE id='.$grupo_id;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'UPDATE '.$tabela.' SET superior_id='.$superior_id.' WHERE superior_id='.$grupo_id;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'UPDATE '.$tabela.' SET esq=esq-1, dir=dir-1 WHERE esq>'.$esquerda.' AND dir<'.$direita;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'UPDATE '.$tabela.' SET esq=esq-2 WHERE esq>'.$direita;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'UPDATE '.$tabela.' SET dir=dir-2 WHERE dir>'.$direita;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$sucesso = TRUE;
			break;
		default:
			$grupo_ids = $subordinado_ids;
			$grupo_ids[] = $grupo_id;
			$comando_sql = 'DELETE FROM '.$grupos_mapa_tabela.' WHERE grupo_id IN ('.implode(',', $grupo_ids).')';
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'DELETE FROM '.$grupos_objeto_mapa_tabela.' WHERE grupo_id IN ('.implode(',', $grupo_ids).')';
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'DELETE FROM '.$tabela.' WHERE id IN ('.implode(',', $grupo_ids).')';
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'UPDATE '.$tabela.' SET esq=esq-'. ($direita - $esquerda + 1).' WHERE esq>'.$direita;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$comando_sql = 'UPDATE '.$tabela.' SET dir=dir-'. ($direita - $esquerda + 1).' WHERE dir>'.$direita;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) break;
			$sucesso = TRUE;
		}
	if (!$sucesso) {
		$this->debug_db('excluir_grupo');
		$this->db->RollBackTrans();
		return false;
		}
	$this->texto_depanagem("excluir_grupo(): excluído grupo ID: $grupo_id");
	$this->db->CommitTrans();
	if ($this->_caching == TRUE && $this->_forcar_expirar_cache == TRUE) $this->Cache_Lite->clean('default');
	return true;
	}
	
function get_objeto($secao_valor = null, $retornar_escondido = 1, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			break;
		case 'acl':
			$tipo_objeto = 'acl';
			$tabela = $this->_bd_tabela_prefixo.'acl';
			break;
		default:
			$this->texto_depanagem('get_objeto():  Objeto Tipo inválido: '.$tipo_objeto);
			return FALSE;
		}
	$this->texto_depanagem("get_objeto(): Valor da Seção: $secao_valor Tipo de Objeto: $tipo_objeto");
	$comando_sql = 'SELECT id FROM '.$tabela;
	$onde = array();
	if (!empty($secao_valor)) $onde[] = 'valor_secao='.$this->db->quote($secao_valor);
	if ($retornar_escondido == 0 && $tipo_objeto != 'acl')	$onde[] = 'escondido=0';
	if (!empty($onde)) $comando_sql.= ' WHERE '.implode(' AND ', $onde);
	$rs = $this->db->GetCol($comando_sql);
	if (!is_array($rs)) {
		$this->debug_db('get_objeto');
		return false;
		}
	return $rs;
	}
	
function get_objetos_desagrupados($retornar_escondido = 1, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			break;
		default:
			$this->texto_depanagem('get_objetos_desagrupados(): Tipo de objeto inválido: '.$tipo_objeto);
			return FALSE;
		}
	$this->texto_depanagem("get_objetos_desagrupados(): Tipo de objeto: $tipo_objeto");
	$comando_sql = 'SELECT id FROM '.$tabela.' a	LEFT JOIN '.$this->_bd_tabela_prefixo.'grupos_'.$tipo_objeto.'_mapa b ON a.id = b.'.$tipo_objeto.'_id';
	$onde = array();
	$onde[] = 'b.grupo_id IS NULL';
	if ($retornar_escondido == 0) $onde[] = 'a.escondido=0';
	if (!empty($onde)) $comando_sql.= ' WHERE '.implode(' AND ', $onde);
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_objetos_desagrupados');
		return false;
		}
	while (!$rs->EOF) {
		$retornar[] = $rs->fields[0];
		$rs->MoveNext();
		}
	return $retornar;
	}
	
function get_objetos($secao_valor = NULL, $retornar_escondido = 1, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			break;
		default:
			$this->texto_depanagem('get_objetos():  Objeto Tipo inválido: '.$tipo_objeto);
			return FALSE;
		}
	$this->texto_depanagem("get_objetos(): Valor da Seção: $secao_valor Tipo de objeto: $tipo_objeto");
	$comando_sql = 'SELECT valor_secao, valor FROM '.$tabela;
	$onde = array();
	if (!empty($secao_valor)) $onde[] = 'valor_secao='.$this->db->quote($secao_valor);

	if ($retornar_escondido == 0) $onde[] = 'escondido=0';

	if (!empty($onde)) $comando_sql.= ' WHERE '.implode(' AND ', $onde);

	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_objetos');
		return FALSE;
		}
	$retornar = array();
	while ($linha = $rs->FetchRow()) $retornar[$linha[0]][] = $linha[1];
	return $retornar;
	}
	
function get_objeto_dados($objeto_id, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			break;
		default:
			$this->texto_depanagem('get_objeto_dados(): Tipo de objeto inválido: '.$tipo_objeto);
			return FALSE;
		}
	$this->texto_depanagem("get_objeto_dados(): ID do objeto: $objeto_id Tipo de objeto: $tipo_objeto");
	if (empty($objeto_id)) {
		$this->texto_depanagem("get_objeto_dados(): ID do objeto ($objeto_id) está vazio e é requerido");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("get_objeto_dados(): ID do objeto ($tipo_objeto) está vazio e é requerido");
		return false;
		}
	$comando_sql = 'SELECT valor_secao, valor, valor_ordem, nome, escondido FROM '.$tabela.' WHERE id='.$objeto_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_objeto_dados');
		return false;
		}
	if ($rs->RecordCount() < 1) {
		$this->texto_depanagem('get_objeto_dados(): Returned  '.$contagem_linha.' rows');
		return FALSE;
		}
	return $rs->GetRows();
	}
	
function get_objeto_id($secao_valor, $valor, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			break;
		default:
			$this->texto_depanagem('get_objeto_id(): Tipo de objeto inválido: '.$tipo_objeto);
			return FALSE;
		}
	$this->texto_depanagem("get_objeto_id(): Valor da Seção: $secao_valor Valor: $valor Tipo de objeto: $tipo_objeto");
	$secao_valor = trim($secao_valor);
	$valor = trim($valor);
	if (empty($secao_valor) && empty($valor)) {
		$this->texto_depanagem("get_objeto_id(): Valor da Seção ($valor) E valor ($valor) estão vazios e são requeridos");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("get_objeto_id(): Valor da Seção ($tipo_objeto) está vazio e é requerido.");
		return false;
		}
	$comando_sql = 'SELECT id FROM '.$tabela.' WHERE valor_secao='.$this->db->quote($secao_valor).' AND valor='.$this->db->quote($valor);
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_objeto_id');
		return false;
		}
	$contagem_linha = $rs->RecordCount();
	if ($contagem_linha > 1) {
		$this->texto_depanagem("get_objeto_id(): Retornou $contagem_linha linhas, e só pode retornar uma. Isto não deveria ter acontecido, o banco ded dados pode estar deixando de verificar fields chave.");
		return false;
		}
	if ($contagem_linha == 0) {
		$this->texto_depanagem("get_objeto_id(): Retornou $contagem_linha linhas");
		return false;
		}
	$linha = $rs->FetchRow();
	return $linha[0];
	}
	
function get_objeto_valor_secao($objeto_id, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
	case 'aco':
		$tipo_objeto = 'aco';
		$tabela = $this->_bd_tabela_prefixo.'aco';
		break;
	case 'aro':
		$tipo_objeto = 'aro';
		$tabela = $this->_bd_tabela_prefixo.'aro';
		break;
	case 'axo':
		$tipo_objeto = 'axo';
		$tabela = $this->_bd_tabela_prefixo.'axo';
		break;
	default:
		$this->texto_depanagem('get_objeto_valor_secao(): Tipo de objeto inválido: '.$tipo_objeto);
		return FALSE;
		}
	$this->texto_depanagem("get_objeto_valor_secao(): ID do objeto: $objeto_id Object Tipo: $tipo_objeto");
	if (empty($objeto_id)) {
		$this->texto_depanagem("get_objeto_valor_secao(): ID do objeto ($objeto_id) está vazio e é requerido");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("get_objeto_valor_secao(): Tipo de objeto ($tipo_objeto) está vazio e é requerido");
		return false;
		}
	$comando_sql = 'SELECT valor_secao FROM '.$tabela.' WHERE id='.$objeto_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_objeto_valor_secao');
		return false;
		}
	$contagem_linha = $rs->RecordCount();
	if ($contagem_linha > 1) {
		$this->texto_depanagem("get_objeto_valor_secao(): Retornou $contagem_linha linhas, e só pode retornar uma");
		return false;
		}
	if ($contagem_linha == 0) {
		$this->texto_depanagem("get_objeto_valor_secao(): Returned $contagem_linha linhas");
		return false;
		}
	$linha = $rs->FetchRow();
	return $linha[0];
	}
	
function get_objeto_grupos($objeto_id, $tipo_objeto = 'ARO', $opcao = 'NO_RECURSE') {
	$this->texto_depanagem('get_objeto_grupos(): Object ID: '.$objeto_id.' Objeto Tipo: '.$tipo_objeto.' Option: '.$opcao);
	switch (strtolower(trim($tipo_objeto))) {
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela_grupo = $this->_bd_tabela_prefixo.'axo_grupos';
			$tabela_mapa = $this->_bd_tabela_prefixo.'grupos_axo_mapa';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela_grupo = $this->_bd_tabela_prefixo.'aro_grupos';
			$tabela_mapa = $this->_bd_tabela_prefixo.'grupos_aro_mapa';
			break;
		default:
			$this->texto_depanagem('get_objeto_grupos():  Objeto Tipo inválido: '.$tipo_objeto);
			return FALSE;
		}
	if (empty($objeto_id)) {
		$this->texto_depanagem('get_objeto_grupos(): Object ID: ('.$objeto_id.') está vazio, e é requerido');
		return FALSE;
		}
	if (strtoupper($opcao) == 'RECURSE') $comando_sql = 'SELECT	DISTINCT g.id AS grupo_id	FROM '.$tabela_mapa.' gm LEFT JOIN	'.$tabela_grupo.' g1 ON g1.id=gm.grupo_id	LEFT JOIN	'.$tabela_grupo.' g ON g.esq<=g1.esq AND g.dir>=g1.dir';
	else $comando_sql = 'SELECT	gm.grupo_id	FROM '.$tabela_mapa.' gm';
	$comando_sql.= ' WHERE gm.'.$tipo_objeto.'_id='.$objeto_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_objeto_grupos');
		return FALSE;
		}
	$retornar = array();
	while ($linha = $rs->FetchRow()) $retornar[] = $linha[0];
	return $retornar;
	}
	
function adicionar_objeto($secao_valor, $nome, $valor = 0, $ordem = 0, $escondido = 0, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'aco_secoes';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'aro_secoes';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'axo_secoes';
			break;
		default:
			$this->texto_depanagem('adicionar_objeto():  Objeto Tipo inválido: '.$tipo_objeto);
			return FALSE;
		}
	
	$this->texto_depanagem("adicionar_objeto(): Valor da Seção: $secao_valor Valor: $valor Order: $ordem Name: $nome Objeto Tipo: $tipo_objeto");
	
	$secao_valor = trim($secao_valor);
	$nome = trim($nome);
	$valor = trim($valor);
	$ordem = trim($ordem);
	$escondido = intval($escondido);
	if ($ordem == NULL || $ordem == '') $ordem = 0;
	if (empty($nome) || empty($secao_valor)) {
		$this->texto_depanagem("adicionar_objeto(): nome ($nome) ou secao_valor ($secao_valor) está vazio, e é requerido");
		return false;
		}
	if (strlen($nome) >= 255 || strlen($valor) >= 230) {
		$this->texto_depanagem("adicionar_objeto(): nome ($nome) ou valor ($valor) grande demais.");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("adicionar_objeto(): Objeto Tipo ($tipo_objeto) está vazio, e é requerido");
		return false;
		}
		
	$comando_sql = 'SELECT CASE WHEN o.id IS NULL THEN 0 ELSE 1 END AS object_exists FROM '.$objeto_tabela_secoes.' s LEFT JOIN '.$tabela.' o ON (s.valor=o.valor_secao AND o.valor='.$this->db->quote($valor).') WHERE	s.valor='.$this->db->quote($secao_valor);
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('adicionar_objeto');
		return FALSE;
		}
	if ($rs->RecordCount() != 1) {
		$this->texto_depanagem("adicionar_objeto(): Valor da Seção: $secao_valor Objeto Tipo ($tipo_objeto) não existe, é requerido");
		return false;
		}
		
	$linha = $rs->FetchRow();
	if ($linha[0] == 1) return true;
	$id_inserido = $this->db->GenID($this->_bd_tabela_prefixo.$tipo_objeto.'_seq', 10);
	$comando_sql = 'INSERT INTO '.$tabela.' (id,valor_secao, valor,valor_ordem, nome, escondido) VALUES ('.$id_inserido.','.$this->db->quote($secao_valor).','.$this->db->quote($valor).','.$ordem.','.$this->db->quote($nome).','.$escondido.')';
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('adicionar_objeto');
		return false;
		}
	$this->texto_depanagem("adicionar_objeto(): Added object as ID: $id_inserido");
	return $id_inserido;
	}
	
function editar_objeto($objeto_id, $secao_valor, $nome, $valor = 0, $ordem = 0, $escondido = 0, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'aco_mapa';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'aro_mapa';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'axo_mapa';
			break;
		}
	$this->texto_depanagem("editar_objeto(): ID: $objeto_id Valor da Seção: $secao_valor Valor: $valor Order: $ordem Name: $nome Objeto Tipo: $tipo_objeto");
	$secao_valor = trim($secao_valor);
	$nome = trim($nome);
	$valor = trim($valor);
	$ordem = trim($ordem);
	$escondido = intval($escondido);
	if (empty($objeto_id) || empty($secao_valor)) {
		$this->texto_depanagem("editar_objeto(): Object ID ($objeto_id) OR Valor da Seção ($secao_valor) está vazio, e é requerido");
		return false;
		}
	if (empty($nome)) {
		$this->texto_depanagem("editar_objeto(): nome ($nome) está vazio, e é requerido");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("editar_objeto(): Objeto Tipo ($tipo_objeto) está vazio, e é requerido");
		return false;
		}
	$this->db->BeginTrans();
	$comando_sql = 'SELECT valor, valor_secao FROM '.$tabela.' WHERE id='.$objeto_id;
	$antigo = $this->db->GetRow($comando_sql);
	$comando_sql = ' UPDATE	'.$tabela.'	SET	valor_secao='.$this->db->quote($secao_valor).',	valor='.$this->db->quote($valor).',	valor_ordem='.$this->db->quote($ordem).',	nome='.$this->db->quote($nome).',	escondido='.$escondido.' WHERE	id='.$objeto_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('editar_objeto');
		$this->db->RollbackTrans();
		return false;
		}
	$this->texto_depanagem('editar_objeto(): Modificado '.strtoupper($tipo_objeto).' ID: '.$objeto_id);
	if ($antigo[0] != $valor || $antigo[1] != $secao_valor) {
		$this->texto_depanagem("editar_objeto(): Value OR Valor da Seção Changed, update other tables.");
		$comando_sql = 'UPDATE '.$objeto_mapa_tabela.' SET valor='.$this->db->quote($valor).', valor_secao='.$this->db->quote($secao_valor).'	WHERE	valor_secao='.$this->db->quote($antigo[1]).'	AND	valor='.$this->db->quote($antigo[0]);
		$rs = $this->db->Execute($comando_sql);
		if (!is_object($rs)) {
			$this->debug_db('editar_objeto');
			$this->db->RollbackTrans();
			return FALSE;
			}
		$this->texto_depanagem('editar_objeto(): Modified Map Valor: '.$valor.' Valor da Seção: '.$secao_valor);
		}
	$this->db->CommitTrans();
	return TRUE;
	}
	
function excluir_objeto($objeto_id, $tipo_objeto = NULL, $apagar = FALSE) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'aco_mapa';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'aro_mapa';
			$grupos_mapa_tabela = $this->_bd_tabela_prefixo.'aro_grupos_mapa';
			$objeto_grupo_tabela = $this->_bd_tabela_prefixo.'grupos_aro_mapa';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'axo_mapa';
			$grupos_mapa_tabela = $this->_bd_tabela_prefixo.'axo_grupos_mapa';
			$objeto_grupo_tabela = $this->_bd_tabela_prefixo.'grupos_axo_mapa';
			break;
		default:
			$this->texto_depanagem('excluir_objeto():  Objeto Tipo inválido: '.$tipo_objeto);
			return FALSE;
		}
	$this->texto_depanagem("excluir_objeto(): ID: $objeto_id Objeto Tipo: $tipo_objeto, Erase all referencing objects: $apagar");
	if (empty($objeto_id)) {
		$this->texto_depanagem("excluir_objeto(): Object ID ($objeto_id) está vazio, e é requerido");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("excluir_objeto(): Objeto Tipo ($tipo_objeto) está vazio, e é requerido");
		return false;
		}
	$this->db->BeginTrans();
	$comando_sql = 'SELECT valor_secao, valor FROM '.$tabela.' WHERE id='.$objeto_id;
	$objeto = $this->db->GetRow($comando_sql);
	if (empty($objeto)) {
		$this->texto_depanagem('excluir_objeto(): The specified object ('.strtoupper($tipo_objeto).' ID: '.$objeto_id.') could not be found.');
		$this->db->RollbackTrans();
		return FALSE;
	}
	$secao_valor = $objeto[0];
	$valor = $objeto[1];
	$comando_sql = "SELECT acl_id FROM $objeto_mapa_tabela WHERE valor='$valor' AND valor_secao='$secao_valor'";
	$acl_ids = $this->db->GetCol($comando_sql);
	if ($apagar) {
		$this->texto_depanagem("excluir_objeto(): Erase was set to TRUE, excluir all referencing objects");
		if ($tipo_objeto == "aro"	|| $tipo_objeto == "axo") {
			$comando_sql = 'DELETE FROM '.$objeto_grupo_tabela.' WHERE '.$tipo_objeto.'_id='.$objeto_id;
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) {
				$this->debug_db('editar_objeto');
				$this->db->RollBackTrans();
				return false;
			}
		}
		if (!empty($acl_ids)) {
			if ($tipo_objeto == 'aco') 	$acl_ids_orfaos = $acl_ids;
			else {
				$comando_sql = "DELETE FROM $objeto_mapa_tabela WHERE valor_secao='$secao_valor' AND valor='$valor'";
				$this->db->Execute($comando_sql);
				if (!is_object($rs)) {
					$this->debug_db('editar_objeto');
					$this->db->RollBackTrans();
					return false;
					}
				$sql_acl_ids = implode(",", $acl_ids);
				$comando_sql = ' SELECT	a.id FROM	'.$this->_bd_tabela_prefixo.'acl a LEFT JOIN '.$objeto_mapa_tabela.' b ON a.id=b.acl_id LEFT JOIN	'.$grupos_mapa_tabela.' c ON a.id=c.acl_id	WHERE	b.valor IS NULL	AND	b.valor_secao IS NULL	AND	c.grupo_id IS NULL AND a.id in ('.$sql_acl_ids.')';
				$acl_ids_orfaos = $this->db->GetCol($comando_sql);
				}
			if ($acl_ids_orfaos) {
				foreach($acl_ids_orfaos AS $acl) $this->excluir_acl($acl);
				}
			}
		$comando_sql = "DELETE FROM $tabela WHERE id='$objeto_id'";
		$rs = $this->db->Execute($comando_sql);
		if (!is_object($rs)) {
			$this->debug_db('editar_objeto');
			$this->db->RollBackTrans();
			return false;
			}
		$this->db->CommitTrans();
		return true;
		}
	$grupos_ids = FALSE;
	if ($tipo_objeto == 'axo'	|| $tipo_objeto == 'aro') {
		$comando_sql = 'SELECT grupo_id FROM '.$objeto_grupo_tabela.' WHERE '.$tipo_objeto.'_id='.$objeto_id;
		$grupos_ids = $this->db->GetCol($comando_sql);
		}
	if ((isset($acl_ids) && ! empty($acl_ids)) || (isset($grupos_ids) && ! empty($grupos_ids))) {
		$this->texto_depanagem("excluir_objeto(): Can't excluir the object as it is being referenced by GROUPs (".@implode($grupos_ids).") or ACLs (".@implode($acl_ids, ",").")");
		$this->db->RollBackTrans();
		return false;
		} 
	else {
		$comando_sql = "DELETE FROM $tabela WHERE id='$objeto_id'";
		$rs = $this->db->Execute($comando_sql);
		if (!is_object($rs)) {
			$this->debug_db('editar_objeto');
			$this->db->RollBackTrans();
			return false;
			}
		$this->db->CommitTrans();
		return true;
		}
	$this->db->RollbackTrans();
	return false;
	}
	
function get_objeto_secao_secao_id($nome = NULL, $valor = NULL, $tipo_objeto = NULL) {
	$this->texto_depanagem("get_objeto_secao_secao_id(): Valor: $valor Name: $nome Objeto Tipo: $tipo_objeto");
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
		case 'aro':
		case 'axo':
		case 'acl':
			$tipo_objeto = strtolower(trim($tipo_objeto));
			$tabela = $this->_bd_tabela_prefixo.$tipo_objeto;
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.$tipo_objeto.'_secoes';
			break;
		default:
			$this->texto_depanagem('get_objeto_secao_secao_id():  Objeto Tipo inválido ('.$tipo_objeto.')');
			return FALSE;
		}
	$nome = trim($nome);
	$valor = trim($valor);
	if (empty($nome) && empty($valor)) {
		$this->texto_depanagem('get_objeto_secao_secao_id(): Both Name ('.$nome.') and Value ('.$valor.') are empty, you must specify at least one.');
		return FALSE;
		}
	$comando_sql = 'SELECT id FROM '.$objeto_tabela_secoes;
	$onde = ' WHERE ';
	if (!empty($valor)) {
		$comando_sql.= $onde.'valor='.$this->db->quote($valor);
		$onde = ' AND ';
		}
	if (!empty($nome)) $comando_sql.= $onde.'nome='.$this->db->quote($nome);
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('get_objeto_secao_secao_id');
		return FALSE;
		}
	$contagem_linha = $rs->RecordCount();
	if ($contagem_linha == 1) {
		$linha = $rs->FetchRow();
		return $linha[0];
		}
	if ($contagem_linha > 1) {
		$this->texto_depanagem("get_objeto_secao_secao_id(): Retornou $contagem_linha linhas, e só pode retornar uma. Please search by valor not nome, or make your nomes unique.");
		return FALSE;
		}
	$this->texto_depanagem('get_objeto_secao_secao_id(): Retornou '.$contagem_linha.' linhas, nenhuma seção compatível encontrada.');
	return FALSE;
	}
	
function adicionar_objeto_secao($nome, $valor = 0, $ordem = 0, $escondido = 0, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'aco_secoes';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'aro_secoes';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'axo_secoes';
			break;
		case 'acl':
			$tipo_objeto = 'acl';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'acl_secoes';
			break;
		}
	$this->texto_depanagem("adicionar_objeto_secao(): Valor: $valor Order: $ordem Name: $nome Objeto Tipo: $tipo_objeto");
	$nome = trim($nome);
	$valor = trim($valor);
	$ordem = trim($ordem);
	$escondido = intval($escondido);
	if ($ordem == NULL || $ordem == '') $ordem = 0;
	if (empty($nome)) {
		$this->texto_depanagem("adicionar_objeto_secao(): nome ($nome) está vazio, e é requerido");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("adicionar_objeto_secao(): Objeto do tipo ($tipo_objeto) está vazio, e é requerido");
		return false;
		}
	$id_inserido = $this->db->GenID($this->_bd_tabela_prefixo.$tipo_objeto.'_secoes_seq', 10);
	$comando_sql = 'insert into '.$objeto_tabela_secoes.' (id,valor,valor_ordem,nome,escondido) values( '.$id_inserido.', '.$this->db->quote($valor).', '.$ordem.', '.$this->db->quote($nome).', '.$escondido.')';
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('adicionar_objeto_secao');
		return false;
		} 
	else {
		$this->texto_depanagem("adicionar_objeto_secao(): Adicionado object_secao como ID: $id_inserido");
		return $id_inserido;
		}
	}

function editar_objeto_secao($objeto_secao_id, $nome, $valor = 0, $ordem = 0, $escondido = 0, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'aco_secoes';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'aco_mapa';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'aro_secoes';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'aro_mapa';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'axo_secoes';
			$objeto_mapa_tabela = $this->_bd_tabela_prefixo.'axo_mapa';
			break;
		case 'acl':
			$tipo_objeto = 'acl';
			$tabela = $this->_bd_tabela_prefixo.'acl';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'acl_secoes';
			break;
		default:
			$this->texto_depanagem('editar_objeto_secao(): Objeto de tipo inválido: '.$tipo_objeto);
			return FALSE;
		}
	$this->texto_depanagem("editar_objeto_secao(): ID: $objeto_secao_id Valor: $valor Ordem: $ordem Nome: $nome Objeto Tipo: $tipo_objeto");
	$nome = trim($nome);
	$valor = trim($valor);
	$ordem = trim($ordem);
	$escondido = intval($escondido);
	if (empty($objeto_secao_id)) {
		$this->texto_depanagem("editar_objeto_secao(): ID da Seção ($objeto_secao_id) está vazia, e é requerida");
		return false;
		}
	if (empty($nome)) {
		$this->texto_depanagem("editar_objeto_secao(): nome ($nome) está vazio, e é requerido");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("editar_objeto_secao(): Objeto Tipo ($tipo_objeto) está vazio, e é requerido");
		return false;
		}
	$this->db->BeginTrans();
	$comando_sql = "SELECT valor FROM $objeto_tabela_secoes WHERE id=$objeto_secao_id";
	$antigo_valor = $this->db->GetOne($comando_sql);
	$comando_sql = "UPDATE $objeto_tabela_secoes SET valor='".$valor."',	valor_ordem='".$ordem."',	nome='".$nome."',	escondido=".$escondido." WHERE id=".$objeto_secao_id;
	$rs = $this->db->Execute($comando_sql);
	if (!is_object($rs)) {
		$this->debug_db('editar_objeto_secao');
		$this->db->RollbackTrans();
		return false;
		} 
	else {
		$this->texto_depanagem("editar_objeto_secao(): aco_secao ID Modificado: $objeto_secao_id");
		if ($antigo_valor != $valor) {
			$this->texto_depanagem("editar_objeto_secao(): Valor mudou, atualize outras tabelas.");
			$comando_sql = "UPDATE ".$tabela." set valor_secao='".$valor."'	where valor_secao = '".$antigo_valor."'";
			$rs = $this->db->Execute($comando_sql);
			if (!is_object($rs)) {
				$this->debug_db('editar_objeto_secao');
				$this->db->RollbackTrans();
				return false;
			} else {
				if (!empty($objeto_mapa_tabela)) {
					$comando_sql = "UPDATE ".$objeto_mapa_tabela." SET valor_secao='".$valor."'	WHERE valor_secao = '".$antigo_valor."'";
					$rs = $this->db->Execute($comando_sql);
					if (!is_object($rs)) {
						$this->debug_db('editar_objeto_secao');
						$this->db->RollbackTrans();
						return false;
						} 
					else {
						$this->texto_depanagem("editar_objeto_secao(): Modificado objeto_mapa valor: $valor");
						$this->db->CommitTrans();
						return true;
						}
					} 
				else {
					$this->db->CommitTrans();
					return true;
					}
				}
			}
		$this->db->CommitTrans();
		return true;
		}
	}
	
function excluir_objeto_secao($objeto_secao_id, $tipo_objeto = NULL, $apagar = FALSE) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'aco_secoes';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'aro_secoes';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'axo_secoes';
			break;
		case 'acl':
			$tipo_objeto = 'acl';
			$objeto_tabela_secoes = $this->_bd_tabela_prefixo.'acl_secoes';
			break;
		}
	$this->texto_depanagem("excluir_objeto_secao(): ID: $objeto_secao_id Objeto Tipo: ".$tipo_objeto.", Apagar todos: ".$apagar);
	if (empty($objeto_secao_id)) {
		$this->texto_depanagem("excluir_objeto_secao(): Seção ID (".$objeto_secao_id.") está vazia, e é requerida");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("excluir_objeto_secao(): Objeto Tipo ($tipo_objeto) está vazio, e é requerido");
		return false;
		}
	$comando_sql = "SELECT valor FROM $objeto_tabela_secoes WHERE id='$objeto_secao_id'";
	$secao_valor = $this->db->GetOne($comando_sql);
	$objeto_ids = $this->get_objeto($secao_valor, 1, $tipo_objeto);
	if ($apagar) {
		if (is_array($objeto_ids)) {
			foreach($objeto_ids AS $id) {
				if ($tipo_objeto === 'acl') $this->excluir_acl($id);
				else $this->excluir_objeto($id, $tipo_objeto, TRUE);
				}
			}
		}
	if ($objeto_ids AND ! $apagar) {
		$this->texto_depanagem("excluir_objeto_secao(): Could not excluir the secao ($secao_valor) as it is not empty.");
		return false;
		} 
	else {
		$comando_sql = "DELETE FROM $objeto_tabela_secoes WHERE id='$objeto_secao_id'";
		$rs = $this->db->Execute($comando_sql);
		if (!is_object($rs)) {
			$this->debug_db('excluir_objeto_secao');
			return false;
			} 
		else {
			$this->texto_depanagem("excluir_objeto_secao(): excluído secao ID: $objeto_secao_id Valor: $secao_valor");
			return true;
			}
		}
	return false;
	}
	
function get_secao_data($secao_valor, $tipo_objeto = NULL) {
	switch (strtolower(trim($tipo_objeto))) {
		case 'aco':
			$tipo_objeto = 'aco';
			$tabela = $this->_bd_tabela_prefixo.'aco_secoes';
			break;
		case 'aro':
			$tipo_objeto = 'aro';
			$tabela = $this->_bd_tabela_prefixo.'aro_secoes';
			break;
		case 'axo':
			$tipo_objeto = 'axo';
			$tabela = $this->_bd_tabela_prefixo.'axo_secoes';
			break;
		default:
			$this->texto_depanagem('get_secao_data():  Objeto Tipo inválido: '.$tipo_objeto);
			return FALSE;
		}
	$this->texto_depanagem("get_secao_data(): Valor da Seção: $secao_valor Objeto Tipo: $tipo_objeto");
	if (empty($secao_valor)) {
		$this->texto_depanagem("get_secao_data(): Valor da Seção ($secao_valor) está vazio, e é requerido");
		return false;
		}
	if (empty($tipo_objeto)) {
		$this->texto_depanagem("get_secao_data(): Objeto Tipo ($tipo_objeto) está vazio, e é requerido");
		return false;
		}
	$comando_sql = "SELECT id, valor, valor_ordem, nome, escondido FROM '. $tabela .' WHERE valor='$secao_valor'";
	$linha = $this->db->GetRow($comando_sql);
	if ($linha) return $linha;
	$this->texto_depanagem("get_secao_data(): Section não existe.");
	return false;
	}
	
function limpar_baseDados() {
	$tabelasParaLimpar = array($this->_bd_tabela_prefixo.'acl', $this->_bd_tabela_prefixo.'aco', $this->_bd_tabela_prefixo.'aco_mapa', $this->_bd_tabela_prefixo.'aco_secoes', $this->_bd_tabela_prefixo.'aro', $this->_bd_tabela_prefixo.'aro_grupos', $this->_bd_tabela_prefixo.'aro_grupos_mapa', $this->_bd_tabela_prefixo.'aro_mapa', $this->_bd_tabela_prefixo.'aro_secoes', $this->_bd_tabela_prefixo.'axo', $this->_bd_tabela_prefixo.'axo_grupos', $this->_bd_tabela_prefixo.'axo_grupos_mapa', $this->_bd_tabela_prefixo.'axo_mapa', $this->_bd_tabela_prefixo.'axo_secoes', $this->_bd_tabela_prefixo.'grupos_aro_mapa', $this->_bd_tabela_prefixo.'grupos_axo_mapa');
	$tabelaNomes = $this->db->MetaTables('tableS');
	$comando_sql = array();
	foreach($tabelaNomes AS $chave =>$valor) {
		if (in_array($valor, $tabelasParaLimpar)) $comando_sql[] = 'TRUNCATE table '.$valor.';';
		}
	foreach($comando_sql AS $chave =>$valor) $resultado = $this->db->Execute($valor);
	return TRUE;
	}
} 
?>