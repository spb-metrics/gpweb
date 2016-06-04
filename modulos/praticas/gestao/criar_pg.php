<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$sql = new BDConsulta;

$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
$dept_id = ($Aplic->getEstado('dept_id') !== null && $Aplic->profissional ? $Aplic->getEstado('dept_id') : null);

if (getParam($_REQUEST, 'salvar', 0)){
	//verificar se já existe do ano pedido
	$sql->adTabela('plano_gestao');
	$sql->adCampo('count(pg_id)');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	if (getParam($_REQUEST, 'pg_ano', '')) $sql->adOnde('pg_ano='.getParam($_REQUEST, 'pg_ano', ''));
	$existe=$sql->Resultado();
	$sql->limpar();
	if ($existe) ver2('Já existe um plano de gestão criado no ano escolhido!');
	else {	
		$ano_importar=getParam($_REQUEST, 'pg_ano_importar', 0);
		$sql->adTabela('plano_gestao');
		$sql->adInserir('pg_cia', $cia_id);
		$sql->adInserir('pg_ano', getParam($_REQUEST, 'pg_ano', '0'));
		$sql->adInserir('pg_ultima_alteracao', date('Y-m-d H:i:s'));
		$sql->adInserir('pg_usuario_ultima_alteracao', $Aplic->usuario_id);
		if ($dept_id) $sql->adInserir('pg_dept', $dept_id);
		$sql->exec();
		$pg_id=$bd->Insert_ID('plano_gestao','pg_id');
		$sql->Limpar();
		
		$sql->adTabela('plano_gestao2');
		$sql->adInserir('pg_id', $pg_id);
		$sql->exec();
		$sql->Limpar();
		
		
		if ($ano_importar){
			$sql->adTabela('plano_gestao');
			$sql->adCampo('plano_gestao.*');
			$sql->adOnde('pg_cia='.(int)$cia_id);
			if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
			else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
			$sql->adOnde('pg_ano='.(int)$ano_importar);
			$plano_gestao_antigo=$sql->Linha();
			$pg_id_antigo=$plano_gestao_antigo['pg_id'];
			$sql->limpar();
			if ($pg_id_antigo){
				$sql->adTabela('plano_gestao');
				foreach($plano_gestao_antigo as $chave => $valor) if ($chave!='pg_id' && $chave!='pg_ano' && $chave!='pg_ultima_alteracao' && $chave!='pg_usuario_ultima_alteracao') $sql->adAtualizar($chave, $valor);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->Limpar();
				}
				
			
			
			$sql->adTabela('plano_gestao2');
			$sql->adCampo('plano_gestao2.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$plano_gestao_antigo2=$sql->Linha();
			$sql->limpar();
			if ($plano_gestao_antigo2['pg_id']){
				$sql->adTabela('plano_gestao2');
				foreach($plano_gestao_antigo2 as $chave => $valor) if ($chave!='pg_id') $sql->adAtualizar($chave, $valor);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->Limpar();
				}
	
			
			$sql->adTabela('plano_gestao_tema');
			$sql->adCampo('plano_gestao_tema.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_tema');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('tema_id', $linha['tema_id']);
					$sql->adInserir('tema_ordem', $linha['tema_ordem']);
					$sql->exec();
					$sql->Limpar();
					}
				}	
			
			
			
				
	
			$sql->adTabela('plano_gestao_objetivos_estrategicos');
			$sql->adCampo('plano_gestao_objetivos_estrategicos.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_objetivos_estrategicos');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('pg_objetivo_estrategico_id', $linha['pg_objetivo_estrategico_id']);
					$sql->adInserir('pg_objetivo_estrategico_ordem', $linha['pg_objetivo_estrategico_ordem']);
					$sql->exec();
					$sql->Limpar();
					}
				}	
				
				
			
			$sql->adTabela('plano_gestao_estrategias');
			$sql->adCampo('plano_gestao_estrategias.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_estrategias');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('pg_estrategia_id', $linha['pg_estrategia_id']);
					$sql->adInserir('pg_estrategia_ordem', $linha['pg_estrategia_ordem']);
					$sql->exec();
					$sql->Limpar();
					}
				}	
				
		
			
			$sql->adTabela('plano_gestao_fatores_criticos');
			$sql->adCampo('plano_gestao_fatores_criticos.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_fatores_criticos');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('pg_fator_critico_id', $linha['pg_fator_critico_id']);
					$sql->adInserir('pg_fator_critico_ordem', $linha['pg_fator_critico_ordem']);
					$sql->exec();
					$sql->Limpar();
					}
				}	
				
			
			$sql->adTabela('plano_gestao_perspectivas');
			$sql->adCampo('plano_gestao_perspectivas.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_perspectivas');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('pg_perspectiva_id', $linha['pg_perspectiva_id']);
					$sql->adInserir('pg_perspectiva_ordem', $linha['pg_perspectiva_ordem']);
					$sql->exec();
					$sql->Limpar();
					}
				}	
			
	
			$sql->adTabela('plano_gestao_metas');
			$sql->adCampo('plano_gestao_metas.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_metas');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('pg_meta_id', $linha['pg_meta_id']);
					$sql->adInserir('pg_meta_ordem', $linha['pg_meta_ordem']);
					$sql->exec();
					$sql->Limpar();
					}
				}	
			
			
			
		
			$sql->adTabela('plano_gestao_ameacas');
			$sql->adCampo('plano_gestao_ameacas.*');
			$sql->adOnde('pg_ameaca_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_ameaca_pg_id']){
					$sql->adTabela('plano_gestao_ameacas');
					$sql->adInserir('pg_ameaca_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_ameaca_id' && $chave!='pg_ameaca_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}
			
			
			$sql->adTabela('plano_gestao_arquivos');
			$sql->adCampo('plano_gestao_arquivos.*');
			$sql->adOnde('pg_arquivo_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_arquivo_pg_id']){
					$sql->adTabela('plano_gestao_arquivos');
					$sql->adInserir('pg_arquivo_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_arquivos_id' && $chave!='pg_arquivo_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}		
				
			
			
			
			$sql->adTabela('plano_gestao_diretrizes');
			$sql->adCampo('plano_gestao_diretrizes.*');
			$sql->adOnde('pg_diretriz_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_diretriz_pg_id']){
					$sql->adTabela('plano_gestao_diretrizes');
					$sql->adInserir('pg_diretriz_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_diretriz_id' && $chave!='pg_diretriz_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}
			
			
			$sql->adTabela('plano_gestao_diretrizes_superiores');
			$sql->adCampo('plano_gestao_diretrizes_superiores.*');
			$sql->adOnde('pg_diretriz_superior_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_diretriz_superior_pg_id']){
					$sql->adTabela('plano_gestao_diretrizes_superiores');
					$sql->adInserir('pg_diretriz_superior_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_diretriz_superior_id' && $chave!='pg_diretriz_superior_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}
			
			
	
			$sql->adTabela('plano_gestao_fornecedores');
			$sql->adCampo('plano_gestao_fornecedores.*');
			$sql->adOnde('pg_fornecedor_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_fornecedor_pg_id']){
					$sql->adTabela('plano_gestao_fornecedores');
					$sql->adInserir('pg_fornecedor_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_fornecedor_id' && $chave!='pg_fornecedor_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}
	
			
	
			
			$sql->adTabela('plano_gestao_oportunidade');
			$sql->adCampo('plano_gestao_oportunidade.*');
			$sql->adOnde('pg_oportunidade_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_oportunidade_pg_id']){
					$sql->adTabela('plano_gestao_oportunidade');
					$sql->adInserir('pg_oportunidade_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_oportunidade_id' && $chave!='pg_oportunidade_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}
			
			
			
			$sql->adTabela('plano_gestao_oportunidade_melhorias');
			$sql->adCampo('plano_gestao_oportunidade_melhorias.*');
			$sql->adOnde('pg_oportunidade_melhoria_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_oportunidade_melhoria_pg_id']){
					$sql->adTabela('plano_gestao_oportunidade_melhorias');
					$sql->adInserir('pg_oportunidade_melhoria_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_oportunidade_melhoria_id' && $chave!='pg_oportunidade_melhoria_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}
			
			
			$sql->adTabela('plano_gestao_pessoal');
			$sql->adCampo('plano_gestao_pessoal.*');
			$sql->adOnde('pg_pessoal_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_pessoal_pg_id']){
					$sql->adTabela('plano_gestao_pessoal');
					$sql->adInserir('pg_pessoal_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_pessoal_id' && $chave!='pg_pessoal_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}		
			
			
			$sql->adTabela('plano_gestao_pontosfortes');
			$sql->adCampo('plano_gestao_pontosfortes.*');
			$sql->adOnde('pg_ponto_forte_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_ponto_forte_pg_id']){
					$sql->adTabela('plano_gestao_pontosfortes');
					$sql->adInserir('pg_ponto_forte_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_ponto_forte_id' && $chave!='pg_ponto_forte_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}		
			
			$sql->adTabela('plano_gestao_premiacoes');
			$sql->adCampo('plano_gestao_premiacoes.*');
			$sql->adOnde('pg_premiacao_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_premiacao_pg_id']){
					$sql->adTabela('plano_gestao_premiacoes');
					$sql->adInserir('pg_premiacao_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_premiacao_id' && $chave!='pg_premiacao_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}		
			
			$sql->adTabela('plano_gestao_principios');
			$sql->adCampo('plano_gestao_principios.*');
			$sql->adOnde('pg_principio_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_principio_pg_id']){
					$sql->adTabela('plano_gestao_principios');
					$sql->adInserir('pg_principio_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_principio_id' && $chave!='pg_principio_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->Limpar();
					}
				}		
			}
			
		$Aplic->setEstado('pg_ano', getParam($_REQUEST, 'pg_ano', null));
		$Aplic->setEstado('editarPG', 1);
		

		if ($Aplic->profissional) echo '<script>parent.gpwebApp._popupCallback('.$pg_id.');</script>';
		else echo '<script>alert("Foi criad'.$config['genero_plano_gestao'].' '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'");window.opener.criar_pg('.$pg_id.'); window.close();</script>';
		
		
		
		}
	}

$anos=array();
$anos_antigos=array();
$sql->adTabela('plano_gestao');
$sql->adCampo('DISTINCT pg_ano');
$sql->adOnde('pg_cia='.(int)$cia_id);
if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
$sql->adOrdem('pg_ano DESC');
$listaanos=$sql->Lista();
$sql->limpar();
$anos_antigos[0]='';
foreach ($listaanos as $ano) $anos_antigos[(int)$ano['pg_ano']]=(int)$ano['pg_ano'];

$anos[0]='';
for($i=(int)date('Y')+10; $i > (int)date('Y')-20; $i--)$anos[$i]=$i; 

echo '<form name="env" id="env" method="POST">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="criar_pg" />';
echo '<input type="hidden" name="u" value="gestao" />';
echo '<input type="hidden" name="salvar" value="0" />';

echo estiloTopoCaixa();
echo '<table id="tblPraticas" border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td colspan=2 align=center><h1>Criação de nov'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'</td></tr>'; 

echo '<tr><td colspan=2 align=center><h1>'.nome_cia($cia_id).'</td></tr>'; 
if ($dept_id) echo '<tr><td colspan=2 align=center><h1>'.nome_dept($dept_id).'</td></tr>'; 

echo '<tr><td align=right nowrap="nowrap">'.dica('Ano d'.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']), 'Selecione o ano d'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].' a ser criado.').'Ano:'.dicaF().'</td><td width="100%">'.selecionaVetor($anos, 'pg_ano', 'class="texto"',(int)date('Y')).'</td></tr>';

echo '<tr><td align=right nowrap="nowrap">'.dica('Importar', 'Caso deseje importar os dados de outr'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].', selecione o ano do mesmo.').'Importar de:'.dicaF().'</td><td width="100%">'.selecionaVetor($anos_antigos, 'pg_ano_importar', 'class="texto"',0).'</td></tr>';

echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('criar', 'Criar', 'Criar '.$config['genero_plano_gestao'].' nov'.$config['genero_plano_gestao'].' '.$config['plano_gestao'],'','env.salvar.value=1; env.submit();').'</td><td width="80%">&nbsp;</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para cancelar a criação','','window.opener = window; window.close()').'</td></tr></table></td></tr>';



echo '</table>';
echo estiloFundoCaixa();
echo '</form>';
?>