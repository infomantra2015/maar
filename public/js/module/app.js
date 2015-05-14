var callbackList = {};

var app = {
    'LOADER': '<img style="display:block;margin:auto;width:350px;" src="/img/load.gif" />',
    'init': function () {
        /*
        jQuery(document).ajaxStart(function () {
            jQuery('#ajaxLoader').show();
        }).ajaxStop(function () {
            jQuery('#ajaxLoader').hide();
        });
        */
        callbackList['getStatesResponse'] = function (data, status) {
            jQuery('#stateId').empty();
            jQuery.each(data.states, function (index, val) {
                jQuery('#stateId').append(jQuery('<option>', {value: index, text: val}));
            });
        };

        callbackList['getCitiesResponse'] = function (data, status) {
            jQuery('#cityId').empty();
            jQuery.each(data.cities, function (index, val) {
                jQuery('#cityId').append(jQuery('<option>', {value: index, text: val}));
            });
        };

        app.additionalValidators();
    },
    'ajaxLoader': function (selector, operation) {
        if (operation == 'show') {
            jQuery(selector).html(app.LOADER);
        } else {
            jQuery(selector).html('');
        }
    },
    'additionalValidators': function () {

        jQuery.validator.addMethod("indianPhone", function (value, element) {
            return (jQuery.trim(value)).match(/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1}){0,1}9[0-9](\s){0,1}(\-){0,1}(\s){0,1}[1-9]{1}[0-9]{7}$/);
        }, jQuery.validator.format("Please enter valid mobile number."));

    },
    'ajaxFormSubmit': function (formSelector, postDispatch, preDispatch,
            target, url, type, dataType, completeCallback, clearForm) {

        var options = {
            cache: 'false',
            success: callbackList[postDispatch]
        };

        if (jQuery.type(clearForm) === 'undefined') {
            options['clearForm'] = true;
            options['resetForm'] = true;
        } else {
            options['clearForm'] = clearForm;
            options['resetForm'] = clearForm;
        }

        if (typeof target !== null) {
            options['target'] = target;
        }

        if (typeof url !== null) {
            options['url'] = url;
        }

        if (typeof type !== null) {
            options['type'] = type;
        }

        if (typeof dataType !== null) {
            options['dataType'] = dataType;
        }

        if (typeof preDispatch !== null) {
            options['beforeSubmit'] = callbackList[preDispatch];
        }

        if (typeof dataType !== null) {
            options['complete'] = callbackList[completeCallback];
        }

        jQuery(formSelector).ajaxForm(options);
    },
    'ajaxRequest': function (requestUrl, method, content, responseType,
            callback) {

        jQuery.ajax({
            url: requestUrl,
            type: method,
            dataType: responseType,
            data: content,
            async: true,
            success: function (data, status) {
                callbackList[callback](data, status);
            },
            error: function (xhr, textStatus, errorThrown) {

                var message = 'There was an unexpected error. Please try again.';
                alert(message);

            },
            beforeSend: function () {
                /* Logic before ajax request sent */
            },
            complete: function () {
                /* Logic after ajax request completes */
            }
        });
    },
    'managePaging': function managePaging(parentSelector, url, callback,
            params) {

        /* Manage paging */
        /* @author : Arvind Singh */

        if (jQuery(".pagination", jQuery(parentSelector)).length) {

            jQuery('.pagination a', jQuery(parentSelector)).unbind('click');

            jQuery('.pagination a', jQuery(parentSelector))
                    .click(
                            function (event) {
                                event.preventDefault();
                                if (event.target == this) {

                                    if (jQuery.type(jQuery(this).attr('href')) != "undefined") {

                                        var part = (jQuery(this).attr('href'))
                                                .split("/");
                                        var page = part[part.length - 1];

                                        if (isNaN(page)) {
                                            page = 1;
                                        }

                                        jQuery("#pageNo").val(page);

                                        var data = {};
                                        data['order'] = jQuery("#sortBy").val();
                                        data['field'] = jQuery("#columnName")
                                                .val();

                                        if (jQuery.type(params) == 'object') {
                                            jQuery.each(params, function (index,
                                                    val) {
                                                data[index] = val;
                                            });
                                        }

                                        app.ajaxRequest(url + '/page/'
                                                + page, 'POST', data, 'html',
                                                callback);
                                    }
                                }
                            });
        }
    },
    'manageSorting': function manageSorting(parentSelector, url, callback,
            params) {

        /* Manage sorting */
        /* @author : Arvind Singh */

        if (jQuery(".sorting,.sorting_asc,.sorting_desc",
                jQuery(parentSelector)).length) {

            if (jQuery("#totalItemCount").val() > 1) {

                jQuery('.sorting,.sorting_asc,.sorting_desc',
                        jQuery(parentSelector)).unbind('click');
                jQuery('.sorting,.sorting_asc,.sorting_desc',
                        jQuery(parentSelector)).click(
                        function (event) {

                            event.preventDefault();

                            if (event.target == this) {

                                jQuery("#columnName").val(
                                        jQuery("a", jQuery(this)).attr('alt'));
                                if (jQuery("#sortBy").val() == 'asc') {
                                    jQuery("#sortBy").val('desc');
                                } else {
                                    jQuery("#sortBy").val('asc');
                                }
                                jQuery("#pageNo").val(1);

                                var data = {};
                                data['order'] = jQuery("#sortBy").val();
                                data['field'] = jQuery("#columnName").val();

                                if (jQuery.type(params) == 'object') {
                                    jQuery.each(params, function (index, val) {
                                        data[index] = val;
                                    });
                                }

                                app.ajaxRequest(url + '/page/1',
                                        'POST', data, 'html', callback);
                            }
                        });

            }
        }
    },
};

function getStates(content) {
    app.ajaxRequest('/application/index/get-states', 'POST', content, 'JSON', 'getStatesResponse');
}

function getCities(content) {
    app.ajaxRequest('/application/index/get-cities', 'POST', content, 'JSON', 'getCitiesResponse');
}

function closeAllModals() {
    jQuery.each(BootstrapDialog.dialogs, function (id, dialog) {
        dialog.close();
    });
}

jQuery(document).ready(function () {
    app.init();
});