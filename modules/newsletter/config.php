<?php
//SMTP Setup
$smtp_config = array("smtp_host" => "smtp.mandrillapp.com",
					"smtp_user" => "s.buckpesch@iconsultants.eu",
					"smtp_pass" => "9e790d25-48c9-42cf-a52c-eec97827b2b8",
					"smtp_port" => "587"
					);

// Sender settings
$sender = array("sender_name" 	=> "App-Arena.com Developer",
				"smtp_user" 	=> "info@app-arena.com",
);

// Default email settings
$email = array("subject" 	=> "Test Newsletter Registration",
				"body" 		=> "Thank you {{name}}. Confirm your registration here: {{confirmation_link}}",
);

?>