<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\classes\perfil.class.php		

Define a classe de controle de acesso ao gpweb																	
																																												
********************************************************************************************/
class CPerfilAcesso {
	function checarModulo($m=null, $acesso='acesso', $usuario_id=null, $submodulo=null){
		global $Aplic;
	  $superadmin = false;
	  if (!$usuario_id){
	    $usuario_id = $Aplic->usuario_id;
	    $superadmin = $Aplic->usuario_super_admin;
	    }
	  else $superadmin = verificaAdministrador($usuario_id);
	  if($superadmin) return true;
		$sql = new BDConsulta;
		//checar se é negado
		$sql->adTabela('perfil_acesso');
		$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
		$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
		$sql->adCampo('perfil_acesso_'.$acesso);
		$sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\''.($submodulo ? ' OR perfil_acesso_objeto = \''.$submodulo.'\'' : ''));
		$sql->adOnde('perfil_acesso_modulo = \''.$m.'\' OR perfil_acesso_modulo =\'todos\''.($m!='admin' && $m!='sistema' ? ' OR perfil_acesso_modulo =\'nao_admin\'' : ' OR perfil_acesso_modulo =\'admin\''));
		$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
		$sql->adOnde('perfil_acesso_negar = 1');
		$achado=$sql->carregarColuna();
		$sql->Limpar();
		if (in_array(1, $achado)) return false;
		$sql->adTabela('perfil_acesso');
		$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
		$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
		$sql->adCampo('perfil_acesso_'.$acesso);
		$sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\''.($submodulo ? ' OR perfil_acesso_objeto = \''.$submodulo.'\'' : ''));
		$sql->adOnde('perfil_acesso_modulo = \''.$m.'\' OR perfil_acesso_modulo =\'todos\''.($m!='admin' && $m!='sistema' ? ' OR perfil_acesso_modulo =\'nao_admin\'' : ' OR perfil_acesso_modulo =\'admin\''));
		$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
		$sql->adOnde('perfil_acesso_negar = 0');
		$achado=$sql->carregarColuna();
		$sql->Limpar();
		return in_array(1, $achado);
		}
	
	function listaPermissoes($m='', $submodulo=null, $usuario_id=null){
		global $Aplic;
		if ($Aplic->usuario_super_admin) return array(true, true, true, true, true);
		if (!$usuario_id) $usuario_id = $Aplic->usuario_id;
		$sql = new BDConsulta;
		$sql->adTabela('perfil_acesso');
		$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
		$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
		$sql->adCampo('perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
		$sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\''.($submodulo ? ' OR perfil_acesso_objeto = \''.$submodulo.'\'' : ''));
		$sql->adOnde('perfil_acesso_modulo = \''.$m.'\' OR perfil_acesso_modulo =\'todos\''.($m!='admin' && $m!='sistema' ? ' OR perfil_acesso_modulo =\'nao_admin\'' : ' OR perfil_acesso_modulo =\'admin\''));
		$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
		$sql->adOnde('perfil_acesso_negar = 1');
		$negados=$sql->lista();
		$sql->Limpar();
		$negado=array();
		foreach($negados as $linha) {
			if ($linha['acesso']) $negado['acesso']=true;
			if ($linha['editar'])$negado['editar']=true;
			if ($linha['adicionar']) $negado['adicionar']=true;
			if ($linha['excluir']) $negado['excluir']=true;
			if ($linha['aprovar']) $negado['aprovar']=true;
			}
		$sql->adTabela('perfil_acesso');
		$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
		$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
		$sql->adCampo('perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
		if ($submodulo) $sql->adOnde('perfil_acesso_objeto = \''.$submodulo.'\' OR perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\'');
		else $sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\'');
		$sql->adOnde('perfil_acesso_modulo = \''.$m.'\' OR perfil_acesso_modulo =\'todos\''.($m!='admin' && $m!='sistema' ? ' OR perfil_acesso_modulo =\'nao_admin\'' : ' OR perfil_acesso_modulo =\'admin\''));
		$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
		$sql->adOnde('perfil_acesso_negar = 0');
		$achados=$sql->lista();
		$sql->Limpar();
		$saida=array(false, false, false, false, false);
		foreach($achados as $linha) {
			if ($linha['acesso'] && !isset($negado['acesso'])) $saida[0]=true;
			if ($linha['editar'] && !isset($negado['editar'])) $saida[1]=true;
			if ($linha['adicionar'] && !isset($negado['adicionar'])) $saida[2]=true;
			if ($linha['excluir'] && !isset($negado['excluir'])) $saida[3]=true;
			if ($linha['aprovar'] && !isset($negado['aprovar'])) $saida[4]=true;
			}
		return $saida;
		}
	}
?>