var account = {
    'init': function () {

        callbackList['getRenderProfileResponse'] = function (data, status) {
            jQuery('#tab_1').html(data);
        };

        callbackList['getRenderAddressResponse'] = function (data, status) {
            jQuery('#tab_2').html(data);
        };

        callbackList['getRenderSocialResponse'] = function (data, status) {
            jQuery('#tab_3').html(data);
        };

        callbackList['getRenderUploadProfileResponse'] = function (data, status) {
            jQuery('#tab_4').html(data);
        };

        callbackList['manageStoreResponse'] = function (data, status) {
            jQuery('#tab_5').html(data);
        };

        callbackList['manageOfferResponse'] = function (data, status) {
            jQuery('#tab_6').html(data);
        };

        account.manageTabs();
    },
    'manageTabs': function () {

        [].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
            new CBPFWTabs(el);
        });

        jQuery('#manageProfile').click(function (event) {
            if (event.target == this) {
                jQuery("div[id^='tab_']").html('');
                account.renderProfileForm();
            }
        });

        jQuery('#manageAddress').click(function (event) {
            if (event.target == this) {
                jQuery("div[id^='tab_']").html('');
                account.renderAddressForm();
            }
        });

        jQuery('#manageSocialInfo').click(function (event) {
            if (event.target == this) {
                jQuery("div[id^='tab_']").html('');
                account.renderSocialForm();
            }
        });

        jQuery('#uploadPicture').click(function (event) {
            if (event.target == this) {
                jQuery("div[id^='tab_']").html('');
                account.renderUploadProfileForm();
            }
        });

        jQuery('#manageStore').click(function (event) {
            if (event.target == this) {
                jQuery("div[id^='tab_']").html('');
                account.renderStoreForm();
            }
        });

        jQuery('#manageOffer').click(function (event) {
            if (event.target == this) {
                jQuery("div[id^='tab_']").html('');
                account.renderOfferForm();
            }
        });

        jQuery('#manageSetting').click(function (event) {
            if (event.target == this) {
                jQuery("div[id^='tab_']").html('');
            }
        });

        jQuery('#manageProfile').trigger('click');
    },
    'renderProfileForm': function () {
        app.ajaxLoader('#tab_1', 'show');
        setTimeout(function () {
            app.ajaxRequest('/user/account/render-profile', 'POST', {}, 'HTML', 'getRenderProfileResponse');
        }, 1000);
    },
    'renderAddressForm': function () {
        app.ajaxLoader('#tab_2', 'show');
        setTimeout(function () {            
            app.ajaxRequest('/user/account/render-address', 'POST', {}, 'HTML', 'getRenderAddressResponse');
        }, 1000);
    },
    'renderSocialForm': function () {
        app.ajaxLoader('#tab_3', 'show');
        setTimeout(function () {            
            app.ajaxRequest('/user/account/render-social', 'POST', {}, 'HTML', 'getRenderSocialResponse');
        }, 1000);
    },
    'renderUploadProfileForm': function () {
        app.ajaxLoader('#tab_4', 'show');
        setTimeout(function () {            
            app.ajaxRequest('/user/account/render-upload-profile-pic', 'POST', {}, 'HTML', 'getRenderUploadProfileResponse');
        }, 1000);
    },
    'renderStoreForm': function () {
        app.ajaxLoader('#tab_5', 'show');
        setTimeout(function () {            
            app.ajaxRequest('/store/store/manage-store', 'POST', {}, 'HTML', 'manageStoreResponse');
        }, 1000);
    },
    'renderOfferForm': function () {
        app.ajaxLoader('#tab_6', 'show');
        setTimeout(function () {            
            app.ajaxRequest('/store/offer/manage-offer', 'POST', {}, 'HTML', 'manageOfferResponse');
        }, 1000);
    },
    'renderSettingForm': function () {
        app.ajaxLoader('#tab_7', 'show');
        app.ajaxRequest('/user/account/render-setting', 'POST', {}, 'HTML', 'getRenderSettingResponse');
    }
};

jQuery(document).ready(function () {
    account.init();
});