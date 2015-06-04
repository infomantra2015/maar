var resetPassword = {
    'init': function () {
        jQuery('#ResetPasswordForm').validate();

        callbackList['validateResetPasswordForm'] = function () {
            if (jQuery('#ResetPasswordForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['resetPasswordResponse'] = function (data, status) {
            BootstrapDialog.alert(data.message, function () {
                if (data.status == 'success') {
                    location.href = '/user';
                }
            });
        };

        jQuery('#resetPasswordBtn').click(function (event) {
            if (event.target === this) {
                app.ajaxFormSubmit('#ResetPasswordForm', 'resetPasswordResponse', 'validateResetPasswordForm',
                        null, '/user/forgot-password/process-reset-password', 'POST', 'JSON', null, false);
            }
        });
    }
};

jQuery(document).ready(function () {
    resetPassword.init();
});