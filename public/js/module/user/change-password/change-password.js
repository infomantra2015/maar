var changePassword = {
    'init': function () {
        jQuery('#ChangePasswordForm').validate();

        callbackList['validateChangePasswordForm'] = function () {
            if (jQuery('#ChangePasswordForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['changePasswordResponse'] = function (data, status) {
            alert(data.message);
        };

        jQuery('#changePasswordBtn').click(function (event) {
            if (event.target === this) {
                app.ajaxFormSubmit('#ChangePasswordForm', 'changePasswordResponse', 'validateChangePasswordForm',
                        null, '/user/change-password/process-change-password', 'POST', 'JSON');
            }
        });
    }
};

jQuery(document).ready(function () {
    changePassword.init();
});