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

require_once ($Aplic->getClasseSistema('libmail'));
//require_once ($Aplic->getClasseSistema('aplic'));
//require_once ($Aplic->getClasseSistema('data'));

class CArquivo extends CAplicObjeto {
	var $arquivo_id = null;
	var $arquivo_superior = null;
	var $arquivo_cia = null;
	var $arquivo_dept = null;
	var $arquivo_dono = null;
	var $arquivo_usuario_upload = null;
	var $arquivo_pasta = null;
	var $chave_publica = null;
	var $arquivo_projeto = null;
	var $arquivo_tarefa = null;
	var $arquivo_pratica = null;
	var $arquivo_acao = null;
	var $arquivo_indicador = null;
	var $arquivo_usuario = null;
	var $arquivo_objetivo = null;
	var $arquivo_perspectiva = null;
	var $arquivo_tema = null;
	var $arquivo_fator = null;
	var $arquivo_estrategia = null;
	var $arquivo_meta = null;
	var $arquivo_demanda = null;
	var $arquivo_instrumento = null;
	var $arquivo_calendario = null;
	var $arquivo_ata = null;
	var $arquivo_canvas = null;
	var $arquivo_versao_id = null;
	var $arquivo_categoria = null;
	var $arquivo_nome = null;
	var $arquivo_nome_real = null;
	var $arquivo_local = null;
	var $arquivo_descricao = null;
	var $arquivo_acesso = null;
	var $assinatura = null;
	var $arquivo_data = null;
	var $arquivo_tipo = null;
	var $arquivo_versao = null;
	var $arquivo_saida = null;
	var $arquivo_motivo_saida = null;
	var $arquivo_icone = null;
	var $arquivo_tamanho = null;
	var $arquivo_cor = null;
	var $arquivo_ativo = null;
	var $arquivo_principal_indicador = null;

	function __construct() {
		global $Aplic;
		parent::__construct('arquivos', 'arquivo_id');
		}

	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$msg = $this->check();
		if ($msg) return 'CArquivo::checagem para armazenar falhou '.$msg;
		$sql = new BDConsulta();
		if ($this->arquivo_id) {
			$ret = $sql->atualizarObjeto('arquivos', $this, 'arquivo_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('arquivos', $this, 'arquivo_id');
			$sql->limpar();
			}


		$arquivo_usuarios=getParam($_REQUEST, 'arquivo_usuarios', null);
		$arquivo_usuarios=explode(',', $arquivo_usuarios);
		$sql->setExcluir('arquivo_usuario');
		$sql->adOnde('arquivo_usuario_arquivo = '.$this->arquivo_id);
		$sql->exec();
		$sql->limpar();
		foreach($arquivo_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('arquivo_usuario');
				$sql->adInserir('arquivo_usuario_arquivo', $this->arquivo_id);
				$sql->adInserir('arquivo_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'arquivo_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('arquivo_dept');
		$sql->adOnde('arquivo_dept_arquivo = '.$this->arquivo_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('arquivo_dept');
				$sql->adInserir('arquivo_dept_arquivo', $this->arquivo_id);
				$sql->adInserir('arquivo_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('arquivo_cia');
			$sql->adOnde('arquivo_cia_arquivo='.(int)$this->arquivo_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'arquivo_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('arquivo_cia');
						$sql->adInserir('arquivo_cia_arquivo', $this->arquivo_id);
						$sql->adInserir('arquivo_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('arquivo_gestao');
			$sql->adAtualizar('arquivo_gestao_arquivo', (int)$this->arquivo_id);
			$sql->adAtualizar('arquivo_gestao_uuid', null);
			$sql->adOnde('arquivo_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_arquivo', (int)$this->arquivo_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('arquivos', $this->arquivo_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->arquivo_id);
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	function check() {
		$this->arquivo_versao_id = intval($this->arquivo_versao_id);
		return null;
		}
	function retirada($usuarioId, $arquivoId, $coRazao) {
		$q = new BDConsulta;
		$q->adTabela('arquivos');
		$q->adAtualizar('arquivo_saida', $usuarioId);
		$q->adAtualizar('arquivo_motivo_saida', $coRazao);
		$q->adOnde('arquivo_id = '.(int)$arquivoId);
		$q->exec();
		$q->limpar();
		return true;
		}
	function excluir($oid = NULL) {
		global $Aplic;
		if (!$this->podeExcluir($msg)) return $msg;
		$this->_mensagem = 'excluido';
	
		if ($Aplic->getEstado('arquivo_id', null)==$this->arquivo_id) $Aplic->setEstado('arquivo_id', null);
		parent::excluir();
		return null;
		}
	function excluirArquivo() {
		//ERRO corrigir depois
		global $config;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		/*
		if ($this->arquivo_projeto) return @unlink($base_dir.'/arquivos/projetos/'.$this->arquivo_projeto.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_pratica) return @unlink($base_dir.'/arquivos/praticas/'.$this->arquivo_pratica.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_demanda) return @unlink($base_dir.'/arquivos/demandas/'.$this->arquivo_demanda.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_instrumento) return @unlink($base_dir.'/arquivos/instrumentos/'.$this->arquivo_instrumento.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_indicador) return @unlink($base_dir.'/arquivos/indicadores/'.$this->arquivo_indicador.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_objetivo) return @unlink($base_dir.'/arquivos/objetivos/'.$this->arquivo_objetivo.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_tema) return @unlink($base_dir.'/arquivos/temas/'.$this->arquivo_tema.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_perspectiva) return @unlink($base_dir.'/arquivos/perspectivas/'.$this->arquivo_perspectiva.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_canvas) return @unlink($base_dir.'/arquivos/canvas/'.$this->arquivo_canvas.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_estrategia) return @unlink($base_dir.'/arquivos/estrategias/'.$this->arquivo_estrategia.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_usuario) return @unlink($base_dir.'/arquivos/usuarios/'.$this->arquivo_usuario.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_fator) return @unlink($base_dir.'/arquivos/fatores/'.$this->arquivo_fator.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_meta) return @unlink($base_dir.'/arquivos/metas/'.$this->arquivo_meta.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_calendario) return @unlink($base_dir.'/arquivos/calendarios/'.$this->arquivo_calendario.'/'.$this->arquivo_nome_real);
		elseif ($this->arquivo_ata) return @unlink($base_dir.'/arquivos/atas/'.$this->arquivo_ata.'/'.$this->arquivo_nome_real);
		*/
		}

	function versaoArquivo(){


		}


	function moverArquivo($nomereal, $projetoAntigo='', $praticaAntiga='', $indicadorAntigo='', $usuarioAntigo='', $objetivoAntigo='', $estrategiaAntigo='', $acaoAntigo='', $fatorAntigo='', $metaAntigo='', $perspectivaAntigo='', $temaAntigo='', $demandaAntiga='', $calendarioAntigo='', $ataAntiga='', $instrumentoAntigo='', $canvasAntigo='') {
		global $Aplic, $config;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		//ERRO corrigir depois

		if ($this->arquivo_projeto) {$pasta='projetos'; $chave=$this->arquivo_projeto;}
		elseif ($this->arquivo_pratica) {$pasta='praticas'; $chave=$this->arquivo_pratica;}
		elseif ($this->arquivo_demanda) {$pasta='demandas'; $chave=$this->arquivo_demanda;}
		elseif ($this->arquivo_instrumento) {$pasta='instrumentos'; $chave=$this->arquivo_instrumento;}
		elseif ($this->arquivo_indicador) {$pasta='indicadores'; $chave=$this->arquivo_indicador;}
		elseif ($this->arquivo_perspectiva) {$pasta='perspectivas'; $chave=$this->arquivo_perspectiva;}
		elseif ($this->arquivo_canvas) {$pasta='canvas'; $chave=$this->arquivo_canvas;}
		elseif ($this->arquivo_tema) {$pasta='temas'; $chave=$this->arquivo_tema;}
		elseif ($this->arquivo_objetivo) {$pasta='objetivos'; $chave=$this->arquivo_objetivo;}
		elseif ($this->arquivo_estrategia) {$pasta='estrategias'; $chave=$this->arquivo_estrategia;}
		elseif ($this->arquivo_fator) {$pasta='fatores'; $chave=$this->arquivo_fator;}
		elseif ($this->arquivo_meta) {$pasta='metas'; $chave=$this->arquivo_meta;}
		elseif ($this->arquivo_calendario) {$pasta='calendarios'; $chave=$this->arquivo_calendario;}
		elseif ($this->arquivo_ata) {$pasta='atas'; $chave=$this->arquivo_ata;}
		elseif ($this->arquivo_usuario) {$pasta='usuarios'; $chave=$this->arquivo_usuario;}
		elseif ($this->arquivo_acao) {$pasta='planos_acao'; $chave=$this->arquivo_acao;}
		else {$pasta='generico'; $chave=$Aplic->usuario_cia;}

		if ($projetoAntigo) {$pasta_antiga='projetos'; $chave_antiga=$projetoAntigo;}
		elseif ($praticaAntiga) {$pasta_antiga='praticas'; $chave_antiga=$praticaAntiga;}
		elseif ($demandaAntiga) {$pasta_antiga='demandas'; $chave_antiga=$demandaAntiga;}
		elseif ($instrumentoAntigo) {$pasta_antiga='instrumentos'; $chave_antiga=$instrumentoAntigo;}
		elseif ($indicadorAntigo) {$pasta_antiga='indicadores'; $chave_antiga=$indicadorAntigo;}
		elseif ($usuarioAntigo) {$pasta_antiga='usuarios'; $chave_antiga=$usuarioAntigo;}
		elseif ($perspectivaAntigo) {$pasta_antiga='perspectivas'; $chave_antiga=$perspectivaAntigo;}
		elseif ($canvasAntigo) {$pasta_antiga='canvas'; $chave_antiga=$canvasAntigo;}
		elseif ($temaAntigo) {$pasta_antiga='temas'; $chave_antiga=$temaAntigo;}
		elseif ($objetivoAntigo) {$pasta_antiga='objetivos'; $chave_antiga=$objetivoAntigo;}
		elseif ($estrategiaAntigo) {$pasta_antiga='estrategias'; $chave_antiga=$estrategiaAntigo;}
		elseif ($fatorAntigo) {$pasta_antiga='fatores'; $chave_antiga=$fatorAntigo;}
		elseif ($metaAntigo) {$pasta_antiga='metas'; $chave_antiga=$metaAntigo;}
		elseif ($acaoAntigo) {$pasta_antiga='planos_acao'; $chave_antiga=$acaoAntigo;}
		elseif ($calendarioAntigo) {$pasta_antiga='calendarios'; $chave_antiga=$calendarioAntigo;}
		elseif ($ataAntiga) {$pasta_antiga='atas'; $chave_antiga=$ataAntiga;}
		else {$pasta_antiga='generico'; $chave_antiga=$Aplic->usuario_cia;}

		if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/'.$pasta)){
			$res = mkdir($base_dir.'/arquivos/'.$pasta, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\\'.$pasta, UI_MSG_ALERTA);
				return false;
				}
			}


		if (!is_dir($base_dir.'/arquivos/'.$pasta.'/'.$chave)){
			$res = mkdir($base_dir.'/arquivos/'.$pasta.'/'.$chave, 0777);
			if (!$res) {
				$Aplic->setMsg('A pasta para arquivos enviados não foi configurada para receber arquivos - mude as permissões no diretório/arquivo.', UI_MSG_ALERTA);
				return false;
				}
			}
		$res = rename($base_dir.'/arquivos/'.$pasta_antiga.'/'.$chave_antiga.'/'.$nomereal, $base_dir.'/arquivos/'.$pasta.'/'.$chave.'/'.$nomereal);
		if (!$res) return false;
		return true;
		}

	function duplicarArquivo($id_antigo, $nomereal) {
		global $Aplic, $config;
		//ERRO corrigir depois
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		if ($this->arquivo_projeto) $pasta='projetos';
		elseif ($this->arquivo_pratica) $pasta='praticas';
		elseif ($this->arquivo_demanda) $pasta='demandas';
		elseif ($this->arquivo_instrumento) $pasta='instrumentos';
		elseif ($this->arquivo_indicador) $pasta='indicadores';
		elseif ($this->arquivo_perspectiva) $pasta='perspectivas';
		elseif ($this->arquivo_canvas) $pasta='canvas';
		elseif ($this->arquivo_tema) $pasta='temas';
		elseif ($this->arquivo_objetivo) $pasta='objetivos';
		elseif ($this->arquivo_estrategia) $pasta='estrategias';
		elseif ($this->arquivo_fator) $pasta='fatores';
		elseif ($this->arquivo_meta) $pasta='metas';
		elseif ($this->arquivo_calendario) $pasta='calendarios';
		elseif ($this->arquivo_ata) $pasta='atas';
		elseif ($this->arquivo_usuario) $pasta='usuarios';
		elseif ($this->arquivo_acao) $pasta='planos_acao';

		if (!$id_antigo){
			if ($this->arquivo_projeto) $id_antigo=$this->arquivo_projeto;
			elseif ($this->arquivo_pratica) $id_antigo=$this->arquivo_pratica;
			elseif ($this->arquivo_demanda) $id_antigo=$this->arquivo_demanda;
			elseif ($this->arquivo_instrumento) $id_antigo=$this->arquivo_instrumento;
			elseif ($this->arquivo_indicador) $id_antigo=$this->arquivo_indicador;
			elseif ($this->arquivo_perspectiva) $id_antigo=$this->arquivo_perspectiva;
			elseif ($this->arquivo_canvas) $id_antigo=$this->arquivo_canvas;
			elseif ($this->arquivo_tema) $id_antigo=$this->arquivo_tema;
			elseif ($this->arquivo_objetivo) $id_antigo=$this->arquivo_objetivo;
			elseif ($this->arquivo_estrategia) $id_antigo=$this->arquivo_estrategia;
			elseif ($this->arquivo_fator) $id_antigo=$this->arquivo_fator;
			elseif ($this->arquivo_meta) $id_antigo=$this->arquivo_meta;
			elseif ($this->arquivo_calendario) $id_antigo=$this->arquivo_calendario;
			elseif ($this->arquivo_ata) $id_antigo=$this->arquivo_ata;
			elseif ($this->arquivo_usuario) $id_antigo=$this->arquivo_usuario;
			elseif ($this->arquivo_acao) $id_antigo=$this->arquivo_acao;
			}
		if (!$nomereal) $nomereal=$this->arquivo_nome_real;


		$dest_nomereal = uniqid(rand());

		$res = copy($base_dir.'/arquivos/'.$pasta.'/'.$id_antigo.'/'.$nomereal, $base_dir.'/arquivos/'.$pasta.'/'.$id_antigo.'/'.$dest_nomereal);

		if (!$res) return false;
		return $dest_nomereal;
		}

	function moverTemp($upload) {
		global $Aplic, $config;
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

		$dia=date('d');
		$mes=date('m');
		$ano=date('Y');

		if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/'.$ano)){
			$res = mkdir($base_dir.'/arquivos/'.$ano, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do ano para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos', UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos/'.$ano.'/'.$mes)){
			$res = mkdir($base_dir.'/arquivos/'.$ano.'/'.$mes, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do mês para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\'.$ano, UI_MSG_ALERTA);
				return false;
				}
			}

		if (!is_dir($base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia)){
			$res = mkdir($base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do dia para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\'.$ano.'\\'.$mes, UI_MSG_ALERTA);
				return false;
				}
			}

		$this->arquivo_local=$ano.'/'.$mes.'/'.$dia.'/';

		$this->_filepath = $base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia.'/'.$this->arquivo_nome_real;
		$res = move_uploaded_file($upload['tmp_name'], $this->_filepath);
		if (!$res) return false;
		return true;
		}


	function notificar() {
		global $Aplic, $config, $localidade_tipo_caract;
		$traducao=array ('atualizado'=>'atualizado', 'adicionado'=>'adicionado', 'excluido'=>'excluído');


		$sql = new BDConsulta;
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$email->Assunto($this->arquivo_nome, $localidade_tipo_caract);
	  $titulo=$this->arquivo_nome;
		$corpo = '<b>'.$this->arquivo_nome.'</b>';

		$sql->adTabela('arquivo_usuario');
		$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=arquivo_usuario_usuario');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo('contato_email, usuarios.usuario_id');
		$sql->adOnde('arquivo_usuario_arquivo = '.(int)$this->arquivo_id);
		$this->_usuarios = $sql->Lista();
		$sql->limpar();
		$corpo .= "\n\nO arquivo ".$this->arquivo_nome.' foi '.$this->_mensagem.' pelo '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
		if ($this->_mensagem != 'excluido') {
			$corpo .= "\n".'<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=ver&arquivo_id='.$this->arquivo_id.'\');">Detalhes do arquivo</a>'."\n";
			$corpo .= "\n".'<a href="'.BASE_URL.'/codigo/arquivo_visualizar.php?arquivo_id='.$this->arquivo_id.">Clique aqui para abrir o arquivo</a>\n";
			$corpo .= "\n"."<b>Descrição:</b>\n".$this->arquivo_descricao;
			}
		$email->Corpo($corpo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
		foreach ($this->_usuarios as $linha) {
			msg_email_interno ('', $titulo, $corpo,'', $linha['usuario_id']);
			if ($email->EmailValido($linha['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']) {
				$email->Para($linha['contato_email'], true);
				$email->Enviar();
				}
			}


		}


	function getResponsavel() {
		$responsavel = '';
		if (!$this->arquivo_dono)	return $responsavel;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('usuarios', 'a');
		$this->_consulta->esqUnir('contatos', 'b', 'b.contato_id = a.usuario_contato');
		$this->_consulta->adCampo('contato_posto, contato_nomeguerra');
		$this->_consulta->adOnde('a.usuario_id = '.(int)$this->arquivo_dono);
		if ($qid = &$this->_consulta->exec()) $responsavel = $qid->fields['contato_posto'].' '.$qid->fields['contato_nomeguerra'];
		$this->_consulta->limpar();
		return $responsavel;
		}
	function getTarefaNome() {
		$tarefaNome = '';
		if (!$this->arquivo_tarefa)	return $tarefaNome;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('tarefas');
		$this->_consulta->adCampo('tarefa_nome');
		$this->_consulta->adOnde('tarefa_id = '.(int)$this->arquivo_tarefa);
		if ($qid = &$this->_consulta->exec()) {
			if ($qid->fields['tarefa_nome']) $tarefaNome = $qid->fields['tarefa_nome'];
			else $tarefaNome = $qid->fields[0];
			}
		$this->_consulta->limpar();
		return $tarefaNome;
		}

	function getAcaoNome() {
		$acaoNome = '';
		if (!$this->arquivo_acao)	return $acaoNome;
		$this->_consulta->limpar();
		$this->_consulta->adTabela('plano_acao');
		$this->_consulta->adCampo('plano_acao_nome');
		$this->_consulta->adOnde('plano_acao_id = '.(int)$this->arquivo_acao);
		if ($qid = &$this->_consulta->exec()) {
			if ($qid->fields['plano_acao_nome']) $acaoNome = $qid->fields['plano_acao_nome'];
			else $acaoNome = $qid->fields[0];
			}
		$this->_consulta->limpar();
		return $acaoNome;
		}



	function podeAcessar() {
		global $Aplic;
		$valor=permiteAcessarArquivo($this->arquivo_acesso, $this->arquivo_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarArquivo($this->arquivo_acesso, $this->arquivo_id);
		return $valor;
		}


	}

class CPastaArquivo extends CAplicObjeto {
	var $arquivo_pasta_id = null;
  var $arquivo_pasta_superior = null;
  var $arquivo_pasta_cia = null;
  var $arquivo_pasta_dept = null;
  var $arquivo_pasta_dono = null;
  var $arquivo_pasta_projeto = null;
  var $arquivo_pasta_tarefa = null;
  var $arquivo_pasta_acesso = null;
  var $arquivo_pasta_pratica = null;
  var $arquivo_pasta_demanda = null;
  var $arquivo_pasta_instrumento = null;
  var $arquivo_pasta_acao = null;
  var $arquivo_pasta_indicador = null;
  var $arquivo_pasta_usuario = null;
  var $arquivo_pasta_perspectiva = null;
  var $arquivo_pasta_tema = null;
  var $arquivo_pasta_objetivo = null;
  var $arquivo_pasta_fator = null;
  var $arquivo_pasta_estrategia = null;
  var $arquivo_pasta_meta = null;
  var $arquivo_pasta_calendario = null;
  var $arquivo_pasta_ata = null;
  var $arquivo_pasta_canvas = null;
  var $arquivo_pasta_nome = null;
  var $arquivo_pasta_descricao = null;
  var $arquivo_pasta_cor = null;
  var $arquivo_pasta_ativo = null;


	function __construct() {
		parent::__construct('arquivo_pasta', 'arquivo_pasta_id');
		}

	function armazenar($atualizarNulos = false) {
		global $Aplic;
		$msg = $this->check();
		if ($msg) return 'CArquivo::checagem para armazenar falhou '.$msg;
		$sql = new BDConsulta();
		if ($this->arquivo_pasta_id) {
			$ret = $sql->atualizarObjeto('arquivo_pasta', $this, 'arquivo_pasta_id', false);
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('arquivo_pasta', $this, 'arquivo_pasta_id');
			$sql->limpar();
			}


		$arquivo_pasta_usuarios=getParam($_REQUEST, 'arquivo_pasta_usuarios', null);
		$arquivo_pasta_usuarios=explode(',', $arquivo_pasta_usuarios);
		$sql->setExcluir('arquivo_pasta_usuario');
		$sql->adOnde('arquivo_pasta_usuario_pasta = '.$this->arquivo_pasta_id);
		$sql->exec();
		$sql->limpar();
		foreach($arquivo_pasta_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('arquivo_pasta_usuario');
				$sql->adInserir('arquivo_pasta_usuario_pasta', $this->arquivo_pasta_id);
				$sql->adInserir('arquivo_pasta_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'arquivo_pasta_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('arquivo_pasta_dept');
		$sql->adOnde('arquivo_pasta_dept_pasta = '.$this->arquivo_pasta_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('arquivo_pasta_dept');
				$sql->adInserir('arquivo_pasta_dept_pasta', $this->arquivo_pasta_id);
				$sql->adInserir('arquivo_pasta_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('arquivo_pasta_cia');
			$sql->adOnde('arquivo_pasta_cia_pasta='.(int)$this->arquivo_pasta_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'arquivo_pasta_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('arquivo_pasta_cia');
						$sql->adInserir('arquivo_pasta_cia_pasta', $this->arquivo_pasta_id);
						$sql->adInserir('arquivo_pasta_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}


		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('arquivo_pasta_gestao');
			$sql->adAtualizar('arquivo_pasta_gestao_pasta', (int)$this->arquivo_pasta_id);
			$sql->adAtualizar('arquivo_pasta_gestao_uuid', null);
			$sql->adOnde('arquivo_pasta_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	function check() {
		return null;
		}
	function excluir($oid = null) {
		$k = $this->_chave_tabela;
		if ($oid) $this->$k = intval($oid);
		if (!$this->podeExcluir($msg, ($oid ? $oid : $this->arquivo_pasta_id)))	return $msg;
		$this->$k = $this->$k ? $this->$k : intval(($oid ? $oid : $this->arquivo_pasta_id));
		$q = new BDConsulta();
		$q->setExcluir($this->_tbl);
		$q->adOnde($this->_chave_tabela.' = '.$this->$k);
		if (!$q->exec()) {
			$q->limpar();
			return db_error();
			}
		else {
			$q->limpar();
			return null;
			}
		}
	function podeExcluir(&$msg='', $oid = null, $unioes = null) {
		global $Aplic;
		$q = new BDConsulta();
		$q->adTabela('arquivo_pasta');
		$q->adCampo('COUNT(DISTINCT arquivo_pasta_id) AS num_de_subpastas');
		$q->adOnde('arquivo_pasta_superior='.(int)$oid);
		$res1 = $q->Resultado();
		$q->limpar();
		$q = new BDConsulta();
		$q->adTabela('arquivos');
		$q->adCampo('COUNT(DISTINCT arquivo_id) AS num_of_files');
		$q->adOnde('arquivo_pasta='.(int)$oid);
		$res2 = $q->Resultado();
		$q->limpar();
		if (($res1 > 0) || ($res2 > 0)) {
			$msg[] = 'Pasta de Arquivo';
			$msg = 'Não é possível excluir a Pasta, pois a mesma tem arquivos e/ou subpastas: '.implode(', ', $msg);
			return false;
			}
		return true;
		}
	function getNomePastaSuperior() {
		$q = new BDConsulta();
		$q->adTabela('arquivo_pasta');
		$q->adCampo('arquivo_pasta_nome');
		$q->adOnde('arquivo_pasta_id='.$this->arquivo_pasta_superior);
		return $q->Resultado();
		}
	function contarPastas() {
		$q = new BDConsulta();
		$q->adTabela($this->_tbl);
		$q->adCampo('COUNT('.$this->_chave_tabela. ' )');
		$resultado = $q->Resultado();
		return $resultado;
		}


	function podeAcessar() {
		global $Aplic;
		$valor=permiteAcessarPasta($this->arquivo_pasta_acesso, $this->arquivo_pasta_id);
		return $valor;
		}

	function podeEditar() {
		$valor=permiteEditarPasta($this->arquivo_pasta_acesso, $this->arquivo_pasta_id);
		return $valor;
		}



	}

function arquivo_tamanho($size) {
	if ($size > 1024 * 1024 * 1024)	return round($size / 1024 / 1024 / 1024, 2).' Gb';
	if ($size > 1024 * 1024) return round($size / 1024 / 1024, 2).' Mb';
	if ($size > 1024)	return round($size / 1024, 2).' Kb';
	return $size.' B';
	}

function ultimo_arquivo($arquivo_versoes, $arquivo_nome, $arquivo_projeto) {
	$ultimo = null;
	if (isset($arquivo_versoes)) foreach ($arquivo_versoes as $arquivo_versao){
			if (($arquivo_versao['arquivo_nome'] == $arquivo_nome && $arquivo_versao['arquivo_projeto'] == $arquivo_projeto) && ($ultimo == null || $ultimo['arquivo_versao'] < $arquivo_versao['arquivo_versao']))	$ultimo = $arquivo_versao;
			}
	return $ultimo;
	}

function getIcone($arquivo_tipo) {
	global $config, $estilo_ui;
	$resultado = '';
	$mime = str_replace('/', '-', $arquivo_tipo);
	$icone = 'gnome-mime-'.$mime;
	if (is_file(BASE_DIR.'/styles/rondon/imagens/icones/'.$icone.'.png')) $resultado = 'icones/'.$icone.'.png';
	else {
		$mime = explode('/', $arquivo_tipo);
		switch ($mime[0]) {
			case 'audio':
				$resultado = 'icones/gnome-mime-audio-x-wav.png';
				break;
			case 'image':
				$resultado = 'icones/gnome-mime-image.png';
				break;
			case 'text':
				$resultado = 'icones/gnome-mime-text-x-txt.png';
				break;
			case 'video':
				$resultado = 'icones/gnome-mime-video.png';
				break;
			}
		if ($mime[0] == 'aplicacao') {
			switch ($mime[1]) {
				case 'vnd.ms-excel':
					$resultado = 'icones/gnome-mime-application-x-applix-spreadsheet.png';
					break;
				case 'vnd.ms-powerpoint':
					$resultado = 'icones/gnome-mime-video-quicktime.png';
					break;
				case 'octet-stream':
					$resultado = 'icones/fonte_c.png';
					break;
				default:
					$resultado = 'icones/gnome-mime-application-msword.png';
				}
			}
		}
	if ($resultado == '') $resultado = 'icones/desconhecido.png';

	return $resultado;
	}
function getPastaListaSelecao() {
	//ERRO corrigir depois
	global $Aplic, $projeto_id, $pratica_id, $demanda_id, $instrumento_id, $pratica_indicador_id, $arquivo_usuario, $pg_objetivo_estrategico_id, $tema_id, $pg_perspectiva_id, $canvas_id, $pg_estrategia_id, $plano_acao_id, $pg_fator_critico_id, $pg_meta_id, $calendario_id;
	$q = new BDConsulta();
	$q->adTabela('arquivo_pasta');
	$q->adCampo('arquivo_pasta_id, arquivo_pasta_nome, arquivo_pasta_superior');
	if ($projeto_id) $q->adOnde('arquivo_pasta_projeto='.$projeto_id);
	if ($pratica_id) $q->adOnde('arquivo_pasta_pratica='.$pratica_id);
	if ($demanda_id) $q->adOnde('arquivo_pasta_demanda='.$demanda_id);
	if ($instrumento_id) $q->adOnde('arquivo_pasta_instrumento='.$instrumento_id);
	if ($plano_acao_id) $q->adOnde('arquivo_pasta_acao='.$plano_acao_id);
	if ($pratica_indicador_id) $q->adOnde('arquivo_pasta_indicador='.$pratica_indicador_id);
	if ($arquivo_usuario) $q->adOnde('arquivo_pasta_usuario='.$arquivo_usuario);
	if ($pg_perspectiva_id) $q->adOnde('arquivo_pasta_perspectiva='.$pg_perspectiva_id);
	if ($canvas_id) $q->adOnde('arquivo_pasta_canvas='.$canvas_id);
	if ($tema_id) $q->adOnde('arquivo_pasta_tema='.$tema_id);
	if ($pg_objetivo_estrategico_id) $q->adOnde('arquivo_pasta_objetivo='.$pg_objetivo_estrategico_id);
	if ($pg_estrategia_id) $q->adOnde('arquivo_pasta_estrategia='.$pg_estrategia_id);
	if ($pg_fator_critico_id) $q->adOnde('arquivo_pasta_fator='.$pg_fator_critico_id);
	if ($pg_meta_id) $q->adOnde('arquivo_pasta_meta='.$pg_meta_id);
	if ($calendario_id) $q->adOnde('arquivo_pasta_calendario='.$calendario_id);
	$q->adOrdem('arquivo_pasta_nome');
	$pastas = unirVetores(array(0 => array(null, "Raiz", 0)), $q->ListaChave('arquivo_pasta_id'));
	return $pastas;
	}
?>