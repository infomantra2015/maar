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


        callbackList['downloadUserPicResponse'] = function (data, status) {
            alert(data.message);
            upload.getProfilePics();
        };

        callbackList['deleteUserPicResponse'] = function (data, status) {
            alert(data.message);
            upload.getProfilePics();
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
        app.ajaxLoader('#profileImageList', 'show');
        app.ajaxRequest('/user/account/get-user-profile-pics', 'POST', {}, 'html', 'getProfilePicsResponse');
    }
};

function downloadPic(picId, ext) {
    app.ajaxRequest('/user/account/download-user-pic', 'POST', {'picId': picId, 'ext': ext}, 'json', 'downloadUserPicResponse');
}

function deletePic(picId, ext) {

    var c = confirm('Do you want to delete the picture?');
    if (c) {
        app.ajaxRequest('/user/account/delete-user-pic', 'POST', {'picId': picId, 'ext': ext}, 'json', 'deleteUserPicResponse');
    }
}

jQuery(document).ready(function () {
    upload.init();
});