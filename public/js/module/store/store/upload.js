var upload = {
    'init': function () {

        jQuery('#FileUploadForm').validate();

        callbackList['validateFileUploadForm'] = function () {
            if (jQuery('#FileUploadForm').valid()) {
                return true;
            }
            return false;
        };

        callbackList['fileUploadResponse'] = function (data, status) {
            BootstrapDialog.alert(data.message);
            store.getStorePictueList(data.storeId);
        };

        callbackList['downloadStorePicResponse'] = function (data, status) {
            BootstrapDialog.alert(data.message);
        };

        callbackList['deleteStoreLogoResponse'] = function (data, status) {
            BootstrapDialog.alert(data.message);
            store.getStorePictueList(data.storeId);
        };

        callbackList['setStoreLogoResponse'] = function (data, status) {
            BootstrapDialog.alert(data.message);
            store.getStorePictueList(data.storeId);
        };

        callbackList['removeStoreLogoResponse'] = function (data, status) {
            BootstrapDialog.alert(data.message);
            store.getStorePictueList(data.storeId);
        };

        jQuery('#uploadBtn').click(function (event) {
            if (event.target === this) {
                app.ajaxFormSubmit('#FileUploadForm', 'fileUploadResponse', 'validateFileUploadForm',
                        null, '/store/store/process-store-file-upload', 'POST', 'JSON', null, false);
            }
        });
        
        store.getStorePictueList(jQuery('#picStoreId').val());
    }
};

function downloadPic(storeId, picId, ext) {
    app.ajaxRequest('/store/store/download-store-pic', 'GET', {'storeId' : storeId, 'picId': picId, 'ext': ext}, 'HTML', 'downloadStorePicResponse');
}

function deletePic(storeId, picId, ext) {

    var c = confirm('Do you want to delete the picture?');
    if (c) {
        app.ajaxRequest('/store/store/delete-store-pic', 'POST', {'storeId' : storeId, 'picId': picId, 'ext': ext}, 'json', 'deleteStoreLogoResponse');
    }
}

function setStorePic(storeId, picId, ext) {
    app.ajaxRequest('/store/store/set-store-logo', 'POST', {'storeId' : storeId, 'picId': picId, 'ext': ext}, 'json', 'setStoreLogoResponse');
}

function removeStorePic(storeId, picId, ext) {
    var c = confirm('Do you want to remove store logo?');
    if (c) {
        app.ajaxRequest('/store/store/remove-store-logo', 'POST', {'storeId' : storeId, 'picId': picId, 'ext': ext}, 'json', 'removeStoreLogoResponse');
    }
}

jQuery(document).ready(function () {
    upload.init();
});