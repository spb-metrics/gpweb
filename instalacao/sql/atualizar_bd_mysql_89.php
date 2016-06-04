<?php
mysql_query("DROP FUNCTION IF EXISTS concatenar_dois;");

mysql_query("CREATE FUNCTION concatenar_dois(t1 varchar(255), t2 varchar(255))
RETURNS text
DETERMINISTIC
BEGIN
RETURN CONCAT(IF(ISNULL(t1),'',t1), IF(ISNULL(t2),'',t2));
END;");

mysql_query("DROP FUNCTION IF EXISTS concatenar_tres;");

mysql_query("CREATE FUNCTION concatenar_tres(t1 varchar(255), t2 varchar(255), t3 varchar(255))
RETURNS text
DETERMINISTIC
BEGIN
RETURN CONCAT(IF(ISNULL(t1),'',t1), IF(ISNULL(t2),'',t2), IF(ISNULL(t3),'',t3));
END;");

mysql_query("DROP FUNCTION IF EXISTS concatenar_quatro;");

mysql_query("CREATE FUNCTION concatenar_quatro(t1 varchar(255), t2 varchar(255), t3 varchar(255), t4 varchar(255))
RETURNS text
DETERMINISTIC
BEGIN
RETURN CONCAT(IF(ISNULL(t1),'',t1), IF(ISNULL(t2),'',t2), IF(ISNULL(t3),'',t3), IF(ISNULL(t4),'',t4));
END;");

mysql_query("DROP FUNCTION IF EXISTS concatenar_cinco;");

mysql_query("CREATE FUNCTION concatenar_cinco(t1 varchar(255), t2 varchar(255), t3 varchar(255), t4 varchar(255), t5 varchar(255))
RETURNS text
DETERMINISTIC
BEGIN
RETURN CONCAT(IF(ISNULL(t1),'',t1), IF(ISNULL(t2),'',t2), IF(ISNULL(t3),'',t3), IF(ISNULL(t4),'',t4), IF(ISNULL(t5),'',t5));
END;");


?>
