UPDATE versao SET versao_codigo='7.7.9'; 
UPDATE versao SET versao_bd=62;

ALTER TABLE baseline_tarefa_dependencias MODIFY tipo_dependencia varchar(3) DEFAULT 'TI';
ALTER TABLE tarefa_dependencias MODIFY tipo_dependencia varchar(3) DEFAULT 'TI';


UPDATE tarefa_dependencias SET tipo_dependencia='TI' WHERE tipo_dependencia='f-i';
UPDATE tarefa_dependencias SET tipo_dependencia='TT' WHERE tipo_dependencia='f-f';
UPDATE tarefa_dependencias SET tipo_dependencia='II' WHERE tipo_dependencia='i-i';
UPDATE tarefa_dependencias SET tipo_dependencia='IT' WHERE tipo_dependencia='i-f';