/**
 * Author: Guntram Pollock & Sebastian Buckpesch
 */

/**
 * Loads a new template file into the div#main container using jquery animations
 * @param tmpl_filename Filename of the template
 */
function aa_tmpl_load(tmpl_filename, data) {
    show_loading(); // show the loading screen
    if ( typeof( data ) == 'undefined' ) {
    	data = '';
    } else {
    	data = '&' + data;
    }
    var url = "templates/" + tmpl_filename + "?aa_inst_id=" + aa.inst.aa_inst_id + data;
    $("#main").slideUp(0, function () {
        $("#main").load( url, function () {
            $("#main").slideDown(600, function () {
                FB.Canvas.scrollTo(0, 0);
                hide_loading(); // hide the loading screen
            });
        });
    });
}

function open_popup( url, name ) {
    popup = window.open( url, name, 'target=_blank,width=820,height=800' );
    if ( window.focus ) {
        popup.focus();
    }
    return false;
}

function setAdminIntroCookie() {
    if ($('#admin-intro').is(':checked')) {
        setCookie('admin_intro_' + aa_inst_id, true);
    } else {
        setCookie('admin_intro_' + aa_inst_id, false);
    }
}

function show_admin_info() {
    $('#admin_modal').modal("show");
}

function urlencode(str){str=(str+'').toString();return encodeURIComponent(str).replace(/!/g,'%21').replace(/'/g,'%27').replace(/\(/g,'%28').replace(/\)/g,'%29').replace(/\*/g,'%2A').replace(/%20/g,'+');}