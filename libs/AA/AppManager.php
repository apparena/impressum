<?php
// SOAP Client

class AA_AppManager {
	private $fb_page_id;
	
	
	function __construct(Array $params) {
		if (!isset($params['aa_inst_id']) || !$params['aa_inst_id'])
			$this->fb_page_id = $this->getFbPageId();
	}
	
	
	function getFbPageId() {
		// Try to get fb_page_id from signed request
		
		;
	}

}