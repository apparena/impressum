<?php 
include_once ( '../init.php' );
?>
<h1>Database demo</h1>
<p>Our app template is using the <a href="http://framework.zend.com/manual/1.12/en/zend.db.adapter.html" target="_blank">Zend-Framework to connect to a database</a>. Here are some basic examples, how to perform queries on your database.</p>
<h2>Basic setup</h2>
<p>Just enter your database data in <strong>config.php</strong> and set $db_activated to 'true' to use it.</p>
<h2>Simple Query</h2>
<pre>
$sql 	= "SELECT * FROM 'app_participant' WHERE 1";
$result = $db->fetchAll($sql);
</pre>
Result:<br />
<pre>
<?php 
$sql 	= "SELECT * FROM 'app_participant' WHERE 1";
$result = $db->fetchAll($sql);
var_dump($result);
?>
</pre>