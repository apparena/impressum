<?php
require_once(dirname(__FILE__).'/../../init.php');

// Check if user is Admin
if (is_fb_user_admin()){
?>
<div id="admin_panel">
		<div class="configure-app">
            <a class="app_config" href="/manager/index.php/wizard/instance_id/<?=$aa['instance']["aa_inst_id"];?>/'" target="_blank"><button class="btn"><i class="icon-cog"></i> <?php __p("Configure_App")?></button></a>
		</div>
</div>
	
	<script type="text/javascript">
	function popup (url) {
	 fenster = window.open(url, "Admin panel", "width=900,height=650,resizable=no");
	 fenster.focus();
	 return false;
	}
	
	</script>

<?php 
}
?>
