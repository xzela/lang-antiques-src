function displaySystemMessage(text) {
    var t;
    var sysDiv = $('#sysDiv');

    sysDiv.html(text);
    sysDiv.slideDown('slow',function() {
        t = setTimeout(function() {
            sysDiv.slideUp('slow');
        }, 5000);
    });
}

function cb_messages(node) {
    isDirty = false;
    if(showSavedMessage) {
        displaySystemMessage('Your changes to the ' + node.attr('name') + ' have been saved.');
        showSavedMessage = false;
    }

}
$(document).ready(function() {

        $('#sub_menu_options').bind('click', function(e) {
            var div = $('#item_submenu_div');
            if(div.is(':hidden')) {
                div.slideDown('fast');
                $('#sub_menu_options').html('Close Options');
            }
            else {
                div.slideUp('fast');
                $('#sub_menu_options').html('Show Options');
            }
        });

        $('#a_show_extra').bind('click', function(e) {
            var div = $('#catalogue_info_div');
            if(div.is(':hidden')) {
                div.slideDown('slow');
                $(this).html('Close Extra Info');
            }
            else {
                div.slideUp('slow');
                $(this).html('Show Extra Info');
            }
        });

        $('#sub_item_info_a').bind('click', function() {
            var div = $('#sub_item_info_div');
            if(div.is(':hidden')) {
                div.slideDown('slow');
                $('#sub_item_info_a').html('Hide More info');
            }
            else {
                div.slideUp('slow');
                $('#sub_item_info_a').html('Show More info');
            }
        });

        $('#div_images').bind('click', function() {
            var div = $('#image_area');
            if(div.is(':hidden')) {
                $(this).html('Hide');
                div.slideDown('slow');
            }
            else {
                $(this).html('Show');
                div.slideUp('slow');
            }
        });

        $('#alerts_messages').bind('click', function() {
            var tr = $('#tr_alerts');
            if(tr.is(':hidden')) { //hidden, show it
                $(this).html('Hide');
                tr.slideDown('slow');
            }
            else { //visible, hide it.
                $(this).html('Show');
                tr.slideUp('slow');
            }
        });

        //Category Checkbox Code
        $('.category_checkbox').bind('click', function(e) {
            var c_url = base_url + 'inventory/AJAX_updateCatalogueStatus';
            $.post(c_url, {
                item_id: id,
                name: $(this).attr('name'),
                status: $(this).attr('checked')
            });
        });

        $('#purchase_date_input').bind('blur', function (e) {
            $.post(base_url + url, {
                item_id : id,
                id: 'purchase_date',
                type: 'date',
                value: $('#purchase_date_input').val()
            });
        });

        //photographer stuff
        $('#photo_queue_a').bind('click', function(e) {
            if($(this).html() == 'Add to Queue' || $(this).html() == 'ReQueue') {
                $('#photo_queue_span').html('In Queue');
                $('#photo_queue_span').attr('class', 'warning');
                $(this).html('Remove from Queue');
                $.post(base_url + url, {
                    item_id: id,
                    id: 'photo_queue',
                    value: 1
                });
            }
            else if($(this).html() == 'Remove from Queue') {
                $('#photo_queue_span').html('Not in Queue');
                $('#photo_queue_span').attr('class', '');
                $(this).html('Add to Queue');
                $.post(base_url + url, {
                    item_id: id,
                    id: 'photo_queue',
                    value: 0
                });
            }
        });
        $('#edit_queue_a').bind('click', function(e){
            if($(this).html() == 'Add to Queue' || $(this).html() == 'ReQueue') {
                $('#edit_queue_span').html('In Queue');
                $('#edit_queue_span').attr('class', 'warning');
                $(this).html('Remove from Queue');
                $.post(base_url + url, {
                    item_id: id,
                    id: 'edit_queue',
                    value: 1
                });
            }
            else if($(this).html() == 'Remove from Queue') {
                $('#edit_queue_span').html('Not in Queue');
                $('#edit_queue_span').attr('class', '');
                $(this).html('Add to Queue');
                $.post(base_url + url, {
                    item_id: id,
                    id: 'edit_queue',
                    value: 0
                });
            }
        });
        $('.edit,.textarea_edit,.select_edit,.edit_money,.edit_title').bind('keydown', function(event) {
            object = this;
            if(event.keyCode==9) {
                $(this).find("input").blur();
                $(this).find("textarea").blur();
                $(this).find("select").blur();
                var nextBox='';
                if ($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").index(this) == ($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").length-1)) { //at last box
                    nextBox=$(".edit:first"); //last box, go to first
                }
                else {
                    nextBox = $(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").get($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").index(this)+1);
                }
                $(nextBox).click();
                return false;
            }
        })
        .If(function() {
                return ($(this).attr('class') == 'textarea_edit') ? true : false;
        })
        .editable(base_url + url, {
                submitdata: {
                    item_id: id
                },
                type: 'textarea',
                rows: '10',
                cols: '70',
                cssclass: 'inplace_field',
                onblur: 'submit',
                onedit: function () {
                    isDirty = true;
                },
                callback: function(res) {
                    cb_messages($(this));
                }
            })
        .ElseIf(function() { //Gender select field
                return ($(this).attr('class') == 'select_edit' && $(this).attr('id') == 'gender') ? true : false;
            })
            .editable(base_url + url, {
                data: gender_data, //" {0:'Women',1:'Men',2:'Unisex'} ",
                submitdata: {
                    item_id: id
                    },
                type: 'select',
                onblur: 'submit',
                callback: function(value) {
                    json = gender_json; //[{'value':'Women'},{'value':'Men'},{'value':'Unisex'}]; //order: 0,1
                    this.innerHTML = json[value].value;
                }
            })
        /**.ElseIf(function() { //Item Location select field
                return ($(this).attr('class') == 'select_edit' && $(this).attr('id') == 'item_location') ? true : false;
            })
            .editable(base_url + url, {
                data: location_data, //" {0:'Women',1:'Men',2:'Unisex'} ",
                submitdata: {
                    item_id: id
                    },
                type: 'select',
                onblur: 'submit',
                callback: function(value) {
                    json = location_json; //[{'value':'Women'},{'value':'Men'},{'value':'Unisex'}]; //order: 0,1
                    this.innerHTML = json[value].value;
                }
            })*/
        .ElseIf(function() { //money field
                return ($(this).attr('class') == 'edit_money') ? true : false;
            })
            .editable(base_url + url, {
                submitdata: {
                    item_id: id,
                    type: 'money'
                },
                type: 'text',
                height: '16px',
                select : true,
                cssclass: 'inplace_field',
                onblur: 'submit',
                onedit: function() {
                    isDirty = true;
                },
                callback: function(res) {
                    cb_messages($(this));
                }
            })
        .ElseIf(function() {
            return ($(this).attr('class') == 'edit_title') ? true : false;
        })
            .editable(base_url + url, {
                submitdata: {
                    item_id: id,
                    type: 'text'
                },
                type: 'text',
                select: true,
                height: '18px',
                width: '500px',
                cssclass: 'inplace_field',
                onblur: 'submit',
                onedit: function() {
                    isDirty = true;
                },
                callback: function(res) {
                    cb_messages($(this));
                }

            })
        .Else() //default input text field
            .editable(base_url + url, {
                submitdata: {
                    item_id: id
                },
                height: '16px',
                type: 'text',
                select: true,
                cssclass: 'inplace_field',
                onblur: 'submit',
                onedit: function() {
                    isDirty = true;
                },
                callback: function(res) {
                    cb_messages($(this));
                }
            });
    });