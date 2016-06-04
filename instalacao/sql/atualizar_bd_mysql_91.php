<?php
mysql_query("DROP FUNCTION IF EXISTS diferenca_data;");

mysql_query("CREATE FUNCTION diferenca_data(t1 datetime, t2 datetime)
RETURNS integer(10)
DETERMINISTIC
BEGIN
RETURN DATEDIFF(t1, t2);
END;");
?>
