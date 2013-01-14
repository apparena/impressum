/**
 * Post an action to the graph.
 * The namespace and the object name are needed here!
 * @param string namespace The canvas page namespace. If the canvas-url ends
 *                            like ".../my-cool-page" the namespace is "my-cool-page".
 * @param string object_name The name of the object. The object name is defined in
 *                              the open graph config.
 *
 * fb-example:
 * https://graph.facebook.com/me/[YOUR_APP_NAMESPACE]:cook?recipe=OBJECT_URL&access_token=ACCESS_TOKEN
 */
function postToGraph(namespace, object_name, action, object_url, callback) {

    //if ( typeof( action ) == 'undefined' || action.length < 0 ) {
    // handle missing action?!
    //}

    /*
     * THIS IS WORKING WITH FIXED VALUES!!
     FB.api(
     '/me/advents--kalender:open',
     'post',
     { door: 'https://www.app-arena.com/app/iconsultants/Adventskalender/v20_dev/modules/open_graph/door_test.php' },
     function( response ) {
     if ( !response || response.error ) {
     var action_id = false;
     } else {
     var action_id = response.id;
     }
     if ( typeof( callback ) == 'function' ) {
     callback();
     }
     }
     );
     */

    var object_param = {};
    object_param[ object_name ] = object_url;

    FB.api(
        '/me/' + namespace + ':' + action,
        'post',
        object_param,
        function (response) {
            if (!response || response.error) {
                var action_id = false;
            } else {
                var action_id = response.id;
            }
            $('#__cook').append('graph post response from dynamic attr values: ' + action_id + '<br />');

            if (typeof( callback ) == 'function') {
                callback();
            }
        }
    );

}

function open_graph_post(action, object, aa_inst_id, fb_app_url, fb_canvas_url, answer, callback) {

    var mObject = {};
    mObject[object] = 'https://apps.facebook.com/' + fb_app_url + '/modules/open_graph/object.php?aa_inst_id=' + aa_inst_id + '&answer=' + answer;
    mObject['aa_inst_id'] = aa_inst_id;
    mObject['answer'] = answer;
    mObject['object'] = object;
    FB.api(
        '/me/' + fb_app_url + ':' + action,
        'post', mObject,
        function (response) {
            if (!response || response.error) {
                var action_id = false;
            } else {
                var action_id = response.id;
            }
            if (typeof( callback ) == 'function') {
                callback(action_id);
            }
        }
    );

}