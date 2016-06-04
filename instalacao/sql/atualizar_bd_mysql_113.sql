SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.28'; 
UPDATE versao SET ultima_atualizacao_bd='2012-07-09'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-07-09'; 
UPDATE versao SET versao_bd=113;


UPDATE tarefas SET tarefa_numeracao=NULL;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW v_lista_id_msg_arquivadas
AS
select 
    msg_usuario.para_id AS para_id,
    msg.msg_id AS msg_id,
    max(msg_usuario.msg_usuario_id) AS msg_usuario_id 
  from 
    ((msg_usuario left join msg on((msg.msg_id = msg_usuario.msg_id))) left join usuarios on((usuarios.usuario_id = msg_usuario.de_id))) 
  where 
    (msg_usuario.status = 4) 
  group by 
    msg.msg_id,msg_usuario.para_id 
  order by 
    msg_usuario.para_id,msg.msg_id desc;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW v_lista_id_msg_enviadas
AS
select 
    msg_usuario.de_id AS de_id,
    msg.msg_id AS msg_id,
    max(msg_usuario.msg_usuario_id) AS msg_usuario_id 
  from 
    ((msg_usuario left join msg on((msg.msg_id = msg_usuario.msg_id))) left join usuarios on((usuarios.usuario_id = msg_usuario.de_id))) 
  where 
    (msg_usuario.de_id = 1) 
  group by 
    msg.msg_id,msg_usuario.de_id;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW v_lista_id_msg_pendentes
AS
select 
    msg_usuario.para_id AS para_id,
    msg.msg_id AS msg_id,
    max(msg_usuario.msg_usuario_id) AS msg_usuario_id 
  from 
    ((msg_usuario left join msg on((msg.msg_id = msg_usuario.msg_id))) left join usuarios on((usuarios.usuario_id = msg_usuario.de_id))) 
  where 
    (msg_usuario.status = 3) 
  group by 
    msg.msg_id,msg_usuario.para_id 
  order by 
    msg_usuario.para_id,msg.msg_id;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW v_lista_id_msg_recebidas
AS
select 
    msg_usuario.para_id AS para_id,
    msg.msg_id AS msg_id,
    max(msg_usuario.msg_usuario_id) AS msg_usuario_id 
  from 
    ((msg_usuario left join msg on((msg.msg_id = msg_usuario.msg_id))) left join usuarios on((usuarios.usuario_id = msg_usuario.de_id))) 
  group by 
    msg.msg_id,msg_usuario.para_id 
  order by 
    msg_usuario.para_id,msg.msg_id;
