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

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once (BASE_DIR.'/modulos/praticas/gestao/gestao.class.php');

transforma_vazio_em_nulo($_REQUEST);
$pg_id = intval(getParam($_REQUEST, 'pg_id', 0));
$del = intval(getParam($_REQUEST, 'del', 0));

$nao_eh_novo = getParam($_REQUEST, 'pg_id', null);

if ($del && !$podeExcluir) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($nao_eh_novo && !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$obj = new CGestao();
if ($pg_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

$obj->pg_ultima_alteracao = date('Y-m-d H:i:s');

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&u=gestao&a=gestao_lista');
	}
$Aplic->setMsg('Planejamento Estratégico');
if ($del) {
	$obj->load($pg_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		}
	
	$Aplic->redirecionar('m=praticas&u=gestao&a=gestao_lista');	
		
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
	
	
	
	
	
$importar_id=getParam($_REQUEST, 'importar_id', null);

if ($importar_id){

	$sql = new BDConsulta;

	$sql->adTabela('plano_gestao');
	$sql->adCampo('plano_gestao.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$plano_gestao_antigo=$sql->Linha();
	$sql->limpar();
	
	if ($importar_id){
		$sql->adTabela('plano_gestao');
		foreach($plano_gestao_antigo as $chave => $valor) if (
		$chave!='pg_id' && 
		$chave!='pg_ano' && 
		$chave!='pg_ultima_alteracao' && 
		$chave!='pg_usuario_ultima_alteracao' &&
		$chave!='pg_cia' && 
		$chave!='pg_dept' && 
		$chave!='pg_usuario' && 
		$chave!='pg_acesso' && 
		$chave!='pg_ativo' && 
		$chave!='pg_nome' && 
		$chave!='pg_inicio' && 
		$chave!='pg_fim' && 
		$chave!='pg_descricao' 
		) $sql->adAtualizar($chave, $valor);
		$sql->adOnde('pg_id='.(int)$obj->pg_id);
		$sql->exec();
		$sql->Limpar();
		}
		
	
	
	$sql->adTabela('plano_gestao2');
	$sql->adCampo('plano_gestao2.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$plano_gestao_antigo2=$sql->Linha();
	$sql->limpar();
	if ($plano_gestao_antigo2['pg_id']){
		$sql->adTabela('plano_gestao2');
		foreach($plano_gestao_antigo2 as $chave => $valor) if ($chave!='pg_id') $sql->adAtualizar($chave, $valor);
		$sql->adOnde('pg_id='.(int)$obj->pg_id);
		$sql->exec();
		$sql->Limpar();
		}

	
	$sql->adTabela('plano_gestao_tema');
	$sql->adCampo('plano_gestao_tema.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_tema');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('tema_id', $linha['tema_id']);
			$sql->adInserir('tema_ordem', $linha['tema_ordem']);
			$sql->exec();
			$sql->Limpar();
			}
		}	
	
	$sql->adTabela('plano_gestao_objetivos_estrategicos');
	$sql->adCampo('plano_gestao_objetivos_estrategicos.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_objetivos_estrategicos');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('pg_objetivo_estrategico_id', $linha['pg_objetivo_estrategico_id']);
			$sql->adInserir('pg_objetivo_estrategico_ordem', $linha['pg_objetivo_estrategico_ordem']);
			$sql->exec();
			$sql->Limpar();
			}
		}	
		
	$sql->adTabela('plano_gestao_estrategias');
	$sql->adCampo('plano_gestao_estrategias.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_estrategias');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('pg_estrategia_id', $linha['pg_estrategia_id']);
			$sql->adInserir('pg_estrategia_ordem', $linha['pg_estrategia_ordem']);
			$sql->exec();
			$sql->Limpar();
			}
		}	
	
	$sql->adTabela('plano_gestao_fatores_criticos');
	$sql->adCampo('plano_gestao_fatores_criticos.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_fatores_criticos');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('pg_fator_critico_id', $linha['pg_fator_critico_id']);
			$sql->adInserir('pg_fator_critico_ordem', $linha['pg_fator_critico_ordem']);
			$sql->exec();
			$sql->Limpar();
			}
		}	
		
	$sql->adTabela('plano_gestao_perspectivas');
	$sql->adCampo('plano_gestao_perspectivas.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_perspectivas');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('pg_perspectiva_id', $linha['pg_perspectiva_id']);
			$sql->adInserir('pg_perspectiva_ordem', $linha['pg_perspectiva_ordem']);
			$sql->exec();
			$sql->Limpar();
			}
		}	

	$sql->adTabela('plano_gestao_metas');
	$sql->adCampo('plano_gestao_metas.*');
	$sql->adOnde('pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		if ($linha['pg_id']){
			$sql->adTabela('plano_gestao_metas');
			$sql->adInserir('pg_id', $obj->pg_id);
			$sql->adInserir('pg_meta_id', $linha['pg_meta_id']);
			$sql->adInserir('pg_meta_ordem', $linha['pg_meta_ordem']);
			$sql->exec();
			$sql->Limpar();
			}
		}	
	
	$sql->adTabela('plano_gestao_ameacas');
	$sql->adCampo('plano_gestao_ameacas.*');
	$sql->adOnde('pg_ameaca_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_ameaca_pg_id']){
			$sql->adTabela('plano_gestao_ameacas');
			$sql->adInserir('pg_ameaca_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_ameaca_id' && $chave!='pg_ameaca_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}
	
	$sql->adTabela('plano_gestao_arquivos');
	$sql->adCampo('plano_gestao_arquivos.*');
	$sql->adOnde('pg_arquivo_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_arquivo_pg_id']){
			$sql->adTabela('plano_gestao_arquivos');
			$sql->adInserir('pg_arquivo_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_arquivos_id' && $chave!='pg_arquivo_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}		
	
	$sql->adTabela('plano_gestao_diretrizes');
	$sql->adCampo('plano_gestao_diretrizes.*');
	$sql->adOnde('pg_diretriz_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_diretriz_pg_id']){
			$sql->adTabela('plano_gestao_diretrizes');
			$sql->adInserir('pg_diretriz_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_diretriz_id' && $chave!='pg_diretriz_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}
	
	$sql->adTabela('plano_gestao_diretrizes_superiores');
	$sql->adCampo('plano_gestao_diretrizes_superiores.*');
	$sql->adOnde('pg_diretriz_superior_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_diretriz_superior_pg_id']){
			$sql->adTabela('plano_gestao_diretrizes_superiores');
			$sql->adInserir('pg_diretriz_superior_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_diretriz_superior_id' && $chave!='pg_diretriz_superior_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}
	
	$sql->adTabela('plano_gestao_fornecedores');
	$sql->adCampo('plano_gestao_fornecedores.*');
	$sql->adOnde('pg_fornecedor_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_fornecedor_pg_id']){
			$sql->adTabela('plano_gestao_fornecedores');
			$sql->adInserir('pg_fornecedor_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_fornecedor_id' && $chave!='pg_fornecedor_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}

	$sql->adTabela('plano_gestao_oportunidade');
	$sql->adCampo('plano_gestao_oportunidade.*');
	$sql->adOnde('pg_oportunidade_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_oportunidade_pg_id']){
			$sql->adTabela('plano_gestao_oportunidade');
			$sql->adInserir('pg_oportunidade_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_oportunidade_id' && $chave!='pg_oportunidade_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}
	
	$sql->adTabela('plano_gestao_oportunidade_melhorias');
	$sql->adCampo('plano_gestao_oportunidade_melhorias.*');
	$sql->adOnde('pg_oportunidade_melhoria_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_oportunidade_melhoria_pg_id']){
			$sql->adTabela('plano_gestao_oportunidade_melhorias');
			$sql->adInserir('pg_oportunidade_melhoria_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_oportunidade_melhoria_id' && $chave!='pg_oportunidade_melhoria_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}
	
	$sql->adTabela('plano_gestao_pessoal');
	$sql->adCampo('plano_gestao_pessoal.*');
	$sql->adOnde('pg_pessoal_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_pessoal_pg_id']){
			$sql->adTabela('plano_gestao_pessoal');
			$sql->adInserir('pg_pessoal_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_pessoal_id' && $chave!='pg_pessoal_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}		
	
	$sql->adTabela('plano_gestao_pontosfortes');
	$sql->adCampo('plano_gestao_pontosfortes.*');
	$sql->adOnde('pg_ponto_forte_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_ponto_forte_pg_id']){
			$sql->adTabela('plano_gestao_pontosfortes');
			$sql->adInserir('pg_ponto_forte_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_ponto_forte_id' && $chave!='pg_ponto_forte_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}		
	
	$sql->adTabela('plano_gestao_premiacoes');
	$sql->adCampo('plano_gestao_premiacoes.*');
	$sql->adOnde('pg_premiacao_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_premiacao_pg_id']){
			$sql->adTabela('plano_gestao_premiacoes');
			$sql->adInserir('pg_premiacao_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_premiacao_id' && $chave!='pg_premiacao_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}		
	
	$sql->adTabela('plano_gestao_principios');
	$sql->adCampo('plano_gestao_principios.*');
	$sql->adOnde('pg_principio_pg_id='.(int)$importar_id);
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $campos){
		if ($campos['pg_principio_pg_id']){
			$sql->adTabela('plano_gestao_principios');
			$sql->adInserir('pg_principio_pg_id', $obj->pg_id);
			foreach($campos as $chave => $valor) if ($chave!='pg_principio_id' && $chave!='pg_principio_pg_id') $sql->adInserir($chave, $valor);
			$sql->exec();
			$sql->Limpar();
			}
		}		
	}	
	
	
$Aplic->redirecionar('m=praticas&u=gestao&a=menu&pg_id='.$obj->pg_id);	

?>