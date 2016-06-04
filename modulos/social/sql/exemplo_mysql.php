<?php

global $bd;

$q = new BDConsulta;

$projeto=array();

$sql="INSERT INTO projetos (projeto_cia, projeto_responsavel, projeto_criador, projeto_supervisor, projeto_autoridade, projeto_atualizador, projeto_tema, projeto_objetivo_estrategico, projeto_estrategia, projeto_indicador, projeto_meta, projeto_fator, projeto_pratica, projeto_acao, projeto_nome, projeto_nome_curto, projeto_codigo, projeto_sequencial, projeto_url, projeto_url_externa, projeto_data_inicio, projeto_data_fim, projeto_fim_atualizado, projeto_status, projeto_percentagem, projeto_custo, projeto_gasto, projeto_cor, projeto_descricao, projeto_objetivos, projeto_como, projeto_localizacao, projeto_meta_custo, projeto_custo_atual, projeto_privativo, projeto_prioridade, projeto_tipo, projeto_data_chave, projeto_data_chave_pos, projeto_tarefa_chave, projeto_ativo, projeto_especial, projeto_criado, projeto_atualizado, projeto_data_fim_ajustada, projeto_status_comentario, projeto_subprioridade, projeto_data_fim_ajustada_usuario, projeto_acesso, projeto_endereco1, projeto_endereco2, projeto_cidade, projeto_estado, projeto_cep, projeto_pais, projeto_latitude, projeto_longitude, projeto_setor, projeto_segmento, projeto_intervencao, projeto_tipo_intervencao, projeto_ano, projeto_portfolio, projeto_comunidade, projeto_social, projeto_social_acao) VALUES 
  (1,6,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cisterna Familiar - João Pessoa',NULL,NULL,2,NULL,NULL,'2012-02-21 00:00:00','2012-02-21 23:59:59',NULL,1,50.000,NULL,NULL,'ffffeb',NULL,NULL,NULL,NULL,0.000,0.000,0,0,0,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,'2507507','PB',NULL,'BR',NULL,NULL,'01','0103','010301','010301001','2012',0,NULL,1,1);";
$q->executarScript($sql);
$q->exec();
$q->limpar();
$projeto['2'] = $bd->Insert_ID('projetos','projeto_id');


$sql="UPDATE projetos SET projeto_superior=".$projeto['2'].", projeto_superior_original=".$projeto['2']." WHERE projeto_nome='Cisterna Familiar - João Pessoa';";
$q->executarScript($sql);
$q->exec();
$q->limpar();


$sql="INSERT INTO projetos (projeto_cia, projeto_responsavel, projeto_criador, projeto_supervisor, projeto_autoridade, projeto_atualizador, projeto_tema, projeto_objetivo_estrategico, projeto_estrategia, projeto_indicador, projeto_meta, projeto_fator, projeto_pratica, projeto_acao, projeto_nome, projeto_nome_curto, projeto_codigo, projeto_sequencial, projeto_url, projeto_url_externa, projeto_data_inicio, projeto_data_fim, projeto_fim_atualizado, projeto_status, projeto_percentagem, projeto_custo, projeto_gasto, projeto_cor, projeto_descricao, projeto_objetivos, projeto_como, projeto_localizacao, projeto_meta_custo, projeto_custo_atual, projeto_privativo, projeto_prioridade, projeto_tipo, projeto_data_chave, projeto_data_chave_pos, projeto_tarefa_chave, projeto_ativo, projeto_especial, projeto_criado, projeto_atualizado, projeto_data_fim_ajustada, projeto_status_comentario, projeto_subprioridade, projeto_data_fim_ajustada_usuario, projeto_acesso, projeto_endereco1, projeto_endereco2, projeto_cidade, projeto_estado, projeto_cep, projeto_pais, projeto_latitude, projeto_longitude, projeto_setor, projeto_segmento, projeto_intervencao, projeto_tipo_intervencao, projeto_ano, projeto_portfolio, projeto_comunidade, projeto_social, projeto_social_acao) VALUES 
  (1,7,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cisterna Familiar - Campina Grande',NULL,NULL,3,NULL,NULL,'2012-02-21 00:00:00','2012-03-09 23:59:59',NULL,1,20.000,NULL,NULL,'ffe0e0',NULL,NULL,NULL,NULL,0.000,0.000,0,0,0,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,'2504009','PB',NULL,'BR',NULL,NULL,'01','0103','010301','010301001','2012',0,NULL,1,1);";
$q->executarScript($sql);
$q->exec();
$q->limpar();
$projeto['3'] = $bd->Insert_ID('projetos','projeto_id');

$sql="UPDATE projetos SET projeto_superior=".$projeto['3'].", projeto_superior_original=".$projeto['3']." WHERE projeto_nome='Cisterna Familiar - Campina Grande';";
$q->executarScript($sql);
$q->exec();
$q->limpar();

$sql="INSERT INTO projetos (projeto_cia, projeto_responsavel, projeto_criador, projeto_supervisor, projeto_autoridade, projeto_atualizador, projeto_tema, projeto_objetivo_estrategico, projeto_estrategia, projeto_indicador, projeto_meta, projeto_fator, projeto_pratica, projeto_acao, projeto_nome, projeto_nome_curto, projeto_codigo, projeto_sequencial, projeto_url, projeto_url_externa, projeto_data_inicio, projeto_data_fim, projeto_fim_atualizado, projeto_status, projeto_percentagem, projeto_custo, projeto_gasto, projeto_cor, projeto_descricao, projeto_objetivos, projeto_como, projeto_localizacao, projeto_meta_custo, projeto_custo_atual, projeto_privativo, projeto_prioridade, projeto_tipo, projeto_data_chave, projeto_data_chave_pos, projeto_tarefa_chave, projeto_ativo, projeto_especial, projeto_criado, projeto_atualizado, projeto_data_fim_ajustada, projeto_status_comentario, projeto_subprioridade, projeto_data_fim_ajustada_usuario, projeto_acesso, projeto_endereco1, projeto_endereco2, projeto_cidade, projeto_estado, projeto_cep, projeto_pais, projeto_latitude, projeto_longitude, projeto_setor, projeto_segmento, projeto_intervencao, projeto_tipo_intervencao, projeto_ano, projeto_portfolio, projeto_comunidade, projeto_social, projeto_social_acao) VALUES 
  (1,2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cisterna Familiar - Paraíba',NULL,NULL,4,NULL,NULL,'2012-02-21 00:00:00','2012-03-11 23:59:59',NULL,1,0.000,NULL,NULL,'cdfedb',NULL,NULL,NULL,NULL,0.000,0.000,0,0,0,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'PB',NULL,'BR',NULL,NULL,'01','0103','010301','010301001','2012',1,NULL,NULL,NULL);";
$q->executarScript($sql);
$q->exec();
$q->limpar();
$projeto['4'] = $bd->Insert_ID('projetos','projeto_id');

$sql="UPDATE projetos SET projeto_superior=".$projeto['4'].", projeto_superior_original=".$projeto['4']." WHERE projeto_nome='Cisterna Familiar - Paraíba';";
$q->executarScript($sql);
$q->exec();
$q->limpar();

$sql='INSERT INTO projeto_portfolio (projeto_portfolio_pai, projeto_portfolio_filho, projeto_portfolio_ordem) VALUES
('.$projeto['4'].', '.$projeto['2'].', 2),
('.$projeto['4'].', '.$projeto['3'].', 1);';
$q->executarScript($sql);
$q->exec();
$q->limpar();



$tarefa=array();
$sql="INSERT INTO tarefas (tarefa_projeto, tarefa_duracao, tarefa_cia, tarefa_dono, tarefa_criador, tarefa_comunidade, tarefa_social, tarefa_acao, tarefa_nome, tarefa_marco, tarefa_inicio, tarefa_inicio_calculado, tarefa_duracao_tipo, tarefa_horas_trabalhadas, tarefa_fim, tarefa_status, tarefa_prioridade, tarefa_percentagem, tarefa_custo, tarefa_gasto, tarefa_descricao, tarefa_onde, tarefa_porque, tarefa_como, tarefa_custo_almejado, tarefa_url_relacionada, tarefa_ordem, tarefa_cliente_publicada, tarefa_dinamica, tarefa_acesso, tarefa_notificar, tarefa_customizado, tarefa_tipo, tarefa_atualizador, tarefa_data_criada, tarefa_data_atualizada, tarefa_endereco1, tarefa_endereco2, tarefa_cidade, tarefa_estado, tarefa_cep, tarefa_pais, tarefa_latitude, tarefa_longitude, tarefa_emprego_obra, tarefa_emprego_direto, tarefa_emprego_indireto, tarefa_populacao_atendida, tarefa_forma_implantacao, tarefa_adquirido, tarefa_previsto, tarefa_realizado, tarefa_codigo, tarefa_sequencial, tarefa_unidade, tarefa_numeracao) VALUES
(".$projeto['2'].", '40.000', 1, 1, 1, 2, 1, 1, 'Implantar Cisterna - Roger', 0, '2012-02-21 08:00:00', 0, 1, '0.000', '2012-02-27 17:00:00', 0, 0, '0.000', '0.000', '0.000', NULL, NULL, NULL, NULL, '0.000', NULL, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2507507', 'PB', NULL, 'BR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20.000', '1.000', '0.000', NULL, 1, '1', 1);
";
$q->executarScript($sql);
$q->exec();
$q->limpar();
$tarefa['21'] = $bd->Insert_ID('tarefas','tarefa_id');


$sql="INSERT INTO tarefas (tarefa_projeto, tarefa_duracao, tarefa_cia, tarefa_dono, tarefa_criador, tarefa_comunidade, tarefa_social, tarefa_acao, tarefa_nome, tarefa_marco, tarefa_inicio, tarefa_inicio_calculado, tarefa_duracao_tipo, tarefa_horas_trabalhadas, tarefa_fim, tarefa_status, tarefa_prioridade, tarefa_percentagem, tarefa_custo, tarefa_gasto, tarefa_descricao, tarefa_onde, tarefa_porque, tarefa_como, tarefa_custo_almejado, tarefa_url_relacionada, tarefa_ordem, tarefa_cliente_publicada, tarefa_dinamica, tarefa_acesso, tarefa_notificar, tarefa_customizado, tarefa_tipo, tarefa_atualizador, tarefa_data_criada, tarefa_data_atualizada, tarefa_endereco1, tarefa_endereco2, tarefa_cidade, tarefa_estado, tarefa_cep, tarefa_pais, tarefa_latitude, tarefa_longitude, tarefa_emprego_obra, tarefa_emprego_direto, tarefa_emprego_indireto, tarefa_populacao_atendida, tarefa_forma_implantacao, tarefa_adquirido, tarefa_previsto, tarefa_realizado, tarefa_codigo, tarefa_sequencial, tarefa_unidade, tarefa_numeracao) VALUES
(".$projeto['2'].", '40.000', 1, 1, 1, 1, 1, 1, 'Implantar Cisterna - Treze de Maio', 0, '2012-02-27 17:00:00', 0, 1, '0.000', '2012-03-05 17:00:00', 0, 0, '100.000', '0.000', '0.000', NULL, NULL, NULL, NULL, '0.000', NULL, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2507507', 'PB', NULL, 'BR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '15.000', '1.000', '1.000', NULL, 2, '1', 2);
";
$q->executarScript($sql);
$q->exec();
$q->limpar();
$tarefa['22'] = $bd->Insert_ID('tarefas','tarefa_id');


$sql="INSERT INTO tarefas (tarefa_projeto, tarefa_duracao, tarefa_cia, tarefa_dono, tarefa_criador, tarefa_comunidade, tarefa_social, tarefa_acao, tarefa_nome, tarefa_marco, tarefa_inicio, tarefa_inicio_calculado, tarefa_duracao_tipo, tarefa_horas_trabalhadas, tarefa_fim, tarefa_status, tarefa_prioridade, tarefa_percentagem, tarefa_custo, tarefa_gasto, tarefa_descricao, tarefa_onde, tarefa_porque, tarefa_como, tarefa_custo_almejado, tarefa_url_relacionada, tarefa_ordem, tarefa_cliente_publicada, tarefa_dinamica, tarefa_acesso, tarefa_notificar, tarefa_customizado, tarefa_tipo, tarefa_atualizador, tarefa_data_criada, tarefa_data_atualizada, tarefa_endereco1, tarefa_endereco2, tarefa_cidade, tarefa_estado, tarefa_cep, tarefa_pais, tarefa_latitude, tarefa_longitude, tarefa_emprego_obra, tarefa_emprego_direto, tarefa_emprego_indireto, tarefa_populacao_atendida, tarefa_forma_implantacao, tarefa_adquirido, tarefa_previsto, tarefa_realizado, tarefa_codigo, tarefa_sequencial, tarefa_unidade, tarefa_numeracao) VALUES
(".$projeto['3'].", '40.000', 1, 1, 1, 3, 1, 1, 'Implantar Cisterna - São José', 0, '2012-02-24 08:00:00', 0, 1, '0.000', '2012-03-01 17:00:00', 0, 0, '20.000', '0.000', '0.000', NULL, NULL, NULL, NULL, '0.000', NULL, NULL, 0, 0, 0, NULL, NULL,  NULL, NULL, NULL, NULL, NULL, NULL, '2504009', 'PB', NULL, 'BR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '13.000', '5.000', '1.000', NULL, 1, '1', 3);
";
$q->executarScript($sql);
$q->exec();
$q->limpar();
$tarefa['23'] = $bd->Insert_ID('tarefas','tarefa_id');

$q->adTabela('tarefas');
$q->adAtualizar('tarefa_superior', $tarefa['21']);
$q->adOnde('tarefa_id='.(int)$tarefa['21']);
$q->exec();
$q->limpar();

$q->adTabela('tarefas');
$q->adAtualizar('tarefa_superior', $tarefa['22']);
$q->adOnde('tarefa_id='.(int)$tarefa['22']);
$q->exec();
$q->limpar();

$q->adTabela('tarefas');
$q->adAtualizar('tarefa_superior', $tarefa['23']);
$q->adOnde('tarefa_id='.(int)$tarefa['23']);
$q->exec();
$q->limpar();

$sql="INSERT INTO tarefa_dependencias (dependencias_tarefa_id, dependencias_req_tarefa_id, tipo_dependencia, latencia, tipo_latencia) VALUES
(".$tarefa['22'].", ".$tarefa['21'].", 'TI', 0, 'd');";
$q->executarScript($sql);
$q->exec();
$q->limpar();



?>