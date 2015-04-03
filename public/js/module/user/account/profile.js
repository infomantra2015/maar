var profile = {
    'init': function () {

        detectFormChange.setFormData('#ProfileForm', detectFormChange.flag.initial);

        jQuery('#ProfileForm').validate();

        callbackList['validateProfileForm'] = function () {
            if (jQuery('#ProfileForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['profileResponse'] = function (data, status) {
            detectFormChange.resetFormData('#ProfileForm');
            alert(data.message);
        };

        jQuery('#profileBtn').click(function (event) {
            if (event.target === this) {

                detectFormChange.setFormData('#ProfileForm', detectFormChange.flag.before);

                if (!detectFormChange.isFormDataChanged('#ProfileForm')) {
                    return false;
                }
                app.ajaxFormSubmit('#ProfileForm', 'profileResponse', 'validateProfileForm',
                        null, '/user/account/process-profile-update', 'POST', 'JSON', null, false);
            }
        });

        jQuery('#countryId').change(function () {
            getStates(jQuery(this).val());
        });

        jQuery('#stateId').change(function () {
            getCities(jQuery(this).val());
        });

        new Pikaday({
            field: document.getElementById('dob'),
            maxDate: new Date(),
            format: 'YYYY-MM-DD'
        });

    }
};

jQuery(document).ready(function () {
    profile.init();
});