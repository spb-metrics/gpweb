<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$sql = new BDConsulta;
$sql->adTabela('usuarios');
$sql->adCampo('usuario_login2, usuario_senha2');
$sql->adOnde('usuario_id ='.$Aplic->usuario_id);
$outra_conta=$sql->Linha();
$sql->limpar();

$sql->adTabela('usuarios');
$sql->adCampo('usuario_id');
$sql->adOnde('usuario_login =\''.$outra_conta['usuario_login2'].'\'');
$sql->adOnde('usuario_senha =\''.$outra_conta['usuario_senha2'].'\'');
$achado=$sql->Resultado();
$sql->limpar();

if ($achado){
 		$Aplic->mudar_conta($achado);
 		$Aplic->redirecionar('m=email&a=lista_msg');
		}
$Aplic->setMsg('A segunda conta cadastrada n�o corresponde a nenhuma cadastrada no sistema!', UI_MSG_ERRO); 			
$Aplic->redirecionar('m=email&a=lista_msg');
?>