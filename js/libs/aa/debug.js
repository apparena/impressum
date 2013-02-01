/**
 * Helper functions to improve the development process
 */

/**
 * Creates a var dump out of a javascript object.
 * @param {Object} obj The object to dump
 * @param {String} selector the selector to show the dumping in.
 * @param {boolean} overwrite set to true if the object shall overwrite the selectors content, or false to append stuff.
 */
function var_dump(obj, selector, overwrite) {
    if ( typeof( overwrite ) == 'undefined' || overwrite != true ) {
        overwrite = false;
    }
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    //console.log(out);
    if ( overwrite ) {
        $( selector ).html( out );
    } else {
        $( selector ).append( out );
    }
}