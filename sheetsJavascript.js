alert('whyyyyy');

$(document).ready(function( $ ) {
    alert('waaa');
    $('#newProjectForm').submit(function(event) {
        alert('form submit!');
        //sendData("newProj=hello||1||2||4||nice", "wow");
    });
    
    $('#result').load('javascript here!');
    //$('#newProjectForm').submit(function(event) {alert('wow');});
});
    // variable to hold request
    var request;
    // bind to the submit event of our form
    function sendData(formData,postType){
        alert('sendData!');
        // abort any pending request
        if (request) {
            request.abort();
        }
        // setup some local variables


        // let's disable the inputs for the duration of the ajax request
        // Note: we disable elements AFTER the form data has been serialized.
        // Disabled form elements will not be serialized.
        $('#result').text('Sending data...');

        // fire off the request to /form.php
        request = $.ajax({
            url: "https://script.google.com/macros/s/AKfycbxv4wFDp2dwwAG9Wx51g7AUy1F_ZEH1UvkKBwbQiBBQtvLtleI/exec",
            type: "get",
            data: formData;
        });

        // callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR){
            // log a message to the console
            $('#result').html('AJAX done, now for PHP');
            console.log("Hooray, it worked!");
            
            var phprequest;
            
            phprequest = $.ajax({
                url: 'SheetsPHP.php',
                type: 'post',
                data: {
                    newProj: "start",
                    firstStation: "1",
                    lastStation: "2",
                    totalMade: '20'
                }
                
            });
        });

        // callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            // log the error to the console
            console.error(
                "The following error occured: "+
                textStatus, errorThrown
            );
        });

        // callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // reenable the inputs
            $inputs.prop("disabled", false);
        });

        // prevent default posting of form
        event.preventDefault();

    }
//});
