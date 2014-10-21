// this is the javascript application.js file. The user should not be able to view it.

$(document).ready(function(){
    // alert('app.js is loaded' + '\r\n' + transkey);

    // assign the handler for the login form
    $('#frmLogin').submit( function (e) {

        e.preventDefault();
        var form = $(e.target).parents("form");
        
        // get the data from the form
        var uname = $("#un").val();
        var upass = $("#up").val();
        
        // clean the input from the user.
        // TODO: create a function to clean the input from the user, checking for hacking attempts.

        // verify input
        if (uname.length > 0)
            uname = encodeData(uname);
        if (upass.length > 0)
            upass = encodeData(upass);
        
        // build the data array
        var data = {action:"login", method:"json" , un:uname, up:upass};
        
        // post the login to the system
        var url = "index.php";
        ajaxCall(url, data);

        // check the input on the main form for content
        // $(e.target).addClass('selected');


        // $(form).addClass('selected');
    })
});

function encodeData(value)
{
    // check for the transkey
    if (!(typeof(transkey) === 'undefined')) {
        // transkey exists
        // TODO: better encryption, this is really bad.
        return value+transkey;
    } else {
        displayLoginMsg("Login Failed:", 
                        'Transkey cannot be found! Aborting login due to unstable security.', 
                        'error');
        return false;
    }
}

function ajaxCall(url, data) 
{
    // data must be a named array
    // { name: "John", location: "Boston" }

    $.ajax({
        type: "POST",
        url: url,
        data: data
    })
    .done(function( msg ) {
        displayLoginMsg("Login Success", msg, 'success');
    }).fail(function( jqXHR, textStatus ) {
        displayLoginMsg("Login Failed:", textStatus, 'warning');
        // alert( "Request Failed: " + textStatus );
    }).always(function() {
        // alert( "complete" );
    })
}

function displayLoginMsg (boldMsg, msg, msgType)
{
    $('#loginMsg').html('<div id="loginError" class="alert alert-' + msgType + '">' + '<strong>' + boldMsg + '</strong> ' + msg + '</div>');
}

function displayAppError (errMsg) {
    
}