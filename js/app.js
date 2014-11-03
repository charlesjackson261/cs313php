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
    });

    
    // dashboard buttons
    $('#btnActivateDisplay').click(function () {

        // forward the user over to the activation wizard
        window.location.replace("?action=activate");
        
    });
    
    $('.btnUpdateSubscription').click(function () {

        alert('btnUpdateSubscription pressed');
        
    });
    
    $('.btnEditDisplay').click(function () {

        alert('btnEditDisplay pressed');
        
    });
    
    $('.btnDeleteDisplay').click(function () {

        alert('btnDeleteDisplay pressed');
        
    });
    
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
    .done(function( ajaxresponse ) {


        try {
            var response = jQuery.parseJSON( ajaxresponse );
        } catch (err) {
            alert("Error: " + ajaxresponse);
        }

        if (response.response_code == 1)
        {
            // all is good
            displayLoginMsg("Login Success", '', 'success');

            // add a block to the form
            $('#frmLogin').css('display', 'none');

            // redirect the user to the dashboard
            var redirect = '';
            $.redirectPost(redirect, {action: 'dashboard', sub: '12'});

        } else {
            // failed login
            if (response.msgs.length > 0)
            {
                displayLoginMsg("Login Error", response.msgs, 'error');

            } else {
                displayLoginMsg("Login Error", '', 'error');
            }
        }

        // displayLoginMsg("Login Success", ajaxresponse, 'success');

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

// jquery extend function
$.extend(
    {
        redirectPost: function(location, args)
        {
            var form = '';
            $.each( args, function( key, value ) {
                form += '<input type="hidden" name="'+key+'" value="'+value+'">';
            });
            $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
        }
    });