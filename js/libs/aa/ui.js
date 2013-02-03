/**
 * Loads a new modal into the div #modal container using jquery animations
 * @param template Filename of the template
 * @param data get Url Data.
 */
function modal(template, modal_config, data) {
    var modal_folder = "templates/modals/";
    $("#modal").load(modal_folder + template + "?aa_inst_id=" + aa_inst_id + data, function () {
        switch (auth_type) {
            case 'auth_facebook':
                if (typeof(modal_config.first_name) != 'undefined') {
                    $("#first_name").val(modal_config.first_name);
                }
                if (typeof(modal_config.last_name) != 'undefined') {
                    $("#last_name").val(modal_config.last_name);
                }
                if (typeof(modal_config.gender) != 'undefined') {
                    $("#gender").val(modal_config.gender);
                }
                if (typeof(modal_config.city) != 'undefined') {
                    $("#city").val(modal_config.city);
                }
                if (typeof(modal_config.email) != 'undefined') {
                    $("#key").val(modal_config.email);
                }
                break;

            case 'auth_none':
                break;
            default:
                break;
        }
        if (template.indexOf('modal_register') >= 0) {
            formMessages();
            $('#form_registration').validate($.register_bootstrap_form);
            $.validator.setDefaults($.register_bootstrap_form); // make sure that messages and rules are set and the functions are called
        }
        $("#modal_div").modal('show');
        // handle modal for opera
        if (navigator.appName == "Opera") {
            $("#modal_div").removeClass('fade').removeClass('hide');
        }

        FB.Canvas.getPageInfo(function (info) {
            $("#modal_div").css('top', info.scrollTop + 5 + 'px');
            //    	        console.log( 'pageInfo:' );
            //    	    	console.log( info );
            //    	        console.log( 'offsetTop: ' + info.offsetTop );
            //    	        console.log( 'scrollTop: ' + info.scrollTop );
        });
    });
}


/**
 * Disable all form fields, to progress data
 */
function disable_form() {
    $('body').find('input').each(function () {
        $(this).attr('disabled', 'disabled');
    });
    $('body').find('button').each(function () {
        $(this).attr('disabled', 'disabled');
    });
    $('body').find('select').each(function () {
        $(this).attr('disabled', 'disabled');
    });
}

/**
 * Enable all form fields, to go on with the user flow
 */
function enable_form() {
    $('body').find('input').each(function () {
        $(this).removeAttr('disabled');
    });
    $('body').find('button').each(function () {
        $(this).removeAttr('disabled');
    });
    $('body').find('select').each(function () {
        $(this).removeAttr('disabled');
    });
}

/**
 * Show a message in the top msg-container (which is hidden usually)
 * @param msg the message to display
 * @param type one of: 'error', 'alert', 'success' or 'info' - determines the classing of the msg-div-element.
 * @param delay number of milisecons until the message box disappears
 */
function notify(msg, type, delay) {
    if (typeof( type ) !== 'string') {
        type = 'error';
    }
    if (typeof( msg ) !== 'string') {
        msg = __e('something_went_wrong');
    }
    var classes = 'alert fade in';
    switch (type) {

        case 'error':
            classes = 'alert alert-error fade in';
            break;

        case 'success':
            classes = 'alert alert-success fade in';
            break;

        case 'info':
            classes = 'alert alert-info fade in';
            break;

        default:
            classes = 'alert alert-block fade in';
            break;
    }
    $('#msg-container').slideUp(500, function () {
        $('#msg-container').alert();
        $('#msg-container').removeClass().addClass(classes).html(msg).slideDown(500).delay(delay).fadeOut('slow');
    });
}