var assignOffer = {
    'offerCount': 0,
    'bPaginate': false,
    'bFilter': false,
    'bSort': false,
    'bLengthChange': false,
    'recordsPerPage': 10,
    'init': function () {

        assignOffer.offerCount = jQuery('#totalOffers').val();

        if (assignOffer.offerCount > assignOffer.recordsPerPage) {
            assignOffer.bPaginate = true;
            assignOffer.bLengthChange = true;
        }

        if (assignOffer.offerCount > 1) {
            assignOffer.bFilter = true;
            assignOffer.bSort = true;
        }

        jQuery('#offerAssignment').dataTable({
            "bPaginate": assignOffer.bPaginate,
            "bLengthChange": assignOffer.bLengthChange,
            "bFilter": assignOffer.bFilter,
            "bSort": assignOffer.bSort,
            "bInfo": false,
            "bAutoWidth": true,
            "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }]
        });

        var checkAll = jQuery('input[type="checkbox"].checkAll');
        var checkboxes = jQuery('input[type="checkbox"].flat-red');

        checkAll.iCheck({
            checkboxClass: 'icheckbox_flat-green'
        });

        checkboxes.iCheck({
            checkboxClass: 'icheckbox_flat-green'
        });

        checkAll.on('ifChecked ifUnchecked', function (event) {
            if (event.type == 'ifChecked') {
                checkboxes.iCheck('check');
            } else {
                checkboxes.iCheck('uncheck');
            }
        });

        checkboxes.on('ifChanged', function (event) {
            if (checkboxes.filter(':checked').length == checkboxes.length) {
                checkAll.prop('checked', true);
            } else {
                checkAll.prop('checked', false);
            }
            checkAll.iCheck('update');
        });

        callbackList['validateAssignOfferForm'] = function () {
            return true;
        };

        callbackList['assignOfferResponse'] = function (data, status) {
            closeAllModals();
            BootstrapDialog.alert(data.message);
        };
    },
    'assignStoresToOffer': function () {

        app.ajaxFormSubmit('#assignOfferForm', 'assignOfferResponse', 'validateAssignOfferForm',
                null, '/store/offer/process-offer-assignment', 'POST', 'JSON');

        jQuery('#submitButton').trigger('click');
    }
};

jQuery(document).ready(function () {
    assignOffer.init();
});

