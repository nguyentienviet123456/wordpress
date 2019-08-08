;(function ($) {
    "use strict";
    
    function fami_is_equivalent(a, b) {
        // Create arrays of property names
        var aProps = Object.getOwnPropertyNames(a);
        var bProps = Object.getOwnPropertyNames(b);
        
        // If number of properties is different,
        // objects are not equivalent
        if (aProps.length != bProps.length) {
            return false;
        }
        
        for (var i = 0; i < aProps.length; i++) {
            var propName = aProps[i];
            
            // If values of same property are not equal,
            // objects are not equivalent
            if (a[propName] !== b[propName]) {
                return false;
            }
        }
        
        // If we made it this far, objects
        // are considered equivalent
        return true;
    }
    
    function fami_get_cur_variations_img(product_variations, cur_img_id) {
        var img = funiter_toolkit_woo_atts['product_mobile']['default_thumb'];
        for (var i = 0; i < product_variations.length; i++) {
            var this_img_id = product_variations[i]['image_id'];
            if (this_img_id == cur_img_id) {
                if (typeof product_variations[i]['image']['thumb_src'] != 'undefined' && typeof product_variations[i]['image']['thumb_src'] != false) {
                    img['url'] = product_variations[i]['image']['thumb_src'];
                    img['width'] = product_variations[i]['image']['thumb_src_w'];
                    img['height'] = product_variations[i]['image']['thumb_src_h'];
                }
                else if (typeof product_variations[i]['image']['src'] != 'undefined' && typeof product_variations[i]['image']['src'] != false) {
                    img['url'] = product_variations[i]['image']['src'];
                    img['width'] = product_variations[i]['image']['src_w'];
                    img['height'] = product_variations[i]['image']['src_h'];
                }
                else if (typeof product_variations[i]['image']['url'] != 'undefined' && typeof product_variations[i]['image']['url'] != false) {
                    img['url'] = product_variations[i]['image']['url'];
                }
                break;
            }
        }
        
        return img;
    }
    
    function variations_custom() {
        $('.variations_form').find('.data-val').html('');
        $('.variations_form select').each(function () {
            var _this = $(this);
            var all_product_data = _this.closest('form').attr('data-product_variations');
            all_product_data = JSON.parse(all_product_data);
            _this.find('option').each(function () {
                var _ID = $(this).parent().data('id'),
                    _data = $(this).data(_ID),
                    _value = $(this).attr('value'),
                    _name = $(this).text(),
                    _data_type = $(this).data('type'),
                    _itemclass = _data_type;
                
                if ($(this).is(':selected')) {
                    _itemclass += ' active';
                }
                if (_value !== '') {
                    if (_data_type == 'color') {
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" style="background: ' + _data + ';" data-value="' + _value + '"></a>');
                    } else if (_data_type == 'photo') {
                        var img_url = $.trim(_data).replace('url(', '').replace(')', '');
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" data-value="' + _value + '"><img src="' + img_url + '"></a>');
                    } else {
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" data-value="' + _value + '">' + _name + '</a>');
                    }
                }
            });
        });
        
        // Real mobile
        var $singleSummary = $('.single-product-mobile .summary');
        var $variationsForm = $singleSummary.find('.variations_form');
        if ($('.single-product-mobile').length && $variationsForm.length) {
            var product_variations = JSON.parse($variationsForm.attr('data-product_variations'));
            var cur_img_id = $variationsForm.attr('current-image');
            var toggle_variations_select_html = '';
            var cur_img_html = '';
            var cur_variation_price_html = '';
            var qty_html = '';
            var price_and_qty_html = '';
            var variations_select_box_html = '';
            
            // console.log(product_variations);
            
            if (!$singleSummary.find('.variations-box-wrap').length) {
                $singleSummary.prepend('<div class="variations-box-wrap box-wrap"><div class="box-inner"></div></div>');
            }
            
            var reset_text = $variationsForm.find('.reset_variations').text();
            var add_to_cart_text = $variationsForm.find('.single_add_to_cart_button').text();
            var $boxInner = $singleSummary.find('.box-inner');
            var $boxContentInner = $boxInner.find('.box-content-inner');
            
            var i = 0;
            var k = 0;
            $variationsForm.find('td.value').each(function () {
                // Index variations group
                i++;
                $(this).find('.data-val').attr('data-variations-group-num', i);
                
                // Index variations values
                $(this).find('.data-val .change-value').each(function () {
                    k++;
                    $(this).attr('data-change-value-num', k);
                });
                
                var $thisTr = $(this).closest('tr');
                var label_html = '<label>' + $thisTr.find('td.label').text() + '</label>';
                var value_html = '<div data-variations-group-num="' + i + '" class="variations-group-clone-inner" class="data-val-clone">' + $(this).find('.data-val').html() + '</div>';
                variations_select_box_html += '<div class="variations-group-clone">' + label_html + value_html + '</div>';
            });
            
            // Get current image
            var cur_img = fami_get_cur_variations_img(product_variations, cur_img_id);
            var reset_variations_btn_html = '<a href="#" class="reset_variations_clone">' + reset_text + '</a>';
            var add_to_cart_btn_html = '<button type="button" class="single_add_to_cart_button-clone button alt">' + add_to_cart_text + '</button>';
            
            cur_variation_price_html = '';
            if ($variationsForm.find('.woocommerce-variation-price').length) {
                cur_variation_price_html = $variationsForm.find('.woocommerce-variation-price').html();
            }
            
            var is_update = $boxContentInner.find('.variations-groups-clone-wrap').length > 0;
            if (is_update) {
                $boxContentInner.find('.variations-groups-clone-wrap').html(variations_select_box_html);
                if (cur_variation_price_html != '') {
                    if (!$boxContentInner.find('.woocommerce-variation-price-clone').length) {
                        if (!$boxContentInner.find('.price-and-qty-wrap').length) {
                            $boxContentInner.find('.variations-groups-clone-wrap').before('<div class="price-and-qty-wrap"></div>');
                            $boxContentInner.find('.price-and-qty-wrap').append('<div class="woocommerce-variation-price-clone woocommerce-variation-price"></div>');
                        }
                        else {
                            $boxContentInner.find('.price-and-qty-wrap').append('<div class="woocommerce-variation-price-clone woocommerce-variation-price"></div>');
                        }
                        // $boxContentInner.find('.variations-groups-clone-wrap').before('<div class="woocommerce-variation-price-clone woocommerce-variation-price"></div>');
                    }
                    $boxContentInner.find('.woocommerce-variation-price-clone').html(cur_variation_price_html);
                }
                else {
                    $boxContentInner.find('.woocommerce-variation-price-clone').remove();
                }
                
                if (cur_img['url'] != '') {
                    if (!$boxContentInner.find('.current-img-wrap').length) {
                        $boxContentInner.prepend('<div class="current-img-wrap"></div>');
                    }
                    $boxContentInner.find('.current-img-wrap').html('<img width="' + cur_img['width'] + '" height="' + cur_img['width'] + '" src="' + cur_img['url'] + '" />');
                }
                else {
                
                }
                
                if (!$boxContentInner.find('.reset_variations_clone').length) {
                    $boxContentInner.append(reset_variations_btn_html);
                }
            }
            
            // The first
            else {
                if (variations_select_box_html != '') {
                    var close_btn_html = '<a href="#" class="close-box-content">X</a>';
                    
                    variations_select_box_html = '<div class="variations-groups-clone-wrap">' + variations_select_box_html + '</div>';
                    
                    if (cur_img['url'] != '') {
                        cur_img_html += '<div class="current-img-wrap"><img width="' + cur_img['width'] + '" height="' + cur_img['width'] + '" src="' + cur_img['url'] + '" /></div>';
                    }
                    
                    if (cur_variation_price_html != '') {
                        cur_variation_price_html = '<div class="woocommerce-variation-price-clone woocommerce-variation-price">' + cur_variation_price_html + '</div>';
                    }
                    
                    if ($singleSummary.find('.quantity input.qty').length) {
                        var qty_text = $singleSummary.find('.quantity .qty-label').text();
                        var cur_qty = $singleSummary.find('input.qty').val();
                        qty_html = '<span class="qty-label">' + qty_text + '</span>' +
                            '<div class="control">' +
                            '     <a class="btn-number qtyminus quantity-minus" href="#">-</a>' +
                            '     <input type="text" data-step="1" min="1" max="" name="quantity" value="' + cur_qty + '" class="input-qty input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">' +
                            '     <a class="btn-number qtyplus quantity-plus" href="#" style="pointer-events: auto;">+</a>' +
                            '</div>';
                        qty_html = '<div class="quantity">' + qty_html + '</div>';
                    }
                    
                    if (cur_variation_price_html != '' || qty_html != '') {
                        price_and_qty_html = '<div class="price-and-qty-wrap">' + qty_html + cur_variation_price_html + '</div>';
                    }
                    
                    variations_select_box_html = '<div class="variations-select-clone-wrap box-content"><div class="box-content-inner">' + cur_img_html + price_and_qty_html + variations_select_box_html + ' ' + close_btn_html + ' ' + add_to_cart_btn_html + reset_variations_btn_html + '</div></div>';
                }
                toggle_variations_select_html = '<a href="#" class="toggle-variations-select-mobile">' + funiter_toolkit_woo_atts['text']['select_variation'] + '</a>';
                
                $singleSummary.find('.variations-box-wrap .box-inner').html(toggle_variations_select_html + variations_select_box_html);
            }
            
        }
        
    }
    
    $(document).on('click', '.toggle-variations-select-mobile', function (e) {
        var $this = $(this);
        var $thisBox = $this.closest('.box-wrap');
        $('html, body').toggleClass('fami-dim-display');
        $thisBox.toggleClass('show-box-content');
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.close-box-content', function (e) {
        $('html, body').removeClass('fami-dim-display');
        $(this).closest('.box-wrap').removeClass('show-box-content');
        e.preventDefault();
        return false;
    });
    
    // Close box content when click outside
    $(document).on('click', function (e) {
        var $box = $('.single-product-mobile .variations-box-wrap');
        
        if ($box.length) {
            if ($(e.target).find('.variations-box-wrap').length) {
                console.log('go here 1');
                $box.find('.close-box-content').trigger('click');
            }
            else {
            
            }
        }
        
    });
    
    $(document).on('click', '.reset_variations_clone', function (e) {
        $('.summary .variations_form .reset_variations').trigger('click');
        $('.summary .variations-select-clone-wrap .woocommerce-variation-price-clone').remove();
        $(this).remove();
        e.preventDefault();
        return false;
    });
    
    // Change clone qty
    $(document).on('change', '.variations-select-clone-wrap input.qty', function () {
        var this_val = $(this).val();
        $('.summary .variations_form input.qty').val(this_val).trigger('change');
    });
    
    // Click add to cart clone button
    $(document).on('click', '.variations-select-clone-wrap .single_add_to_cart_button-clone', function (e) {
        $('.summary .variations_form .single_add_to_cart_button').trigger('click');
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.variations-group-clone .change-value', function (e) {
        var $this = $(this);
        var $thisBoxContentInner = $this.closest('.box-content-inner');
        var change_val_num = $this.attr('data-change-value-num');
        $('.variations .change-value[data-change-value-num="' + change_val_num + '"]').trigger('click');
        
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.reset_variations', function () {
        $('.variations_form').find('.change-value').removeClass('active');
    });
    $(document).on('click', '.variations_form .change-value', function (e) {
        var _this = $(this),
            _change = _this.data('value');
        
        _this.parent().parent().children('select').val(_change).trigger('change');
        _this.addClass('active').siblings().removeClass('active');
        e.preventDefault();
    });
    $(document).on('woocommerce_variation_has_changed wc_variation_form', function () {
        variations_custom();
    });
    $(document).on('qv_loader_stop', function () {
        variations_custom();
    });
    $(document).ajaxComplete(function (event, xhr, settings) {
        variations_custom();
    });
    
})(jQuery);