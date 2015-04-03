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
            alert(data.message);
        };

        callbackList['getProfilePicsResponse'] = function (data, status) {
            jQuery('#profileImageList').html(data);
        };

        jQuery('#submitBtn').click(function (event) {
            if (event.target === this) {
                app.ajaxFormSubmit('#FileUploadForm', 'fileUploadResponse', 'validateFileUploadForm',
                        null, '/user/account/process-user-file-upload', 'POST', 'JSON', null, false);
            }
        });

        upload.getProfilePics();
    },
    'getProfilePics': function () {
        app.ajaxRequest('/user/account/get-user-profile-pics', 'POST', {}, 'html', 'getProfilePicsResponse');
    }
};

jQuery(document).ready(function () {
    upload.init();
});