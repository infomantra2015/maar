var store = {
    'init': function () {

        callbackList['removeStoreOffersResponse'] = function (data, status) {
            closeAllModals();
            BootstrapDialog.alert(data.message);
        };

        callbackList['getStatesResponse'] = function (data, status) {

            var parent = jQuery('.' + data.selector);

            jQuery('.stateId', parent).empty();
            jQuery.each(data.states, function (index, val) {
                jQuery('.stateId', parent).append(jQuery('<option>', {value: index, text: val}));
            });
        };

        callbackList['getCitiesResponse'] = function (data, status) {
            var parent = jQuery('.' + data.selector);
            jQuery('.cityId', parent).empty();
            jQuery.each(data.cities, function (index, val) {
                jQuery('.cityId', parent).append(jQuery('<option>', {value: index, text: val}));
            });
        };

        callbackList['validateStoreForm'] = function () {
            if (jQuery('#StoreForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['storeResponse'] = function (data, status) {
            closeAllModals();
            BootstrapDialog.alert(data.message);
            if (data.status == 'success') {
                store.getStoreList();
            }
        };

        callbackList['getStoreListResponse'] = function (data, status) {
            jQuery('#storeListResponse').html(data);
            app.managePaging('#storeListResponse', '/store/store/get-store-list', 'getStoreListResponse');
            app.manageSorting('#storeListResponse', '/store/store/get-store-list', 'getStoreListResponse');
        };

        callbackList['renderStoreResponse'] = function (data, status) {
            jQuery('#manageStoreBox').html(data);
            jQuery('#StoreForm').validate();

            jQuery('#submitBtn').click(function (event) {
                if (event.target === this) {

                    var clearForm = true;
                    if (jQuery('#storeId').val() > 0) {
                        clearForm = false;
                    }

                    app.ajaxFormSubmit('#StoreForm', 'storeResponse', 'validateStoreForm',
                            null, '/store/store/process-store-update', 'POST', 'JSON', null, clearForm);
                }
            });

            jQuery('#cancelBtn').click(function (event) {
                if (event.target === this) {
                    if (jQuery('#storeId').val() > 0) {
                        store.renderStoreForm();
                    } else {
                        jQuery('#StoreForm')[0].reset();
                    }
                }
            });
        };

        callbackList['viewStoreResponse'] = function (data, status) {
            jQuery('#store-information').html(data);
        };
        
        callbackList['renderStorePicturesResponse'] = function (data, status) {
            jQuery('#store-pictures').html(data);
        };
        
        callbackList['renderStoreTimingsResponse'] = function (data, status) {
            jQuery('#store-timings').html(data);
        };
        
        callbackList['getStorePictureListResponse'] = function (data, status) {
            jQuery('#storePictureList').html(data);
        };
        
        store.renderStoreForm();
        store.getStoreList();
    },
    'getStoreList': function () {
        app.ajaxLoader('#storeListResponse', 'show');
        var data = {};
        data['order'] = jQuery("#sortBy").val();
        data['field'] = jQuery("#columnName").val();

        app.ajaxRequest('/store/store/get-store-list', 'POST', data, 'HTML', 'getStoreListResponse');
    },
    'renderStoreForm': function (storeId) {
        app.ajaxLoader('#manageStoreBox', 'show');
        var data = {};

        if (jQuery.type(storeId) !== 'undefined') {
            data['storeId'] = storeId;
        }
        app.ajaxRequest('/store/store/render-store-form', 'POST', data, 'HTML', 'renderStoreResponse');
    },
    'getStorePictueList': function(storeId) {
        app.ajaxLoader('#storePictureList', 'show');
        var data = {};

        if (jQuery.type(storeId) !== 'undefined') {
            data['storeId'] = storeId;
        }
        app.ajaxRequest('/store/store/get-store-picture-list', 'POST', data, 'HTML', 'getStorePictureListResponse');
    }
};

jQuery(document).ready(function () {
    store.init();
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

function viewStore(storeId) {
    $('#storeDetailBox').show();
    app.ajaxRequest('/store/store/view-store', 'POST', {'storeId': storeId}, 'HTML', 'viewStoreResponse');    
    app.ajaxRequest('/store/store/render-store-pictures', 'POST', {'storeId': storeId}, 'HTML', 'renderStorePicturesResponse');
    app.ajaxRequest('/store/store/render-store-timings', 'POST', {'storeId': storeId}, 'HTML', 'renderStoreTimingsResponse');
    store.renderStoreForm();
}

function editStore(storeId) {
    store.renderStoreForm(storeId);
    closeStoreDetailsBox();
}

function closeStoreDetailsBox() {
    jQuery('#storeDetailBox').hide();
}

function removeAllOffers(storeId) {

    BootstrapDialog.confirm('Remove all offers associated with the store?', function (result) {
        if (result) {
            app.ajaxRequest('/store/store/remove-store-offers', 'POST', {'storeId': storeId}, 'JSON', 'removeStoreOffersResponse');
        }
    });
}