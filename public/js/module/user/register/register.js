var register = {
    'init': function () {
        
        // Validte login form        
        jQuery('#RegisterForm').validate();

        callbackList['validateRegisterForm'] = function () {
            if (jQuery('#RegisterForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['registerResponse'] = function (data, status) {
            BootstrapDialog.alert(data.message);
            location.href = '/user/login';
        };

        jQuery('#registerBtn').click(function (event) {
            if (event.target === this) {
                app.ajaxFormSubmit('#RegisterForm', 'registerResponse', 'validateRegisterForm',
                        null, '/user/register/process-registration', 'POST', 'JSON', null, false);
            }
        });
    }
};

jQuery(document).ready(function () {
    register.init();
});