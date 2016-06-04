<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa GP-Web
O GP-Web � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

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
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>In�cio</b></td><td>'.substr($data['inicio'],0,5).' hs</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>T�rmino</b></td><td>'.substr($data['fim'],0,5).' hs</td></tr>';
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