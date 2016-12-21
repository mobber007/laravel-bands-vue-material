/**
 * Created by Mike on 10/22/2016.
 */
toastr = require('./vendor/toastr/toastr.min');
(function(t){

    t.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "preventDuplicates": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    window.displayToast = function(response){

        var type = "info";
        var title = null;
        var content = null;

        if( response.body.hasOwnProperty('message') ){
            //toast friendly response

            type = response.body.message.type;
            content = response.body.message.content;

            if( response.body.message.hasOwnProperty('title') ){
                //has a title
                title = response.body.message.title;

            }

        } else {
            //non-toastr friendly response

            if( response.status >= 400 ){

                type = "error";

                content = response.statusText;
                title = response.status;

            }

        }

        if( response.status !== 422 ){
            t[type](content, title); //finally display the toastr notification
        } else {
            //we have validation errors

            for( var x in response.body ){
                var error = response.body[x];
                t[type](error);
            }
        }




    };
})(toastr);
