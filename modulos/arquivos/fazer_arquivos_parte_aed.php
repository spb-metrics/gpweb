<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $Aplic;

$selecionado = getParam($_REQUEST, 'parte_selecionado_arquivo', null);
$parte_arquivo_pratica = getParam($_REQUEST, 'parte_arquivo_pratica', null);
$parte_arquivo_indicador = getParam($_REQUEST, 'parte_arquivo_indicador', null);
$parte_arquivo_acao = getParam($_REQUEST, 'parte_arquivo_acao', null);
$parte_arquivo_projeto = getParam($_REQUEST, 'parte_arquivo_projeto', null);
$parte_arquivo_usuario = getParam($_REQUEST, 'parte_arquivo_usuario', null);
$parte_arquivo_objetivo = getParam($_REQUEST, 'parte_arquivo_objetivo', null);
$parte_arquivo_estrategia = getParam($_REQUEST, 'parte_arquivo_estrategia', null);
$parte_arquivo_acao = getParam($_REQUEST, 'parte_arquivo_acao', null);
$parte_arquivo_fator = getParam($_REQUEST, 'parte_arquivo_fator', null);
$parte_arquivo_meta = getParam($_REQUEST, 'parte_arquivo_meta', null);
$parte_arquivo_calendario = getParam($_REQUEST, 'parte_arquivo_calendario', null);
$parte_arquivo_ata = getParam($_REQUEST, 'parte_arquivo_ata', null);
$parte_arquivo_perspectiva = getParam($_REQUEST, 'parte_arquivo_perspectiva', null);
$parte_arquivo_canvas = getParam($_REQUEST, 'parte_arquivo_canvas', null);
$parte_arquivo_tema = getParam($_REQUEST, 'parte_arquivo_tema', null);
$parte_arquivo_demanda = getParam($_REQUEST, 'parte_arquivo_demanda', null);
$parte_arquivo_instrumento = getParam($_REQUEST, 'parte_arquivo_instrumento', null);

$parte_arquivo_pasta = getParam($_REQUEST, 'parte_arquivo_pasta', 'O');
if (is_array($selecionado) && count($selecionado)) {
	$atualizar_arquivo = new CArquivo();
	foreach ($selecionado as $chave => $val) {
		if ($chave) {
			$atualizar_arquivo->load($chave);
			}
		if ($parte_arquivo_projeto != '' && $parte_arquivo_projeto != 'O') {
			if ($atualizar_arquivo->arquivo_id) {
				if ($atualizar_arquivo->arquivo_projeto != $parte_arquivo_projeto) {
					$antigoProjeto = $atualizar_arquivo->arquivo_projeto;
					$praticaAntiga = $atualizar_arquivo->arquivo_pratica;
					$indicadorAntigo = $atualizar_arquivo->arquivo_indicador;	
					$usuarioAntigo = $atualizar_arquivo->arquivo_usuario;	
					$objetivoAntigo = $atualizar_arquivo->arquivo_objetivo;
					$estrategiaAntigo = $atualizar_arquivo->estrategia;
					$acaoAntigo = $atualizar_arquivo->arquivo_acao;
					$fatorAntigo = $atualizar_arquivo->arquivo_fator;
					$metaAntigo = $atualizar_arquivo->arquivo_meta;
					$calendarioAntigo = $atualizar_calendario->arquivo_calendario;
					$ataAntiga = $atualizar_calendario->arquivo_ata;
					$perspectivaAntigo = $atualizar_arquivo->arquivo_perspectiva;
					$canvasAntigo = $atualizar_arquivo->arquivo_canvas;
					$temaAntigo = $atualizar_arquivo->arquivo_tema;
					$demandaAntiga = $atualizar_arquivo->arquivo_demanda;
					$instrumentoAntigo = $atualizar_arquivo->arquivo_instrumento;
					
					$atualizar_arquivo->arquivo_projeto = $parte_arquivo_projeto;
					$atualizar_arquivo->arquivo_pratica = $parte_arquivo_pratica;
					$atualizar_arquivo->arquivo_indicador = $parte_arquivo_indicador;
					$atualizar_arquivo->arquivo_usuario = $parte_arquivo_usuario;
					$atualizar_arquivo->arquivo_objetivo = $parte_arquivo_objetivo;
					$atualizar_arquivo->estrategia = $parte_arquivo_estrategia;
					$atualizar_arquivo->arquivo_acao = $parte_arquivo_acao;
					$atualizar_arquivo->arquivo_fator = $parte_arquivo_fator;
					$atualizar_arquivo->arquivo_meta = $parte_arquivo_meta;
					$atualizar_calendario->arquivo_calendario = $parte_arquivo_calendario;
					$atualizar_ata->arquivo_ata = $parte_arquivo_ata;
					$atualizar_arquivo->arquivo_perspectiva = $parte_arquivo_perspectiva;
					$atualizar_arquivo->arquivo_canvas = $parte_arquivo_canvas;
					$atualizar_arquivo->arquivo_tema = $parte_arquivo_tema;
					$atualizar_arquivo->arquivo_instrumento = $parte_arquivo_instrumento;
					$atualizar_arquivo->arquivo_instrumento = $parte_arquivo_instrumento;

					$res = $atualizar_arquivo->moverArquivo($atualizar_arquivo->arquivo_nome_real, $antigoProjeto, $praticaAntiga, $indicadorAntigo, $usuarioAntigo, $objetivoAntigo, $estrategiaAntigo, $acaoAntigo, $fatorAntigo, $metaAntigo, $perspectivaAntigo, $temaAntigo, $demandaAntiga, $calendrioAntigo, $ataAntiga, $instrumentoAntigo, $canvasAntigo);
					if (!$res) $Aplic->setMsg('Ao menos um arquivo não foi possível mover', UI_MSG_ERRO);
					}
				$atualizar_arquivo->armazenar();
				}
			}
		
		if ($parte_arquivo_pratica != '' && $parte_arquivo_pratica != 'O') {
			if ($atualizar_arquivo->arquivo_id) {
				if ($atualizar_arquivo->arquivo_pratica != $parte_arquivo_pratica) {
					$antigoProjeto = $atualizar_arquivo->arquivo_projeto;
					$praticaAntiga = $atualizar_arquivo->arquivo_pratica;
					$indicadorAntigo = $atualizar_arquivo->arquivo_indicador;
					$usuarioAntigo = $atualizar_arquivo->arquivo_usuario;	
					$objetivoAntigo = $atualizar_arquivo->arquivo_objetivo;
					$estrategiaAntigo = $atualizar_arquivo->estrategia;
					$acaoAntigo = $atualizar_arquivo->arquivo_acao;
					$fatorAntigo = $atualizar_arquivo->arquivo_fator;
					$metaAntigo = $atualizar_arquivo->arquivo_meta;
					$calendarioAntigo = $atualizar_arquivo->arquivo_calendario;
					$ataAntiga = $atualizar_arquivo->arquivo_ata;
					$perspectivaAntigo = $atualizar_arquivo->arquivo_perspectiva;
					$canvasAntigo = $atualizar_arquivo->arquivo_canvas;
					$temaAntigo = $atualizar_arquivo->arquivo_tema;
					$demandaAntiga = $atualizar_arquivo->arquivo_demanda;
					$instrumentoAntigo = $atualizar_arquivo->arquivo_instrumento;
					
					$atualizar_arquivo->arquivo_projeto = $parte_arquivo_projeto;
					$atualizar_arquivo->arquivo_pratica = $parte_arquivo_pratica;
					$atualizar_arquivo->arquivo_indicador = $parte_arquivo_indicador;
					$atualizar_arquivo->arquivo_usuario = $parte_arquivo_usuario;
					$atualizar_arquivo->arquivo_objetivo = $parte_arquivo_objetivo;
					$atualizar_arquivo->estrategia = $parte_arquivo_estrategia;
					$atualizar_arquivo->arquivo_acao = $parte_arquivo_acao;
					$atualizar_arquivo->arquivo_fator = $parte_arquivo_fator;
					$atualizar_arquivo->arquivo_meta = $parte_arquivo_meta;
					$atualizar_arquivo->arquivo_calendario = $parte_arquivo_calendario;
					$atualizar_arquivo->arquivo_ata = $parte_arquivo_ata;
					$atualizar_arquivo->arquivo_perspectiva = $parte_arquivo_perspectiva;
					$atualizar_arquivo->arquivo_canvas = $parte_arquivo_canvas;
					$atualizar_arquivo->arquivo_tema = $parte_arquivo_tema;
					$atualizar_arquivo->arquivo_demanda = $parte_arquivo_demanda;
					$atualizar_arquivo->arquivo_instrumento = $parte_arquivo_instrumento;
					
					$res = $atualizar_arquivo->moverArquivo($atualizar_arquivo->arquivo_nome_real, $antigoProjeto, $praticaAntiga, $indicadorAntigo, $usuarioAntigo, $objetivoAntigo, $estrategiaAntigo, $acaoAntigo, $fatorAntigo, $metaAntigo, $perspectivaAntigo, $temaAntigo, $demandaAntiga, $calendarioAntigo, $ataAntiga, $instrumentoAntigo, $canvasAntigo);
					if (!$res) $Aplic->setMsg('Ao menos un arquivo não foi possível mover', UI_MSG_ERRO);
					}
				$atualizar_arquivo->armazenar();
				}
			}	
		
		if ($parte_arquivo_indicador != '' && $parte_arquivo_indicador != 'O') {
			if ($atualizar_arquivo->arquivo_id) {
				if ($atualizar_arquivo->arquivo_indicador != $parte_arquivo_indicador){
					$antigoProjeto = $atualizar_arquivo->arquivo_projeto;
					$praticaAntiga = $atualizar_arquivo->arquivo_pratica;
					$indicadorAntigo = $atualizar_arquivo->arquivo_indicador;
					
					$usuarioAntigo = $atualizar_arquivo->arquivo_usuario;	
					$objetivoAntigo = $atualizar_arquivo->arquivo_objetivo;
					$estrategiaAntigo = $atualizar_arquivo->estrategia;
					$acaoAntigo = $atualizar_arquivo->arquivo_acao;
					$fatorAntigo = $atualizar_arquivo->arquivo_fator;
					$metaAntigo = $atualizar_arquivo->arquivo_meta;
					$calendarioAntigo = $atualizar_arquivo->arquivo_calendario;
					$ataAntiga = $atualizar_arquivo->arquivo_ata;
					$perspectivaAntigo = $atualizar_arquivo->arquivo_perspectiva;
					$canvasAntigo = $atualizar_arquivo->arquivo_canvas;
					$temaAntigo = $atualizar_arquivo->arquivo_tema;
					$demandaAntiga = $atualizar_arquivo->arquivo_demanda;
					$instrumentoAntigo = $atualizar_arquivo->arquivo_instrumento;
					
					$atualizar_arquivo->arquivo_projeto = $parte_arquivo_projeto;
					$atualizar_arquivo->arquivo_pratica = $parte_arquivo_pratica;
					$atualizar_arquivo->arquivo_indicador = $parte_arquivo_indicador;
					$atualizar_arquivo->arquivo_usuario = $parte_arquivo_usuario;
					$atualizar_arquivo->arquivo_objetivo = $parte_arquivo_objetivo;
					$atualizar_arquivo->estrategia = $parte_arquivo_estrategia;
					$atualizar_arquivo->arquivo_acao = $parte_arquivo_acao;
					$atualizar_arquivo->arquivo_fator = $parte_arquivo_fator;
					$atualizar_arquivo->arquivo_meta = $parte_arquivo_meta;
					$atualizar_arquivo->arquivo_calendario = $parte_arquivo_calendario;
					$atualizar_arquivo->arquivo_ata = $parte_arquivo_ata;
					$atualizar_arquivo->arquivo_perspectiva = $parte_arquivo_perspectiva;
					$atualizar_arquivo->arquivo_canvas = $parte_arquivo_canvas;
					$atualizar_arquivo->arquivo_tema = $parte_arquivo_tema;
					$atualizar_arquivo->arquivo_demanda = $parte_arquivo_demanda;
					$atualizar_arquivo->arquivo_instrumento = $parte_arquivo_instrumento;
					
					$res = $atualizar_arquivo->moverArquivo($atualizar_arquivo->arquivo_nome_real, $antigoProjeto, $praticaAntiga, $indicadorAntigo, $usuarioAntigo, $objetivoAntigo, $estrategiaAntigo, $acaoAntigo, $fatorAntigo, $metaAntigo, $perspectivaAntigo, $temaAntigo, $demandaAntiga, $calendarioAntigo, $ataAntiga, $instrumentoAntigo, $canvasAntigo);
					if (!$res) $Aplic->setMsg('Ao menos un arquivo não foi possível mover', UI_MSG_ERRO);
					}
				$atualizar_arquivo->armazenar();
				}
			}		
		
		
		if ($parte_arquivo_usuario != '' && $parte_arquivo_usuario != 'O') {
			if ($atualizar_arquivo->arquivo_id) {
				if ($atualizar_arquivo->arquivo_usuario != $parte_arquivo_usuario){
					$antigoProjeto = $atualizar_arquivo->arquivo_projeto;
					$praticaAntiga = $atualizar_arquivo->arquivo_pratica;
					$indicadorAntigo = $atualizar_arquivo->arquivo_indicador;
					$usuarioAntigo = $atualizar_arquivo->arquivo_usuario;	
					$objetivoAntigo = $atualizar_arquivo->arquivo_objetivo;
					$estrategiaAntigo = $atualizar_arquivo->estrategia;
					$acaoAntigo = $atualizar_arquivo->arquivo_acao;
					$fatorAntigo = $atualizar_arquivo->arquivo_fator;
					$metaAntigo = $atualizar_arquivo->arquivo_meta;
					$calendarioAntigo = $atualizar_arquivo->arquivo_calendario;
					$ataAntiga = $atualizar_arquivo->arquivo_ata;
					$perspectivaAntigo = $atualizar_arquivo->arquivo_perspectiva;
					$canvasAntigo = $atualizar_arquivo->arquivo_canvas;
					$temaAntigo = $atualizar_arquivo->arquivo_tema;
					$demandaAntiga = $atualizar_arquivo->arquivo_demanda;
					$instrumentoAntigo = $atualizar_arquivo->arquivo_instrumento;
					
					$atualizar_arquivo->arquivo_projeto = $parte_arquivo_projeto;
					$atualizar_arquivo->arquivo_pratica = $parte_arquivo_pratica;
					$atualizar_arquivo->arquivo_indicador = $parte_arquivo_indicador;
					$atualizar_arquivo->arquivo_usuario = $parte_arquivo_usuario;
					$atualizar_arquivo->arquivo_objetivo = $parte_arquivo_objetivo;
					$atualizar_arquivo->estrategia = $parte_arquivo_estrategia;
					$atualizar_arquivo->arquivo_acao = $parte_arquivo_acao;
					$atualizar_arquivo->arquivo_fator = $parte_arquivo_fator;
					$atualizar_arquivo->arquivo_meta = $parte_arquivo_meta;
					$atualizar_arquivo->arquivo_calendario = $parte_arquivo_calendario;
					$atualizar_arquivo->arquivo_ata = $parte_arquivo_ata;
					$atualizar_arquivo->arquivo_perspectiva = $parte_arquivo_perspectiva;
					$atualizar_arquivo->arquivo_canvas = $parte_arquivo_canvas;
					$atualizar_arquivo->arquivo_tema = $parte_arquivo_tema;
					$atualizar_arquivo->arquivo_demanda = $parte_arquivo_demanda;
					$atualizar_arquivo->arquivo_instrumento = $parte_arquivo_instrumento;
					
					$res = $atualizar_arquivo->moverArquivo($atualizar_arquivo->arquivo_nome_real, $antigoProjeto, $praticaAntiga, $indicadorAntigo, $usuarioAntigo, $objetivoAntigo, $estrategiaAntigo, $acaoAntigo, $fatorAntigo, $metaAntigo, $perspectivaAntigo, $temaAntigo, $demandaAntiga, $calendarioAntigo, $ataAntiga, $instrumentoAntigo, $canvasAntigo);
					if (!$res) $Aplic->setMsg('Ao menos un arquivo não foi possível mover', UI_MSG_ERRO);
					}
				$atualizar_arquivo->armazenar();
				}
			}		
		
			
		if (isset($_REQUEST['parte_arquivo_pasta']) && $parte_arquivo_pasta != '' && $parte_arquivo_pasta != 'O') {
			if ($atualizar_arquivo->arquivo_id) {
				$atualizar_arquivo->arquivo_pasta = $parte_arquivo_pasta;
				$atualizar_arquivo->armazenar();
				}
			}
		echo db_error();
		}
	}
$Aplic->redirecionar('m=arquivos&a=index');
?>