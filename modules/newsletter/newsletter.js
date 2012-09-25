/**
 * All functionality to send out a newsletter with double opt in 
 */

/**
 * This functions send out the confirmation email
 */
function send_newsletter(aa_inst_id, email, name) {
	var data = {
		receiver_email : email,
		receiver_name : name
	};

	var url = "modules/newsletter/send_newsletter_confirmation.php?aa_inst_id=" + aa_inst_id;
	jQuery.post(url, data, function(response) {
		if (response.error == 0) {
			//callback;
		} else {
			//error
			//alert(response.error_msg);
		}
	}, 'json');
}