=============================
Usage instruction
=============================
Module: Newsletter

Requirements:
-----------------------------
1. App-Manager:
In the App-Manager you need to add the following config-values to make this work:
- nl_sender_name	[text]
- nl_sender_email	[text]
- nl_subject		[text]
- nl_text 			[html]

2. DB connection:
The database connection can be initialized via "global $db"

3. Email Content (nl_text):
This html content element needs should contain these variables, to print a confirmation link. Without these variables a double opt in method is not possible.
Variables:
{{confirmation_link}}	-	This will be replaced with the confirmation link
{{name}}				-	This will be replaced with the user name


4. Translation:
confirm_newsletter_registration		-	Confirm newsletter registration