=============================
Usage instruction
=============================
Module: Newsletter

Requirements:
-----------------------------
App-Manager:
In the App-Manager you need to add the following config-values to make this work:
- nl_sender_name	[text]
- nl_sender_email	[text]
- nl_subject		[text]
- nl_text 			[html]

Email Content (nl_text):
This html content element needs should contain these variables, to print a confirmation link. Without these variables a double opt in method is not possible.
Variables:
{{confirmation_link}}	-	This will be replaced with the confirmation link
{{name}}				-	This will be replaced with the user name


Translation:
confirm_newsletter_registration		-	Confirm newsletter registration