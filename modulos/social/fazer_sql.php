<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\fazer_sql.php		

Rotina chamada quando se exclui uma a��o, pr�tica ou indicador																																							
																																												
********************************************************************************************/
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');

$excluir=getParam($_REQUEST, 'excluir', 0);
$social_id=getParam($_REQUEST, 'social_id', 0);
$social_acao_id=getParam($_REQUEST, 'social_acao_id', 0);
$social_familia_id=getParam($_REQUEST, 'social_familia_id', 0);
$social_comunidade_id=getParam($_REQUEST, 'social_comunidade_id', 0);
$social_comite_id=getParam($_REQUEST, 'social_comite_id', 0);
$modulo=getParam($_REQUEST, 'modulo', '');
$sql = new BDConsulta;


if ($modulo=='social' && $excluir && $social_id){
	
	$sql->setExcluir('social');
	$sql->adOnde('social_id='.$social_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->setExcluir('social_depts');
	$sql->adOnde('social_id='.$social_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_depts!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->setExcluir('social_log');
	$sql->adOnde('social_log_social='.$social_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();

	$Aplic->setMsg('Programa social exclu�do', UI_MSG_OK);
	$Aplic->redirecionar('m=social&a=index');
	exit();
	}
	
if ($modulo=='acao' && $excluir && $social_acao_id){
	
	$sql->adTabela('social_acao_arquivo');
	$sql->adCampo('social_acao_arquivo_endereco');
	$sql->adOnde('social_acao_arquivo_acao='.$social_acao_id);
	$arquivos=$sql->Lista();
	$sql->limpar();
	foreach($arquivos as $chave => $arquivo){
		@unlink($base_dir.'/arquivos/acoes/'.$arquivo['social_acao_arquivo_endereco']);
		}

	$sql->setExcluir('social_acao_arquivo');
	$sql->adOnde('social_acao_arquivo_acao='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_arquivo!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->setExcluir('social_acao');
	$sql->adOnde('social_acao_id='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();

		$sql->setExcluir('social_acao_usuarios');
	$sql->adOnde('social_acao_id='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_acao_problema');
	$sql->adOnde('social_acao_problema_acao_id='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_acao_negacao');
	$sql->adOnde('social_acao_negacao_acao_id='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();


	$sql->setExcluir('social_acao_log');
	$sql->adOnde('social_acao_log_acao='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();


	$sql->setExcluir('social_acao_lista');
	$sql->adOnde('social_acao_lista_acao_id='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();


	$sql->setExcluir('social_acao_depts');
	$sql->adOnde('social_acao_id='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_acao_conceder');
	$sql->adOnde('social_acao_conceder_acao='.$social_acao_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_id!'.$bd->stderr(true));
	$sql->limpar();
	
	$Aplic->setMsg('a��o social exclu�da', UI_MSG_OK);
	$Aplic->redirecionar('m=social&a=acao_lista');
	exit();
	}	
	


	
if ($modulo=='comite' && $excluir && $social_comite_id){
	
	$sql->adTabela('social_comite_arquivo');
	$sql->adCampo('social_comite_arquivo_nome_real');
	$sql->adOnde('social_comite_arquivo_comite='.$social_comite_id);
	$arquivos=$sql->Lista();
	$sql->limpar();
	foreach($arquivos as $chave => $arquivo){
		@unlink($base_dir.'/arquivos/comites/'.$arquivo['social_comite_arquivo_nome_real']);
		}

	$sql->setExcluir('social_comite_arquivo');
	$sql->adOnde('social_comite_arquivo_comite='.$social_comite_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comite_arquivo!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->setExcluir('social_comite');
	$sql->adOnde('social_comite_id='.$social_comite_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comite_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_comite_membros');
	$sql->adOnde('social_comite_id='.$social_comite_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comite_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_comite_problema');
	$sql->adOnde('social_comite_problema_comite='.$social_comite_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comite_id!'.$bd->stderr(true));
	$sql->limpar();

	
	$sql->setExcluir('social_comite_log');
	$sql->adOnde('social_comite_log_comite='.$social_comite_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comite_id!'.$bd->stderr(true));
	$sql->limpar();


	$sql->setExcluir('social_comite_lista');
	$sql->adOnde('social_comite_lista_comite='.$social_comite_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comite_id!'.$bd->stderr(true));
	$sql->limpar();

	$Aplic->setMsg('Comit� exclu�do', UI_MSG_OK);
	$Aplic->redirecionar('m=social&a=comite_lista');
	exit();
	}		







if ($modulo=='comunidade' && $excluir && $social_comunidade_id){
	
	$sql->setExcluir('social_comunidade');
	$sql->adOnde('social_comunidade_id='.$social_comunidade_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comunidade_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_comunidade_usuarios');
	$sql->adOnde('social_comunidade_id='.$social_comunidade_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comunidade_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_comunidade_log');
	$sql->adOnde('social_comunidade_log_comunidade='.$social_comunidade_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comunidade_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_comunidade_depts');
	$sql->adOnde('social_comunidade_id='.$social_comunidade_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_comunidade_id!'.$bd->stderr(true));
	$sql->limpar();
	
	$Aplic->setMsg('Comunidade exclu�da', UI_MSG_OK);
	$Aplic->redirecionar('m=social&a=comunidade_lista');
	exit();
	}	







if ($modulo=='familia' && $excluir && $social_familia_id){
	
	$sql->adTabela('social_familia_arquivo');
	$sql->adCampo('social_familia_arquivo_nome_real');
	$sql->adOnde('social_familia_arquivo_familia='.$social_familia_id);
	$arquivos=$sql->Lista();
	$sql->limpar();
	foreach($arquivos as $chave => $arquivo){
		@unlink($base_dir.'/arquivos/familias/'.$arquivo['social_familia_arquivo_nome_real']);
		}

	$sql->setExcluir('social_familia_arquivo');
	$sql->adOnde('social_familia_arquivo_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_arquivo!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->setExcluir('social_familia');
	$sql->adOnde('social_familia_id='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_familia_problema');
	$sql->adOnde('social_familia_problema_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_familia_acao_negada');
	$sql->adOnde('social_familia_acao_negada_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_familia_log');
	$sql->adOnde('social_familia_log_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_familia_lista');
	$sql->adOnde('social_familia_lista_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_id!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_familia_producao');
	$sql->adOnde('social_familia_producao_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_producao!'.$bd->stderr(true));
	$sql->limpar();

	$sql->setExcluir('social_familia_opcao');
	$sql->adOnde('social_familia_opcao_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_opcao!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->setExcluir('social_familia_irrigacao');
	$sql->adOnde('social_familia_irrigacao_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_irrigacao!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->setExcluir('social_familia_acao');
	$sql->adOnde('social_familia_acao_familia='.$social_familia_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_familia_acao!'.$bd->stderr(true));
	$sql->limpar();	
	
	$Aplic->setMsg('fam�lia exclu�da', UI_MSG_OK);
	$Aplic->redirecionar('m=social&a=familia_lista');
	exit();
	}		

?>