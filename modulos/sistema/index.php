<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');

$botoesTitulo = new CBlocoTitulo('Administração do Sistema', 'administracao.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
if (!$dialogo) $Aplic->salvarPosicao();

echo estiloTopoCaixa();
echo '<table class="std" width="100%" border=0 cellpadding=0 cellspacing="5">';
echo '<tr><td width="42" valign="top">'.imagem('configuracao.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Administração</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Configuração do Sistema", "Abre uma janela onde se pode editar os parâmetros de funcionamento do Sistema.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=configuracao_sistema\');">Configuração do sistema</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Preferências Padrão de '.ucfirst($config['usuario']), "Abre uma janela onde se pode editar as preferências padrão dos novos ".$config['usuario']." criados.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=editarpref\');">Preferências padrão de '.$config['usuario'].'</a>'.dicaF().'</td></tr>';
//if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Chaves de Campos", "Abre uma janela onde se pode criar e editar valores de chaves para campos.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=sischaves&a=chaves\');">Chaves de campos</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Valores de Campos do Sistema", "Abre uma janela onde se pode editar os valores de diversos campos do sistema.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=sischaves\');">Valores de campos do sistema</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Campos Customizados", "Abre uma janela onde se pode criar e editar campos customizados para diversos módulos.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=campo_customizado\');">Campos customizados</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Perfis de Acesso', 'Abre uma janela onde se pode inserir e editar as definições dos perfis de acesso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=perfis\');">Perfis de acesso</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Permissões', 'Abre uma janela onde se pode verificar o nível de acesso à cada módulo do sistema pel'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=acls_ver\');">Permissões</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica("Campos dos Formulários", "Abre uma janela onde se pode selecionar quais campos serão apresentados em diversos formulários do sistema.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=campo_formulario\');">Campos dos formulários</a>'.dicaF().'</td></tr>';
echo '<tr><td align="left">'.dica('Expediente', 'Abre uma janela onde se pode configurar o expediente d'.$config['genero_organizacao'].' '.$config['organizacao'].', '.$config['departamento'].' ou '.$config['usuario'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=jornada\');">Expediente</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Priorização', 'Abre interface para criação de perguntas para priorização de objeto do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=priorizacao_pro\');">Priorização</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Parecer', 'Abre interface para criação de textos de pareceres quando da assinatura de objeto do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tr&a=tr_atesta\');">Parecer</a>'.dicaF().'</td></tr>';	
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Importar Contatos', 'Abre uma janela onde se pode verificar o nível de acesso à cada módulo do sistema pel'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=contatos_ldap\');">Importar contatos</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Importar do Importar MS Project ou WBS Chart Pro', 'Abre uma janela onde se pode importar projeto do MS Project ou do WBS Chart Pro.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=index&u=importar\');">Importar do MS Project ou WBS Chart Pro</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Importar do DotProject ou PECM', 'Importar a base de dados do DotProject ou PECM.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=importar\');">Importar do DotProject ou PECM</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Backup dos dados', 'Cópia em SQL dos dados do '.$config['gpweb'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=importar&a=exportar_sql\');">Backup dos dados</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Instalação do '.$config['gpweb'].'', 'Abre uma janela onde se pode verificar as configurações da instalação do '.$config['gpweb'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=gpwebinfo&dialogo=1\');">Instalação do '.$config['gpweb'].'</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Informações Sobre o Servidor PHP', 'Abre uma janela onde se pode verificar as configurações do servidor PHP e quais bibliotecas estão instaladas no mesmo.').'<a href="?m=sistema&a=phpinfo&sem_cabecalho=1;" target="_blank">Sobre o PHP</a>'.dicaF().'</td></tr>';
echo '</table></td></tr>';

echo '<tr><td width="42" valign="top">'.imagem('membro.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">'.ucfirst($config['usuario']).'</td></tr>';
echo '<tr><td>'.dica(ucfirst($config['usuario']), 'Visualizar e configurar '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin\');">Administração d'.$config['genero_usuario'].'s '.$config['usuarios'].'</a>'.dicaF().'</td></tr>';

if($Aplic->usuario_super_admin && $config['registrar_mudancas']) echo '<tr><td align="left">'.dica('Histórico de Atividades', 'Registro de todas as ações executadas pel'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=historico&a=index\');">Histórico de atividades</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Últimos Acessos', 'Relatório dos últimos acessos por '.$config['usuario'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=historico&a=ultimo_acesso_pro\');">Últimos acessos</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Conhecimentos, Habilidades e Atitudes', 'Abre interface para criação de tópicos para gestão de conhecimentos, habilidades e atitudes.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=cha_pro\');">Conhecimentos, habilidades e atitudes</a>'.dicaF().'</td></tr>';
if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Clonar Usuários', 'Abre interface onde cópia de '.$config['usuarios'].' podem ser inserid'.$config['genero_usuario'].'s em outr'.$config['genero_organizacao'].' '.$config['organizacao'].' .').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=clonar_usuarios_pro\');">Clonar '.$config['usuarios'].'</a>'.dicaF().'</td></tr>';
echo '</table></td></tr>';


if($Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('projeto.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">'.ucfirst($config['projeto']).'</td></tr>';
	echo '<tr><td align="left">'.dica('Artefatos', 'Criação e edição de modelos artefatos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=projeto&a=modelos_artefatos\');">Modelos de artefatos</a>'.dicaF().'</td></tr>';
	if ($Aplic->profissional) {
		//FALTA IMPLEMENTAR
		//echo '<tr><td align="left">'.dica('Atores', 'Abre interface para criação de atores para o sistema de workflow de projetos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u=ator&a=ator_lista\');">Atores</a>'.dicaF().'</td></tr>';
		}
	echo '</table></td></tr>';
	}

if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('gestao.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Gestão</td></tr>';
	//echo '<tr><td align="left">'.dica('Priorização', 'Abre interface para criação de perguntas para priorização dos objetos da gestão.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=priorizacao_pro\');">Priorização da gestão</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Replicar Dados dos Indicadores para '.date('Y'), 'Ao se selecionar esta opção será rodado um script que replicará os dados dos indicadores ativos para '.date('Y').'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=migrar_indicador_pro\');">Replicar dados dos indicadores para '.date('Y').'</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Replicar Dados d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']).' para '.date('Y'), 'Ao se selecionar esta opção será rodado um script que replicará todos os dados d'.$config['genero_pratica'].'s '.$config['praticas'].' ativ'.$config['genero_pratica'].'s para '.date('Y').'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=migrar_pratica_pro\');">Replicar dados d'.$config['genero_pratica'].'s '.$config['praticas'].' para '.date('Y').'</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Replicar Autoavaliação (BSC) para '.date('Y'), 'Ao se selecionar esta opção será possível replicar uma autoavaliação para o ano de '.date('Y').'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=migrar_bsc_pro\');">Replicar autoavaliação (BSC) para '.date('Y').'</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Matriz de Risco', 'Definir o padrao da matriz de risco.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=matriz_risco_pro\');">Matriz de risco</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica(ucfirst($config['canvas']), 'Configurações para '.$config['genero_canvas'].' '.$config['canvas'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=canvas_pro\');">'.ucfirst($config['canvas']).'</a>'.dicaF().'</td></tr>';
	if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Clonar Gestão Estratégica', 'Abre interface onde cópia da gestão estratégica podem ser inserida em outr'.$config['genero_organizacao'].' '.$config['organizacao'].' .').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=clonar_gestao_pro\');">Clonar gestão estratégica</a>'.dicaF().'</td></tr>';
	if($Aplic->profissional && $Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Clonar Eventos', 'Abre interface onde cópia dos eventos podem ser inseridos em outr'.$config['genero_organizacao'].' '.$config['organizacao'].' .').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=clonar_eventos_pro\');">Clonar eventos</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Importar '.$config['praticas'].' ou Indicadores do mesmo Servidor', 'Importar '.$config['praticas'].' ou indicadores de um'.$config['genero_organizacao'].' '.$config['organizacao'].' para outra, ambas hospedadas no mesmo servidor.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=importar&a=indicadores\');">Importar '.$config['praticas'].' ou indicadores do mesmo servidor</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Importar e Exportar '.$config['praticas'].', Indicadores e Projetos de servidores independentes', 'Importar e exportar '.$config['praticas'].', indicadores e projetos de um'.$config['genero_organizacao'].' '.$config['organizacao'].' para outra em servidores independentes.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=importar&a=importar_praticas\');">Importar e exportar '.$config['praticas'].', indicadores e projetos de servidores independentes</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}


if($Aplic->modulo_ativo('social')){
	echo '<tr><td width="42" valign="top">'.imagem('../../../modulos/social/imagens/social.gif','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Programas Sociais</td></tr>';
	if($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'exporta_familia')) echo '<tr><td align="left">'.dica('Gerar Arquivo com os Beneficiários', 'Gerar, a partir de dispositivo off-line, arquivo de exportação dos beneficiários, para que possa ser instalado no Servidor.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=exportar\');">Gerar arquivo com os beneficiários</a>'.dicaF().'</td></tr>';
	if($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'importa_familia')) echo '<tr><td align="left">'.dica('Importar Arquivo com os Beneficiários', 'Abre uma janela onde se pode importar o arquivo de beneficiários gerados a partir de dispositivos off-line para o servidor central.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=importar\');">Importar arquivo com os beneficiários</a>'.dicaF().'</td></tr>';
	if($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'gera_notebook')) echo '<tr><td align="left">'.dica('Gerar Arquivo de Preparação de Dispositivo Off-Line', 'Abre uma janela onde se pode gerar o arquivo de praparação dos dispositivos que irão trabalhar off-line no cadastramento de beneficiários, com os programas, as ações, comitês e comunidades.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=exportar_notebook\');">Gerar arquivo de preparação de dispositivo off-line</a>'.dicaF().'</td></tr>';
	if($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'importa_notebook')) echo '<tr><td align="left">'.dica('Instalar em Dispositivo Off-Line o Arquivo de Preparação', 'Abre uma janela onde se pode importar o arquivo de praparação de dispositivo off-line, criado no Servidor, para que este possa trabalhar no cadastramento de beneficiários, contendo os programas, as ações, comitês e comunidades.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=importar_notebook\');">Instalar em dispositivo off-line o arquivo de preparação</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	} 


echo '<tr><td width="42" valign="top">'.imagem('email.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">'.ucfirst($config['mensagens']).'</td></tr>';
echo '<tr><td align="left">'.dica('Grupos de Destinatários', 'Configurar os grupos de destinatários default do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=administracao\');">Grupos de destinatários</a>'.dicaF().'</td></tr>';
echo '<tr><td align="left">'.dica('Grupos vs '.ucfirst($config['usuarios']), 'Matriz correlacionando '.$config['genero_usuario'].'s '.$config['usuarios'].' nos diversos grupos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=exibir_usuarios_grupos\');">Grupos vs '.$config['usuarios'].'</a>'.dicaF().'</td></tr>';
echo '<tr><td align="left">'.dica(ucfirst($config['usuario']).' nos Grupos', 'Configurar as permissões de visualização de grupos para cada usuário, assim como cadastrar o mesmo nos grupos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=usuario_grupos\');">'.ucfirst($config['usuario']).' nos grupos</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Edição de Modelos de Documentos', 'Criação e edição de modelos de documentos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=modelos_documentos\');">Edição de modelos de documentos</a>'.dicaF().'</td></tr>';
if($Aplic->usuario_super_admin) echo '<tr><td align="left">'.dica('Modelos de Documentos vs '.ucfirst($config['organizacao']), 'Definição de quais modelos de documentos cada '.$config['organizacao'].' tem acesso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=email&a=modelos_cias\');">Modelos de documentos vs '.$config['organizacao'].'</a>'.dicaF().'</td></tr>';

echo '</table></td></tr>';


echo '<tr><td width="42" valign="top">'.imagem('calendario.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Agenda</td></tr>';
echo '<tr><td align="left">'.dica("Agenda Coletiva", "Abre uma janela onde se pode configurar agendas coletivas.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=calendario&a=calendario_lista\');">Agenda coletiva</a>'.dicaF().'</td></tr>';
echo '</table></td></tr>';

if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('alertas.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Sistema de Alertas</td></tr>';
	echo '<tr><td align="left">'.dica('Configurações do Sistema de Alertas', 'Abre uma janela onde se pode configurar as opções de alertas automáticos do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=alarme_pro\');">Configurações do sistema de alertas</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}

if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('qr.gif','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Códigos nos documentos</td></tr>';
	echo '<tr><td align="left">'.dica('Configurações dos Códigos', 'Abre uma janela onde se pode configurar as opções de códigos de barra, QR, dentre outros, na impressão de documentos.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=codigos_pro\');">Configurações dos códigos</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}



if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('bsc.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Balanced score card</td></tr>';
	echo '<tr><td align="left">'.dica('Pauta de Pontuação', 'Criar ou editar as pautas de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=pauta\');">Pauta de pontuação</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Critérios', 'Criar ou editar critério de pauta de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=criterio\');">Critérios</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Itens', 'Criar ou editar itens de pauta de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=item\');">Itens</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Alíneas', 'Criar ou editar alíneas de item de pauta de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=marcador\');">Alíneas</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Requisito de Adequação', 'Criar ou editar os requisitos de adequação de alíneas de BSC do sistema.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=verbo\');">Requisito de adequação</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Grau de Maturidade', 'Criar ou editar a tabela de pontuação com grau de maturidade equivalente.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=maturidade\');">Grau de maturidade</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Regra de Pontuação', 'Criar ou editar a tabela de regra de pontuação da pauta.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=regra\');">Regra de pontuação</a>'.dicaF().'</td></tr>';
	echo '<tr><td align="left">'.dica('Descrição dos Requisitos', 'Criar ou editar a descrição dos requisitos da pauta de pontuação.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=pauta&a=campo\');">Descrição dos requisitos</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}


if ($Aplic->profissional & $Aplic->usuario_super_admin){
	echo '<tr><td width="42" valign="top">'.imagem('dinheiro.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Elementos de despesa</td></tr>';
	echo '<tr><td align="left">'.dica('Configurar Elementos de Despesa', 'Criar ou editar os elementos de despesa.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=nd&a=nd_lista\');">Configurar os elementos de despesa</a>'.dicaF().'</td></tr>';
	echo '</table></td></tr>';
	}


if($Aplic->usuario_super_admin) {
	echo '<tr><td width="42" valign="top">'.imagem('modulos.png','','',1).'</td><td><table><tr><td align="left" style="color:#666;margin-bottom:0;	margin-top:15px; font:700 12px verdana, arial, helvetica, sans-serif">Módulos</td></tr>';
	echo '<tr><td align="left">'.dica("Ver Módulos", "Abre uma janela onde se pode selecionar quais módulos deverão estar habilitados e visualizados.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=vermods\');">Ver módulos</a>'.dicaF().'</td></tr>';
  //echo '<tr><td align="left">'.dica("Instalar SQL Extra", "Abre uma janela onde se pode selecionar codigo SQL extra a ser instalado no '.$config['gpweb'].'.").'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=instalar_sql\');">Instalar SQL extra</a>'.dicaF().'</td></tr>';
  }
echo '</table></td></tr>';

echo '</table>';
echo estiloFundoCaixa();
?>

<script language="javascript">
	
		
</script>
