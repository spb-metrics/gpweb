<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

function getExpedienteLinks($inicioPeriodo, $fimPeriodo, &$links, $cia_id=0, $usuario_id=0, $projeto_id=0, $recurso_id=0, $tarefa_id=0) {
	global $a, $Aplic, $config;
	$q = new BDConsulta;
	$q->adTabela('expediente');
	$q->adCampo('data, inicio, fim');
	$q->adCampo('IF (((almoco_inicio > inicio) AND (almoco_fim < fim)), (tempo_em_segundos(diferenca_tempo(almoco_inicio, inicio))+tempo_em_segundos(diferenca_tempo(fim, almoco_fim)))/3600, tempo_em_segundos(diferenca_tempo(fim, inicio))/3600) AS horas');
	//$q->adCampo('CASE WHEN ((almoco_inicio > inicio) AND (almoco_fim < fim)) THEN ( tempo_em_segundos(CAST((almoco_inicio - inicio) AS TIME)) + tempo_em_segundos(CAST((fim - almoco_fim) AS TIME)) / 3600 ) ELSE ( tempo_em_segundos(CAST((fim - inicio) AS TIME) ) / 3600 ) END AS horas');
	//EUZEBIO ERRADO
	$q->adOnde('data >= \''.$inicioPeriodo->format('%Y-%m-%d').'\' AND data <=\''.$fimPeriodo->format('%Y-%m-%d').'\'');
	if ($usuario_id) $q->adOnde('usuario_id='.(int)$usuario_id);
	elseif ($recurso_id) $q->adOnde('recurso_id='.(int)$recurso_id);
	elseif ($tarefa_id) $q->adOnde('tarefa_id='.(int)$tarefa_id);
	elseif ($projeto_id) $q->adOnde('projeto_id='.(int)$projeto_id);
	else $q->adOnde('cia_id='.(int)$cia_id);
	$datas = $q->Lista();
	$q->limpar();
	
	$tf = $Aplic->getPref('formatohora');
	foreach ($datas as $data) {
		$dia= new CData($data['data']);
		if ($data['horas']>0){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Total de Horas</b></td><td>'.(int)$data['horas'].'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Início</b></td><td>'.substr($data['inicio'],0,5).' hs</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Término</b></td><td>'.substr($data['fim'],0,5).' hs</td></tr>';
			$dentro .= '</table>';
			$temp =  array('expediente' => true, 'texto_mini' => '<tr><td>'.$dentro.'</td></tr>', 'horas' => $data['horas']);
			}
		else {
			$temp =  array('expediente' => true, 'texto_mini' => '<tr><td><b>Sem Expediente</b></td></tr>', 'horas' => 0);	
			}	
		$links[$dia->format(FMT_TIMESTAMP_DATA)][] = $temp;
		}	
	}


?>