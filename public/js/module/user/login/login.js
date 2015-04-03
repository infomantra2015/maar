var login = {
    'init': function () {

        // Validte login form        
        jQuery('#LoginForm').validate();

        callbackList['validateLoginForm'] = function () {
            if (jQuery('#LoginForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['loginResponse'] = function (data, status) {
            alert(data.message);
            if(data.status == 'success'){
                location.href = '/user/dashboard';
            }
        };

        jQuery('#loginBtn').click(function (event) {
            if (event.target === this) {
                app.ajaxFormSubmit('#LoginForm', 'loginResponse', 'validateLoginForm',
                        null, '/user/login/process-login', 'POST', 'JSON', null, false);
            }
        });
    }
};

jQuery(document).ready(function () {
    login.init();
});