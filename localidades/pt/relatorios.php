<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $config;
$traducao=array(
'relatorio_gestao_titulo'=>ucfirst($config['plano_gestao']),
'relatorio_gestao_descricao'=>'Impress�o d'.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']),
'relatorio_gestao_dica'=> ucfirst($config['genero_plano_gestao']).' '.ucfirst($config['plano_gestao']).' contem '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' e indicadores j� dentro de uma r�gua de pontua��o.',

'atualizacao_valores_titulo'=>'Atualiza��o dos Indicadores',
'atualizacao_valores_descricao'=>'Lista dos indicadores com a data da �ltima atualiza��o.',
'atualizacao_valores_dica'=>'Lista dos indicadores com a data da �ltima atualiza��o dos valores dos mesmos.',

'adequacao_om_pro_titulo'=>'Lacunas de Adequa��o',
'adequacao_om_pro_descricao'=>'As lacunas de adequa��o numa pauta determinada.',
'adequacao_om_pro_dica'=>'Lista as lacunas de adequa��o numa pauta determinada.',

'pontuacao_titulo'=>'Pontua��o Final',
'pontuacao_descricao'=>'Pontua��o final para os processos gerenciais e resultados organizacionais.',
'pontuacao_dica'=>'Lista a pontua��o por �tem , crit�rio e final, tanto para os processos gerenciais quanto para os resultados organizacionais.',

'arvore_titulo'=>'Pontua��o em Forma de �rvore Hier�rquica',
'arvore_descricao'=>'Pontua��o final para os processos gerenciais e resultados organizacionais na forma de �rvore hier�rquica.',
'arvore_dica'=>'Lista a pontua��o por �tem , crit�rio e final, tanto para os processos gerenciais quanto para os resultados organizacionais na forma de �rvore hier�rquica.',

'grafico_pontuacao_titulo'=>'Gr�fico da Pontua��o Final',
'grafico_pontuacao_descricao'=>'Gr�fico da pontua��o final para os processos gerenciais e resultados organizacionais.',
'grafico_pontuacao_dica'=>'Exibe um gr�fico da pontua��o final por crit�rio crit�rio, tanto para os processos gerenciais quanto para os resultados organizacionais.',

'detalhes_praticas_titulo'=>'Lista Detalhada d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']),
'detalhes_praticas_descricao'=>'Lista detalhada d'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' com as principais informa��es relevantes, que atendem a pauta de pontua��o selecionada.',
'detalhes_praticas_dica'=>'Lista detalhada d'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' com as principais informa��es relevantes, que atendem a pauta de pontua��o selecionada.',

'praticas_marcadores_titulo'=>'Lista d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']),
'praticas_marcadores_descricao'=>'Lista d'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' n'.$config['genero_marcador'].'s '.$config['marcadores'].' de processos gerenciais.',
'praticas_marcadores_dica'=>'Lista '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' n'.$config['genero_marcador'].'s '.$config['marcadores'].' de processos gerenciais.',

'resultados_marcadores_titulo'=>'Lista dos Resultados',
'resultados_marcadores_descricao'=>'Lista dos resultados n'.$config['genero_marcador'].'s '.$config['marcadores'].' de processos gerenciais.',
'resultados_marcadores_dica'=>'Lista dos resultados n'.$config['genero_marcador'].'s '.$config['marcadores'].' de processos gerenciais.',


'praticas_titulo'=>'Pontos Fortes d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']),
'praticas_descricao'=>'Lista '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' considerad'.$config['genero_pratica'].'s pontos fortes n'.$config['genero_marcador'].'s '.$config['marcadores'].' de processos gerenciais.',
'praticas_dica'=>'Lista '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' considerad'.$config['genero_pratica'].'s pontos fortes n'.$config['genero_marcador'].'s '.$config['marcadores'].' de processos gerenciais.',

'praticas_om_titulo'=>'Oportunidades de Melhoria n'.$config['genero_pratica'].'s '.ucfirst($config['praticas']),
'praticas_om_descricao'=>'Correlacionamento d'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' com '.$config['genero_marcador'].'s '.$config['marcadores'].' de processos gerenciais e que estejam com lacunas nos fatores.',
'praticas_om_dica'=>'Lista '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' relacionadas com '.$config['genero_marcador'].'s '.$config['marcadores'].' de processos gerenciais e que estejam com lacunas nos fatores.',

'resultados_titulo'=>'Pontos Fortes dos Resultados',
'resultados_descricao'=>'Correlacionamento dos pontos fortes dos indicadores com '.$config['genero_marcador'].'s '.$config['marcadores'].' de resultados organizacionais.',
'resultados_dica'=>'Correlacionamento dos pontos fortes dos indicadores com '.$config['genero_marcador'].'s '.$config['marcadores'].' de resultados organizacionais.',

'resultados_om_titulo'=>'Oportunidades de Melhorias nos Resultados',
'resultados_om_descricao'=>'Correlacionamento dos indicadores com '.$config['genero_marcador'].'s '.$config['marcadores'].' de resultados organizacionais e que estejam com lacunas nos fatores.',
'resultados_om_dica'=>'Lista os indicadores relacionados com '.$config['genero_marcador'].'s '.$config['marcadores'].' de resultados organizacionais e que estejam com lacunas nos fatores.',
);