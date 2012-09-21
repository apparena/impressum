<?php 
include_once ( '../init.php' );

global $db; // Access existing connection
$result = $db->query("SELECT * FROM 'app_participant' WHERE 1");
?>
<h1>Database demo</h1>
<h2>Simple Query</h2>
<pre>SELECT * FROM 'app_participant' WHERE 1</pre>
<p>
	Result:<br />
	<pre>
	<?php var_dump($result); ?>
	</pre>
</p>
