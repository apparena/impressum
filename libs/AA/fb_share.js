/**
 * Contains all relevant js functions to spread information on facebook
 * User: sbuckpesch
 * Date: 25.10.12
 * Time: 09:01
 */

function fb_multi_friend_selector( request_callback ) {
    FB.ui({method: 'apprequests',
        message: 			'This is your share message',
        redirect_uri :	'<?php echo $aa['fb']['share_url']; ?>',
        data:				'<?php echo $aa['instance']['aa_inst_id']; ?>'
    }, request_callback);
}

