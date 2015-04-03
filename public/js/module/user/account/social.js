var address = {
    'init': function () {

        detectFormChange.setFormData('#SocialForm', detectFormChange.flag.initial);
        
        jQuery('#SocialForm').validate();

        callbackList['validateSocialForm'] = function () {
            if (jQuery('#SocialForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['socialResponse'] = function (data, status) {
            detectFormChange.resetFormData('#SocialForm');
            alert(data.message);
        };

        jQuery('#submitBtn').click(function (event) {
            if (event.target === this) {
                
                detectFormChange.setFormData('#SocialForm', detectFormChange.flag.before);
                
                if (!detectFormChange.isFormDataChanged('#SocialForm')) {
                    return false;
                }
                
                app.ajaxFormSubmit('#SocialForm', 'socialResponse', 'validateSocialForm',
                        null, '/user/account/process-user-social-update', 'POST', 'JSON', null, false);
            }
        });

    }
};

jQuery(document).ready(function () {
    address.init();
});