var address = {
    'init': function () {

        detectFormChange.setFormData('#AddressForm', detectFormChange.flag.initial);

        jQuery('#AddressForm').validate();

        callbackList['validateProfileForm'] = function () {
            if (jQuery('#AddressForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['addressResponse'] = function (data, status) {
            detectFormChange.resetFormData('#AddressForm');
            BootstrapDialog.alert(data.message);
        };

        jQuery('#addressBtn').click(function (event) {
            if (event.target === this) {
                
                detectFormChange.setFormData('#AddressForm', detectFormChange.flag.before);
                
                if (!detectFormChange.isFormDataChanged('#AddressForm')) {
                    return false;
                }
                
                app.ajaxFormSubmit('#AddressForm', 'addressResponse', 'validateAddressForm',
                        null, '/user/account/process-user-address-update', 'POST', 'JSON', null, false);
            }
        });

        jQuery('#countryId').change(function () {
            getStates( {'countryId':jQuery(this).val()});
        });

        jQuery('#stateId').change(function () {
            getCities({'stateId':jQuery(this).val()});
        });
    }
};

jQuery(document).ready(function () {
    address.init();
});