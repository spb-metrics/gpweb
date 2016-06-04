<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (!$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');

$botoesTitulo = new CBlocoTitulo('Administra��o do Sistema', 'administracao.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
if (!$dialogo) $Aplic->salvarPosicao();

echo estiloTopoCaixa();
echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="5">';
echo '<tr><td width="42" valign="top">'.imagem('configuracao.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Administra��o</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Configura��o do Sistema", "Abre uma janela onde se pode editar os par�metros de funcionamento do Sistema.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=configuracao_sistema\');">Configura��o do sistema</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Prefer�ncias Padr�o de '.ucfirst($config['usuario']), "Abre uma janela onde se pode editar as prefer�ncias padr�o dos novos ".$config['usuario']." criados.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=editarpref\');">Prefer�ncias padr�o de '.$config['usuario'].'</a>'.dicaF().'</td></tr>';
//if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Chaves de Campos", "Abre uma janela onde se pode criar e editar valores de chaves para campos.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=sischaves&a=chaves\');">Chaves de campos</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Valores de Campos do Sistema", "Abre uma janela onde se pode editar os valores de diversos campos do sistema.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=sischaves\');">Valores de campos do sistema</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Campos Customizados", "Abre uma janela onde se pode criar e editar campos customizados para diversos m�dulos.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=campo_customizado\');">Campos customizados</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Perfis de Acesso', 'Abre uma janela onde se pode inserir e editar as defini��es dos perfis de acesso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=perfis\');">Perfis de acesso</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Permiss�es', 'Abre uma janela onde se pode verificar o n�vel de acesso � cada m�dulo do sistema pel'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=acls_ver\');">Permiss�es</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Campos dos Formul�rios", "Abre uma janela onde se pode selecionar quais campos ser�o apresentados em diversos formul�rios do sistema.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=campo_formulario\');">Campos dos formul�rios</a>'.dicaF().'</td></tr>';
echo '<tr><td align="left">'.dica('Expediente', 'Abre uma janela onde se pode configurar o expediente d'.$config['genero_organizacao'].' '.$config['organizacao'].', '.$config['departamento'].' ou '.$config['usuario'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=jornada\');">Expediente</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Prioriza��o', 'Abre interface para cria��o de perguntas para prioriza��o de objeto do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=priorizacao_pro\');">Prioriza��o</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Parecer', 'Abre interface para cria��o de textos de pareceres quando da assinatura de objeto do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tr&a=tr_atesta\');">Parecer</a>'.dicaF().'</td></tr>';	
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Importar Contatos', 'Abre uma janela onde se pode verificar o n�vel de acesso � cada m�dulo do sistema pel'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=contatos_ldap\');">Importar contatos</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Importar do Importar MS Project ou WBS Chart Pro', 'Abre uma janela onde se pode importar projeto do MS Project ou do WBS Chart Pro.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=index&u=importar\');">Importar do MS Project ou WBS Chart Pro</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Importar do DotProject ou PECM', 'Importar a base de dados do DotProject ou PECM.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=importar\');">Importar do DotProject ou PECM</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Backup dos dados', 'C�pia em SQL dos dados do '.$config['gpweb'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=importar&a=exportar_sql\');">Backup dos dados</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Instala��o do '.$config['gpweb'].'', 'Abre uma janela onde se pode verificar as configura��es da instala��o do '.$config['gpweb'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=gpwebinfo&dialogo=1\');">Instala��o do '.$config['gpweb'].'</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Informa��es Sobre o Servidor PHP', 'Abre uma janela onde se pode verificar as configura��es do servidor PHP e quais bibliotecas est�o instaladas no mesmo.').'<a href="?m=sistema&a=phpinfo&sem_cabecalho=1;" target="_blank">Sobre o PHP</a>'.dicaF().'</td></tr>';
echo '</table></td></tr>';

echo '<tr><td width="42" valign="top">'.imagem('membro.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">'.ucfirst($config['usuario']).'</td></tr>';
echo '<tr><td>'.dica(ucfirst($config['usuario']), 'Visualizar e configurar '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin\');">Administra��o d'.$config['genero_usuario'].'s '.$config['usuarios'].'</a>'.dicaF().'</td></tr>';

if($Aplic->usuario_super_admin && $config['registrar_mudancas']) echo '<tr><td align="left">'.dica('Hist�rico de Atividades', 'Registro de todas as a��es executadas pel'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=historico&a=index\');">Hist�rico de atividades</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('�ltimos Acessos', 'Relat�rio dos �ltimos acessos por '.$config['usuario'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=historico&a=ultimo_acesso_pro\');">�ltimos acessos</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Conhecimentos, Habilidades e Atitudes', 'Abre interface para cria��o de t�picos para gest�o de conhecimentos, habilidades e atitudes.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=cha_pro\');">Conhecimentos, habilidades e atitudes</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Clonar Usu�rios', 'Abre interface onde c�pia de '.$config['usuarios'].' podem ser inserid'.$config['genero_usuario'].'s em outr'.$config['genero_organizacao'].' '.$config['organizacao'].' .').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=clonar_usuarios_pro\');">Clonar '.$config['usuarios'].'</a>'.dicaF().'</td></tr>';
echo '</table></td></tr>';


if($Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('projeto.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">'.ucfirst($config['projeto']).'</td></tr>';
	echo '<tr><td align="left">'.dica('Artefatos', 'Cria��o e edi��o de modelos artefatos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=projeto&a=modelos_artefatos\');">Modelos de artefatos</a>'.dicaF().'</td></tr>';
	if ($Aplic->profissional) {
		//FALTA IMPLEMENTAR
		//echo '<tr><td align="left">'.dica('Atores', 'Abre interface para cria��o de atores para o sistema de workflow de projetos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u=ator&a=ator_lista\');">Atores</a>'.dicaF().'</td></tr>';
		}
	echo '</table></td></tr>';
	}

if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('gestao.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Gest�o</td></tr>';
	//echo '<tr><td align="left">'.dica('Prioriza��o', 'Abre interface para cria��o de perguntas para prioriza��o dos objetos da gest�o.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=priorizacao_pro\');">Prioriza��o da gest�o</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Replicar Dados dos Indicadores para '.date('Y'), 'Ao se selecionar esta op��o ser� rodado um script que replicar� os dados dos indicadores ativos para '.date('Y').'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=migrar_indicador_pro\');">Replicar dados dos indicadores para '.date('Y').'</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Replicar Dados d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']).' para '.date('Y'), 'Ao se selecionar esta op��o ser� rodado um script que replicar� todos os dados d'.$config['genero_pratica'].'s '.$config['praticas'].' ativ'.$config['genero_pratica'].'s para '.date('Y').'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=migrar_pratica_pro\');">Replicar dados d'.$config['genero_pratica'].'s '.$config['praticas'].' para '.date('Y').'</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Replicar Autoavalia��o (BSC) para '.date('Y'), 'Ao se selecionar esta op��o ser� poss�vel replicar uma autoavalia��o para o ano de '.date('Y').'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=migrar_bsc_pro\');">Replicar autoavalia��o (BSC) para '.date('Y').'</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Matriz de Risco', 'Definir o padrao da matriz de risco.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=matriz_risco_pro\');">Matriz de risco</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica(ucfirst($config['canvas']), 'Configura��es para '.$config['genero_canvas'].' '.$config['canvas'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=canvas_pro\');">'.ucfirst($config['canvas']).'</a>'.dicaF().'</td></tr>';
	if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Clonar Gest�o Estrat�gica', 'Abre interface onde c�pia da gest�o estrat�gica podem ser inserida em outr'.$config['genero_organizacao'].' '.$config['organizacao'].' .').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=clonar_gestao_pro\');">Clonar gest�o estrat�gica</a>'.dicaF().'</td></tr>';
	if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Clonar Eventos', 'Abre interface onde c�pia dos eventos podem ser inseridos em outr'.$config['genero_organizacao'].' '.$config['organizacao'].' .').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=clonar_eventos_pro\');">Clonar eventos</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Importar '.$config['praticas'].' ou Indicadores do mesmo Servidor', 'Importar '.$config['praticas'].' ou indicadores de um'.$config['genero_organizacao'].' '.$config['organizacao'].' para outra, ambas hospedadas no mesmo servidor.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=importar&a=indicadores\');">Importar '.$config['praticas'].' ou indicadores do mesmo servidor</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Importar e Exportar '.$config['praticas'].', Indicadores e Projetos de servidores independentes', 'Importar e exportar '.$config['praticas'].', indicadores e projetos de um'.$config['genero_organizacao'].' '.$config['organizacao'].' para outra em servidores independentes.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=importar&a=importar_praticas\');">Importar e exportar '.$config['praticas'].', indicadores e projetos de servidores independentes</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}


if($Aplic->modulo_ativo('social')){
	echo '<tr><td width="42" valign="top">'.imagem('../../../modulos/social/imagens/social.gif','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Programas Sociais</td></tr>';
	if($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'exporta_familia')) echo '<tr><td align="left">'.dica('Gerar Arquivo com os Benefici�rios', 'Gerar, a partir de dispositivo off-line, arquivo de exporta��o dos benefici�rios, para que possa ser instalado no Servidor.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=exportar\');">Gerar arquivo com os benefici�rios</a>'.dicaF().'</td></tr>';
	if($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'importa_familia')) echo '<tr><td align="left">'.dica('Importar Arquivo com os Benefici�rios', 'Abre uma janela onde se pode importar o arquivo de benefici�rios gerados a partir de dispositivos off-line para o servidor central.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=importar\');">Importar arquivo com os benefici�rios</a>'.dicaF().'</td></tr>';
	if($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'gera_notebook')) echo '<tr><td align="left">'.dica('Gerar Arquivo de Prepara��o de Dispositivo Off-Line', 'Abre uma janela onde se pode gerar o arquivo de prapara��o dos dispositivos que ir�o trabalhar off-line no cadastramento de benefici�rios, com os programas, as a��es, comit�s e comunidades.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=exportar_notebook\');">Gerar arquivo de prepara��o de dispositivo off-line</a>'.dicaF().'</td></tr>';
	if($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'importa_notebook')) echo '<tr><td align="left">'.dica('Instalar em Dispositivo Off-Line o Arquivo de Prepara��o', 'Abre uma janela onde se pode importar o arquivo de prapara��o de dispositivo off-line, criado no Servidor, para que este possa trabalhar no cadastramento de benefici�rios, contendo os programas, as a��es, comit�s e comunidades.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=importar_notebook\');">Instalar em dispositivo off-line o arquivo de prepara��o</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	} 


echo '<tr><td width="42" valign="top">'.imagem('email.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">'.ucfirst($config['mensagens']).'</td></tr>';
echo '<tr><td align="left">'.dica('Grupos de Destinat�rios', 'Configurar os grupos de destinat�rios default do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=administracao\');">Grupos de destinat�rios</a>'.dicaF().'</td></tr>';
echo '<tr><td align="left">'.dica('Grupos vs '.ucfirst($config['usuarios']), 'Matriz correlacionando '.$config['genero_usuario'].'s '.$config['usuarios'].' nos diversos grupos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=exibir_usuarios_grupos\');">Grupos vs '.$config['usuarios'].'</a>'.dicaF().'</td></tr>';
echo '<tr><td align="left">'.dica(ucfirst($config['usuario']).' nos Grupos', 'Configurar as permiss�es de visualiza��o de grupos para cada usu�rio, assim como cadastrar o mesmo nos grupos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=usuario_grupos\');">'.ucfirst($config['usuario']).' nos grupos</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Edi��o de Modelos de Documentos', 'Cria��o e edi��o de modelos de documentos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=modelos_documentos\');">Edi��o de modelos de documentos</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Modelos de Documentos vs '.ucfirst($config['organizacao']), 'Defini��o de quais modelos de documentos cada '.$config['organizacao'].' tem acesso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=modelos_cias\');">Modelos de documentos vs '.$config['organizacao'].'</a>'.dicaF().'</td></tr>';

echo '</table></td></tr>';


echo '<tr><td width="42" valign="top">'.imagem('calendario.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Agenda</td></tr>';
echo '<tr><td align="left">'.dica("Agenda Coletiva", "Abre uma janela onde se pode configurar agendas coletivas.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=calendario&a=calendario_lista\');">Agenda coletiva</a>'.dicaF().'</td></tr>';
echo '</table></td></tr>';

if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('alertas.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Sistema de Alertas</td></tr>';
	echo '<tr><td align="left">'.dica('Configura��es do Sistema de Alertas', 'Abre uma janela onde se pode configurar as op��es de alertas autom�ticos do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=alarme_pro\');">Configura��es do sistema de alertas</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}

if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('qr.gif','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">C�digos nos documentos</td></tr>';
	echo '<tr><td align="left">'.dica('Configura��es dos C�digos', 'Abre uma janela onde se pode configurar as op��es de c�digos de barra, QR, dentre outros, na impress�o de documentos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=codigos_pro\');">Configura��es dos c�digos</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}



if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('bsc.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Balanced score card</td></tr>';
	echo '<tr><td align="left">'.dica('Pauta de Pontua��o', 'Criar ou editar as pautas de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=pauta\');">Pauta de pontua��o</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Crit�rios', 'Criar ou editar crit�rio de pauta de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=criterio\');">Crit�rios</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Itens', 'Criar ou editar itens de pauta de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=item\');">Itens</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Al�neas', 'Criar ou editar al�neas de item de pauta de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=marcador\');">Al�neas</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Requisito de Adequa��o', 'Criar ou editar os requisitos de adequa��o de al�neas de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=verbo\');">Requisito de adequa��o</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Grau de Maturidade', 'Criar ou editar a tabela de pontua��o com grau de maturidade equivalente.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=maturidade\');">Grau de maturidade</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Regra de Pontua��o', 'Criar ou editar a tabela de regra de pontua��o da pauta.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=regra\');">Regra de pontua��o</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Descri��o dos Requisitos', 'Criar ou editar a descri��o dos requisitos da pauta de pontua��o.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=campo\');">Descri��o dos requisitos</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}


if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('dinheiro.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Elementos de despesa</td></tr>';
	echo '<tr><td align="left">'.dica('Configurar Elementos de Despesa', 'Criar ou editar os elementos de despesa.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=nd&a=nd_lista\');">Configurar os elementos de despesa</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}


if($Aplic->usuario_super_admin) {
	echo '<tr><td width="42" valign="top">'.imagem('modulos.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">M�dulos</td></tr>';
	echo '<tr><td align="left">'.dica("Ver M�dulos", "Abre uma janela onde se pode selecionar quais m�dulos dever�o estar habilitados e visualizados.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=vermods\');">Ver m�dulos</a>'.dicaF().'</td></tr>';
  //echo '<tr><td align="left">'.dica("Instalar SQL Extra", "Abre uma janela onde se pode selecionar codigo SQL extra a ser instalado no '.$config['gpweb'].'.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=instalar_sql\');">Instalar SQL extra</a>'.dicaF().'</td></tr>';
  }
echo '</table></td></tr>';

echo '</table>';
echo estiloFundoCaixa();
?>

<script language="javascript">
	
		
</script>
