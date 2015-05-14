var offer = {
    'init': function () {

        callbackList['validateOfferForm'] = function () {
            if (jQuery('#OfferForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['offerUpdateResponse'] = function (data, status) {
            closeAllModals();
            BootstrapDialog.alert(data.message);
            if (data.status == 'success') {
                offer.getOfferList();
            }
        };

        callbackList['getOfferListResponse'] = function (data, status) {
            jQuery('#offerListResponse').html(data);
            app.managePaging('#offerListResponse', '/store/offer/get-offer-list', 'getOfferListResponse');
            app.manageSorting('#offerListResponse', '/store/offer/get-offer-list', 'getOfferListResponse');
        };

        callbackList['renderOfferResponse'] = function (data, status) {

            jQuery('#manageOfferBox').html(data);

            if (jQuery('#offerId').val() > 0) {
                detectFormChange.setFormData('#OfferForm', detectFormChange.flag.initial);
            }

            var validator = jQuery('#OfferForm').validate();

            jQuery.validator.addMethod("greaterThan", function (value, element,
                    params) {

                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) >= new Date(jQuery(
                            params).val());
                }

                return isNaN(value)
                        && isNaN(jQuery(params).val())
                        || (Number(value) > Number(jQuery(
                                params).val()));
            },
                    'Valid To date must be equal or greater than Valid From date.');

            jQuery('#submitBtn').click(function (event) {
                if (event.target === this) {

                    var clearForm = true;

                    if (jQuery('#offerId').val() > 0) {

                        detectFormChange.setFormData('#OfferForm', detectFormChange.flag.before);

                        if (!detectFormChange.isFormDataChanged('#OfferForm')) {
                            return false;
                        }
                        clearForm = false;
                    }

                    app.ajaxFormSubmit('#OfferForm', 'offerUpdateResponse', 'validateOfferForm',
                            null, '/store/offer/process-offer-update', 'POST', 'JSON', null, clearForm);
                }
            });

            jQuery('#cancelBtn').click(function (event) {
                if (event.target === this) {
                    if (jQuery('#offerId').val() > 0) {
                        offer.renderOfferForm();
                    } else {
                        jQuery('#OfferForm')[0].reset();
                    }
                    validator.resetForm();
                }
            });

            new Pikaday({
                field: document.getElementById('validFrom'),
                minDate: new Date(),
                format: 'YYYY-MM-DD'
            });

            new Pikaday({
                field: document.getElementById('validTo'),
                minDate: new Date(),
                format: 'YYYY-MM-DD',
                onSelect: function () {
                    console.log(this.getMoment().format('Do MMMM YYYY'));
                }
            });
        };

        callbackList['viewOfferResponse'] = function (data, status) {
            jQuery('#viewOfferResponse').html(data);
            jQuery('#offerDetailBox').show();
        };

        offer.renderOfferForm();
        offer.getOfferList();
    },
    'getOfferList': function () {
        app.ajaxLoader('#offerListResponse', 'show');
        var data = {};
        data['order'] = jQuery("#sortBy").val();
        data['field'] = jQuery("#columnName").val();

        app.ajaxRequest('/store/offer/get-offer-list', 'POST', data, 'HTML', 'getOfferListResponse');

    },
    'renderOfferForm': function (offerId) {
        app.ajaxLoader('#manageOfferBox', 'show');
        var data = {};

        if (jQuery.type(offerId) !== 'undefined') {
            data['offerId'] = offerId;
        }
        app.ajaxRequest('/store/offer/render-offer-form', 'POST', data, 'HTML', 'renderOfferResponse');
    }
};

jQuery(document).ready(function () {
    offer.init();
});

function getContent(obj, flag) {
    var selector = '';
    selector = jQuery(obj).parent().parent().attr('class');
    if (flag == 'country') {
        getStates({'countryId': jQuery(obj).val(), 'selector': selector});
    } else {
        getCities({'stateId': jQuery(obj).val(), 'selector': selector});
    }
}

function viewOffer(offerId) {
    app.ajaxLoader('#viewOfferResponse', 'show');
    app.ajaxRequest('/store/offer/view-offer', 'POST', {'offerId': offerId}, 'HTML', 'viewOfferResponse');
    offer.renderOfferForm();
}

function editOffer(offerId) {
    offer.renderOfferForm(offerId);
    closeOfferDetailBox();
}

function closeOfferDetailBox() {
    jQuery('#viewOfferResponse').html('');
    jQuery('#offerDetailBox').hide();
}

function assignOfferToStore(offerId) {

    BootstrapDialog.show({
        closable: false,
        closeByBackdrop: false,
        closeByKeyboard: false,
        title: 'Assing Stores to Offer',
        buttons: [{
                label: 'Assign Stores To Offer',
                action: function (dialog) {
                    assignOffer.assignStoresToOffer();
                }
            }, {
                label: 'Cancel',
                action: function (dialog) {
                    dialog.close();
                }
            }],
        onshown: function () {
            jQuery('.modal-body').load('/store/offer/assign-offer-to-store/offerId/' + offerId);
        }
    });
}