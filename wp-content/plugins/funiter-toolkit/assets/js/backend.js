jQuery(document).ready(function ($) {
    "use strict";
    
    function funiter_toolkit_get_url_param(sParam, url) {
        var sPageURL = url, // window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
        
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    }
    
    var funiter_toolkit_file_frame = null;  // variable for the wp.media famiau_file_frame
    
    function funiter_toolkit_open_media_uploader() {
        // attach a click event (or whatever you want) to some element on your page
        $(document).on('click', '.funiter_toolkit-upload-wrap .funiter_toolkit-upload-btn', function (e) {
            
            var $this = $(this);
            var thisWrap = $this.closest('.funiter_toolkit-upload-wrap');
            var multi = thisWrap.attr('data-multi') == 'yes';
            var results_selector = $this.attr('data-results_selector');
            
            // if the funiter_toolkit_file_frame has already been created, just reuse it
            if (funiter_toolkit_file_frame) {
                funiter_toolkit_file_frame.open();
                return;
            }
            
            funiter_toolkit_file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).attr('data-uploader_title'),
                button: {
                    text: $(this).attr('data-uploader_button_text')
                },
                library: {
                    type: 'image'
                    // uploadedTo: wp.media.view.settings.post.id
                },
                multiple: multi // set this to true for multiple file selection
            });
            
            funiter_toolkit_file_frame.on('select', function () {
                var selection_imgs = funiter_toolkit_file_frame.state().get('selection').toJSON();
                var remove_img_btn_html = '<a href="#" class="funiter_toolkit-remove-img-btn funiter_toolkit-remove-btn"><i class="fa fa-times"></i></a>';
                
                if (!thisWrap.find('.funiter_toolkit-imgs-preview-wrap').length) {
                    thisWrap.prepend('<div class="funiter_toolkit-imgs-preview-wrap funiter_toolkit-sortable"></div>');
                }
                
                var attachment_ids = '';
                for (var i = 0; i < selection_imgs.length; i++) {
                    var attachment = selection_imgs[i];
                    
                    var img_full = {};
                    var img_url_full = attachment['url'];
                    var img_url = img_url_full;
                    var width = attachment['width'];
                    var height = '';
                    
                    if (typeof attachment['sizes']['thumbnail'] != 'undefined' && typeof attachment['sizes']['thumbnail'] != false) {
                        img_url = attachment['sizes']['thumbnail']['url'];
                        width = attachment['sizes']['thumbnail']['width'];
                        height = attachment['sizes']['thumbnail']['height'];
                    }
                    else {
                    
                    }
                    
                    if ($(results_selector).length) {
                        if (attachment_ids == '') {
                            attachment_ids = attachment['id'];
                        }
                        else {
                            attachment_ids += ',' + attachment['id'];
                        }
                    }
                    
                    if (!thisWrap.find('.funiter_toolkit-img-preview-' + attachment['id']).length) {
                        if (typeof attachment['sizes']['full'] != 'undefined' && typeof attachment['sizes']['full'] != false) {
                            img_full = attachment['sizes']['full'];
                        }
                        else {
                            img_full = {
                                url: img_url_full,
                                height: '',
                                width: ''
                            }
                        }
                        img_full = JSON.stringify(img_full);
                        if (multi) {
                            thisWrap.find('.funiter_toolkit-imgs-preview-wrap').append('<div class="funiter_toolkit-img-preview-wrap">' +
                                '<img width="' + width + '" height="' + height + '" data-attachment_id="' + attachment['id'] + '" data-img_full="' + encodeURIComponent(img_full) + '" class="funiter_toolkit-img-preview funiter_toolkit-img-preview-' + attachment['id'] + '" src="' + img_url + '" /> ' + remove_img_btn_html + '</div>');
                        }
                        else {
                            thisWrap.find('.funiter_toolkit-imgs-preview-wrap').html('<div class="funiter_toolkit-img-preview-wrap">' +
                                '<img width="' + width + '" height="' + height + '" data-attachment_id="' + attachment['id'] + '" data-img_full="' + encodeURIComponent(img_full) + '" class="funiter_toolkit-img-preview funiter_toolkit-img-preview-' + attachment['id'] + '" src="' + img_url + '" /> ' + remove_img_btn_html + '</div>');
                        }
                    }
                    else {
                    
                    }
                    
                    if ($(results_selector).length) {
                        $(results_selector).val(attachment_ids);
                    }
                    
                }
                // funiter_toolkit_update_main_img_preview(thisWrap);
            });
            
            funiter_toolkit_file_frame.open();
            
            e.preventDefault();
        });
        
        // Remove preview image
        $(document).on('click', '.funiter_toolkit-img-preview-wrap .funiter_toolkit-remove-img-btn', function (e) {
            var $this = $(this);
            var thisImgWrap = $this.closest('.funiter_toolkit-img-preview-wrap');
            var thisUploadWrap = $this.closest('.funiter_toolkit-upload-wrap');
            var results_selector = thisUploadWrap.find('.funiter_toolkit-upload-btn').attr('data-results_selector');
            thisImgWrap.remove();
            if ($(results_selector).length) {
                $(results_selector).val('');
            }
            
            e.preventDefault();
        });
    }
    
    funiter_toolkit_open_media_uploader();
    
    // Clear image on submit #addtag form
    $(document).ajaxComplete(function (event, xhr, settings) {
        if ($('#tax_image').length) {
            if (typeof settings['data'] != 'undefined' && typeof settings['data'] != false) {
                var action_name = funiter_toolkit_get_url_param('action', settings['data']);
                if (action_name == 'add-tag') {
                    $('#tax_image').val('');
                    var $uploadWrap = $('.funiter_toolkit-upload-wrap [data-results_selector="#tax_image"]').closest('.funiter_toolkit-upload-wrap');
                    $uploadWrap.find('.funiter_toolkit-img-preview-wrap').remove();
                }
            }
        }
    });
    
});