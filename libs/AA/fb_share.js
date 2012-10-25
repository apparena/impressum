/**
 * Contains all relevant js functions to spread information on facebook
 * User: sbuckpesch
 * Date: 25.10.12
 * Time: 09:01
 */

function fb_multi_friend_selector( params, request_callback ) {
    FB.ui({ method:         'apprequests',
            message:        params.desc,
            redirect_uri:   params.share_url,
            data:           params.aa_inst_id
    }, request_callback);
}

function fb_share( params ) {
    var url='http://www.facebook.com/sharer.php?s=100&amp;p[title]='+urlencode(params.title);
    url+='&amp;p[summary]='+urlencode(params.desc);
    url+='&amp;p[url]='+urlencode(params.share_url);
    url+='&amp;&amp;p[images][0]='+urlencode(params.image);
}

function fb_share_popup( params ){
    window.open(params.share_url,params.title,'toolbar=0,status=0,width='+params.width+',height='+params.height);
}

function urlencode(str) {
    str = (str + '').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function urldecode(str) {
    return decodeURIComponent((str + '').replace(/\+/g, '%20'));
}