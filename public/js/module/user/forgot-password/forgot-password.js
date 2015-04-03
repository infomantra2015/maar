var forgotPassword = {
    'init': function(){
        jQuery('#ForgotPasswordForm').validate();
        
        callbackList['validateForgotPasswordForm'] = function () {
            if (jQuery('#ForgotPasswordForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['forgotPasswordResponse'] = function (data, status) {
            alert(data.message);
            if(data.status == 'success'){
                location.href = '/user';
            }     
        };

        jQuery('#forgotPasswordBtn').click(function (event) {
            if (event.target === this) {
                app.ajaxFormSubmit('#ForgotPasswordForm', 'forgotPasswordResponse', 'validateForgotPasswordForm',
                        null, '/user/forgot-password/process-forgot-password', 'POST', 'JSON', null, false);
            }
        });
    }
};

jQuery(document).ready(function(){
    forgotPassword.init();
});