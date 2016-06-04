<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $config;
$traducao=array(
'relatorio_gestao_titulo'=>ucfirst($config['plano_gestao']),
'relatorio_gestao_descricao'=>'Impressão d'.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']),
'relatorio_gestao_dica'=> ucfirst($config['genero_plano_gestao']).' '.ucfirst($config['plano_gestao']).' contem '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' e indicadores já dentro de uma régua de pontuação.',

'atualizacao_valores_titulo'=>'Atualização dos Indicadores',
'atualizacao_valores_descricao'=>'Lista dos indicadores com a data da última atualização.',
'atualizacao_valores_dica'=>'Lista dos indicadores com a data da última atualização dos valores dos mesmos.',

'adequacao_om_pro_titulo'=>'Lacunas de Adequação',
'adequacao_om_pro_descricao'=>'As lacunas de adequação numa pauta determinada.',
'adequacao_om_pro_dica'=>'Lista as lacunas de adequação numa pauta determinada.',

'pontuacao_titulo'=>'Pontuação Final',
'pontuacao_descricao'=>'Pontuação final para os processos gerenciais e resultados organizacionais.',
'pontuacao_dica'=>'Lista a pontuação por ítem , critério e final, tanto para os processos gerenciais quanto para os resultados organizacionais.',

'arvore_titulo'=>'Pontuação em Forma de Árvore Hierárquica',
'arvore_descricao'=>'Pontuação final para os processos gerenciais e resultados organizacionais na forma de árvore hierárquica.',
'arvore_dica'=>'Lista a pontuação por ítem , critério e final, tanto para os processos gerenciais quanto para os resultados organizacionais na forma de árvore hierárquica.',

'grafico_pontuacao_titulo'=>'Gráfico da Pontuação Final',
'grafico_pontuacao_descricao'=>'Gráfico da pontuação final para os processos gerenciais e resultados organizacionais.',
'grafico_pontuacao_dica'=>'Exibe um gráfico da pontuação final por critério critério, tanto para os processos gerenciais quanto para os resultados organizacionais.',

'detalhes_praticas_titulo'=>'Lista Detalhada d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']),
'detalhes_praticas_descricao'=>'Lista detalhada d'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' com as principais informações relevantes, que atendem a pauta de pontuação selecionada.',
'detalhes_praticas_dica'=>'Lista detalhada d'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' com as principais informações relevantes, que atendem a pauta de pontuação selecionada.',

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