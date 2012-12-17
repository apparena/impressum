/**
 * Contains all relevant js functions to spread information on facebook
 * User: sbuckpesch
 * Date: 25.10.12
 * Time: 09:01
 */

/**
 * App-Arenas method for the best facebook sharing experience
 * @param name
 * @param message
 * @param link
 * @param picture
 */
function aa_share( name, message, link, picture ) {

    // 1. Check the environment for the sharing modal

    // 2. If user is authenticated, then we can use the friend selector

}


/**
 *
 * @param name
 * @param link
 * @param display Can be: page, popup, iframe, or touch, @see https://developers.facebook.com/docs/reference/dialogs/#display
 * @param callback
 */
function fb_send( name, message, link, picture, redirect_uri, to, display, callback ) {
    if ( typeof( display ) == 'undefined' ) {
        display = "page";
    }

    FB.ui({
        method:'send',
        name: name,
        description: message,
        link: link,
        picture: picture,
        redirect_uri: redirect_uri,
        to: to,
        display: display
    }, callback);
}

/**
 * Opens a popup with a send dialog. Easy to use...
 * @param message Main message of the send dialog
 * @param redirect_url Url behind the title of the post (User will be redirected to this url, when he clicks on the title)
 * @param link
 * @param title Title of the send dialog popup
 */
function fb_get_send_url( message, redirect_url, link, popup_title ) {
    if ( typeof( popup_title ) == 'undefined' ) {
        popup_title = "Share";
    }
    var url = 'https://www.facebook.com/dialog/send' +
        '?app_id=' + fb_app_id +
        '&message=' + message +
        '&link=' + link +
        '&redirect_uri=' + redirect_url;
    return url;
}

/**
 * Opens the facebook multi friend selector dialog.
 * @param name Title of the request
 * @param message Message send to the user. The user will only see the message, if he authorized your facebook app before.
 * @param data Additional parameter, which can be passed with the request.
 * @param callback Callback function
 */
function fb_mfs( name, message, data, callback ) {
    // Use FB.ui to send the Request(s)
    FB.ui({method:'apprequests',
        title: name,
        message: message,
        data:data
    }, callback);
}

/**
 * Opens a Multifriend-Selector Dialog popup
 * @param message Message send to the user. The user will only see the message, if he authorized your facebook app before.
 * @param redirect_url Url the request receiver will be redirected to, if he accepts the request
 * @param popup_title Title of the multi friend selector popup
 */
function fb_get_mfs_url( message, redirect_url, popup_title) {
    if ( typeof( popup_title ) == 'undefined' ) {
        popup_title = "Apprequest";
    }
    var url = 'https://www.facebook.com/dialog/apprequests' +
        '?app_id=' + fb_app_id +
        '&message=' + message +
        '&redirect_uri=' + redirect_url;
    openPopup( url, popup_title );
}

/**
 * Facebook Share dialog
 * @param name Title of the sharing message
 * @param message Main message fof the sharing dialog
 * @param link Link the receiver will be redirected to, when he clicks on the title or picture of the shared message
 * @param picture Url of pictures shared with the message
 * @param caption Subtitle for the sharing message
 * @param callback Callback function
 */
function fb_share( name, message, link, picture, caption, callback) {
    var obj = {
        method:'feed',
        name: $( '#name' ).val(),
        description: $( '#message' ).val(),
        link: $( '#link' ).val(),
        picture: $( '#picture' ).val(),
        caption: $( '#caption' ).val()
    };
    FB.ui(obj, callback);
}

/**
 * Opens a facebook sharing dialog popup
 * @param name Title of the sharing message
 * @param message Main message fof the sharing dialog
 * @param redirect_url The url the sender will be redirected to after sending the message
 * @param link Link the receiver will be redirected to, when he clicks on the title or picture of the shared message
 * @param picture Url of pictures shared with the message
 * @param caption Subtitle for the sharing message
 * @param popup_title Sharing dialog popup title
 */
function fb_get_share_url( name, message, redirect_url, link, picture, caption, popup_title) {
    if ( typeof( popup_title ) == 'undefined' ) {
        popup_title = "Share";
    }
    var url = 'https://www.facebook.com/dialog/feed' +
        '?app_id=' + fb_app_id +
        '&link=' + link +
        '&picture=' + picture +
        '&name=' + name +
        '&caption=' + caption +
        '&description=' + message +
        '&redirect_uri=' + redirect_url;
    openPopup( url, popup_title );
}

/**
 * Special facebook share popup, which enables the user as well to post to a friends/pages timeline
 * @param name Title of the shared message
 * @param message Main message to share
 * @param link Link the user will be redirected to, when we clicks on the title or picture in the shared message
 * @param picture Url of a picture shared with this message
 * @param popup_title Title of the popup window
 */
function fb_get_sharer_url( name, message, link, picture, popup_title ) {
    if ( typeof( popup_title ) == 'undefined' ) {
        popup_title = "Share";
    }
    var url='http://www.facebook.com/sharer.php?s=100&amp;p[title]='+urlencode(name);
    url+='&amp;p[summary]='+urlencode(message);
    url+='&amp;p[url]='+urlencode(link);
    url+='&amp;&amp;p[images][0]='+urlencode(picture);
    openPopup( url, popup_title );
}

function urlencode(str) {
    str = (str + '').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function urldecode(str) {
    return decodeURIComponent((str + '').replace(/\+/g, '%20'));
}

function openPopup( url, name ) {
    popup = window.open( url, name, 'target=_blank,width=820,height=800' );
    if ( window.focus ) {
        popup.focus();
    }
    return false;
}