var detectFormChange = {
    'flag': {'initial': 'initial', 'before': 'before'},
    'initialFormData': [],
    'formDataBeforeSubmit': [],
    'setFormData': function (formSelector, flag) {
        if (flag == detectFormChange.flag.initial) {
            detectFormChange.initialFormData[formSelector] = jQuery(formSelector).serialize();
        } else {
            detectFormChange.formDataBeforeSubmit[formSelector] = jQuery(formSelector).serialize();
        }
    },
    'isFormDataChanged': function (formSelector) {
        if ((detectFormChange.initialFormData[formSelector] == detectFormChange.formDataBeforeSubmit[formSelector])) {
            alert('Please make some changes first.');
            return false;
        }
        return true;
    },
    'resetFormData': function (formSelector) {
        detectFormChange.initialFormData[formSelector] = jQuery(formSelector).serialize();
    },
    'cleanAll': function () {
        detectFormChange.initialFormData = {};
        detectFormChange.formDataBeforeSubmit = {};
    }
};