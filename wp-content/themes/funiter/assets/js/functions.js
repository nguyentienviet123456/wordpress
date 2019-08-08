;(function ($, window, document, undefined) {
    'use strict';
    // using static methods
    
    var $body = $('body'),
        has_rtl = $body.hasClass('rtl');
    
    function funiter_main_header_sticky() {
        if (!$('.header-sticky').length) {
            return false;
        }
        if ($('.header-sticky:not(.vertical_always-open)').length) {
            var mainHeader = $('.header-wrap-stick');
            var top_spacing = 0;
            var admin_bar_h = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
            top_spacing += admin_bar_h;
            mainHeader.sticky({topSpacing: top_spacing});
        } else {
            var previousScroll = 0,
                header_position = $('.header-sticky .header-position'),
                headerOrgOffset = header_position.offset().top;
            if ($('.verticalmenu-content').length > 0) {
                headerOrgOffset = headerOrgOffset + $('.verticalmenu-content').outerHeight();
            }
            if ($(window).width() > 1024) {
                $(document).on('scroll', function (ev) {
                    var currentScroll = $(this).scrollTop();
                    if (currentScroll > headerOrgOffset) {
                        if (currentScroll > previousScroll) {
                            header_position.addClass('hide-header');
                        } else {
                            header_position.removeClass('hide-header');
                            header_position.addClass('fixed');
                        }
                    } else {
                        header_position.removeClass('fixed');
                    }
                    previousScroll = currentScroll;
                });
            }
        }
        
    }
    
    function funiter_sticky_single() {
        var _previousScroll = 0,
            _headerOrgOffset = $('#header').outerHeight();
        
        if ($(window).width() > 1024) {
            $(document).on('scroll', function (ev) {
                var _currentScroll = $(this).scrollTop();
                
                if (_currentScroll > _headerOrgOffset) {
                    if (_currentScroll > _previousScroll) {
                        $('body').addClass('show-sticky_info_single');
                    }
                } else {
                    $('body').removeClass('show-sticky_info_single');
                }
                _previousScroll = _currentScroll;
            });
        }
    };
    
    function funiter_add_to_cart_single() {
        /* SINGLE ADD TO CART */
        $(document).on('click', '.product:not(.product-type-external) .single_add_to_cart_button', function (e) {
            
            e.preventDefault();
            var _this = $(this);
            var _product_id = _this.val();
            var _form = _this.closest('form');
            var _form_data = _form.serialize();
            
            if (_product_id != '') {
                var _data = 'add-to-cart=' + _product_id + '&' + _form_data;
            } else {
                var _data = _form_data;
            }
            if (_this.is('.disabled') || _this.is('.wc-variation-selection-needed')) {
                return false;
            }
            $(this).addClass('loading').removeClass('added');
            if ($('.summary .variations-select-clone-wrap .single_add_to_cart_button-clone').length) {
                $('.summary .variations-select-clone-wrap .single_add_to_cart_button-clone').addClass('loading').removeClass('added');
            }
            var atcUrl = wc_add_to_cart_params.wc_ajax_url.toString().replace('wc-ajax=%%endpoint%%', 'add-to-cart=' + _product_id + '&ajax-add-to-cart=1');
            $.ajax({
                type: 'POST',
                url: atcUrl,
                data: _data,
                dataType: 'html',
                cache: false,
                headers: {'cache-control': 'no-cache'},
                success: function () {
                    $(document.body).trigger('wc_fragment_refresh');
                    $('.single_add_to_cart_button, .funiter-single-add-to-cart-fixed-top').removeClass('loading').addClass('added');
                    $('.summary .variations-select-clone-wrap .single_add_to_cart_button-clone').removeClass('loading').addClass('added');
                    $(document.body).trigger('added_to_cart');
                }
            });
        });
    }
    
    function funiter_clone_append_category() {
        if ($('.product-category').length > 0) {
            $('.main-content .funiter-products').prepend('<ul class="list-cate"></ul>')
            $('.product-category').detach().prependTo('.list-cate');
            
        }
    }
    
    function funiter_fix_vc_full_width_row() {
        if ($('body.rtl').length) {
            var $elements = $('[data-vc-full-width="true"]');
            $.each($elements, function () {
                var $el = $(this);
                $el.css('right', $el.css('left')).css('left', '');
            });
        }
    }
    
    function funiter_force_vc_full_width_row_rtl() {
        var _elements = $('[data-vc-full-width="true"]');
        $.each(_elements, function (key, item) {
            var $this = $(this);
            if ($this.parent('[data-vc-full-width="true"]').length > 0) {
                return;
            } else {
                var this_left = $this.css('left'),
                    this_child = $this.find('[data-vc-full-width="true"]');
                
                if (this_child.length > 0) {
                    $this.css({
                        'left': '',
                        'right': this_left
                    });
                    this_child.css({
                        'left': 'auto',
                        'padding-left': this_left.replace('-', ''),
                        'padding-right': this_left.replace('-', ''),
                        'right': this_left
                    });
                } else {
                    $this.css({
                        'left': 'auto',
                        'right': this_left
                    });
                }
            }
        }), $(document).trigger('funiter-force-vc-full-width-row-rtl', _elements);
    };
    
    function funiter_fix_full_width_row_rtl() {
        if (has_rtl) {
            $('.chosen-container').each(function () {
                $(this).addClass('chosen-rtl');
            });
            $(document).on('vc-full-width-row', function () {
                funiter_force_vc_full_width_row_rtl();
            });
        }
    };
    $.fn.funiter_responsive_tabs = function () {
        var _this = $(this);
        _this.on('funiter_responsive_tabs', function () {
            _this.each(function () {
                var tab_wrapper = $(this);
                
                var wrapper_width = tab_wrapper.width(),
                    dropdown_width = tab_wrapper.find('li.dropdown').width(),
                    width_sum = 0;
                
                tab_wrapper.find('>li:not(li.dropdown)').each(function () {
                    width_sum += $(this).outerWidth();
                    if (width_sum + dropdown_width > wrapper_width)
                        $(this).hide();
                    else
                        $(this).show();
                    
                });
                
                var hidden_lists = tab_wrapper.find('>li:not(li.dropdown):not(:visible)');
                if (hidden_lists.length > 0) {
                    $('li.dropdown').show();
                } else {
                    $('li.dropdown').hide();
                }
                
                tab_wrapper.find('ul.dropdown-menu').html(hidden_lists.clone().show());
            });
        }).trigger('funiter_responsive_tabs');
        $(window).on('resize', function () {
            _this.trigger('funiter_responsive_tabs');
        });
        $(document).on('click', '.funiter-tabs .dropdown-menu li', function () {
            $(this).closest('.dropdown').addClass('active');
        });
    };
    $.fn.funiter_sticky_menu = function () {
        var $this = $(this);
        $this.on('funiter_sticky_menu', function () {
            $this.each(function () {
                var previousScroll = 0,
                    header = $(this).closest('.header'),
                    header_position = $(this).find('.header-position'),
                    headerOrgOffset = header_position.offset().top;
                
                if ($(this).find('.verticalmenu-content').length > 0)
                    headerOrgOffset = headerOrgOffset + $(this).find('.verticalmenu-content').outerHeight();
                
                if ($(window).width() > 1024) {
                    header.css('height', header.outerHeight());
                    $(document).on('scroll', function (ev) {
                        var currentScroll = $(this).scrollTop();
                        if (currentScroll > headerOrgOffset) {
                            if (currentScroll > previousScroll) {
                                header_position.addClass('hide-header');
                            } else {
                                header_position.removeClass('hide-header');
                                header_position.addClass('fixed');
                            }
                        } else {
                            header_position.removeClass('fixed');
                        }
                        previousScroll = currentScroll;
                    });
                } else {
                    header.css("height", "auto");
                }
            })
        }).trigger('funiter_sticky_menu');
        $(window).on('resize', function () {
            $this.trigger('funiter_sticky_menu');
        });
    }
    
    /* Category */
    $.fn.funiter_vertical_menu = function () {
        /* SHOW ALL ITEM */
        var _countLi = 0,
            _verticalMenu = $(this).find('.vertical-menu'),
            _blockNav = $(this).closest('.block-nav-category'),
            _blockTitle = $(this).find('.block-title');
        
        $(this).each(function () {
            var _dataItem = $(this).data('items') - 1;
            _countLi = $(this).find('.vertical-menu>li').length;
            
            if (_countLi > (_dataItem + 1)) {
                $(this).addClass('show-button-all');
            }
            $(this).find('.vertical-menu>li').each(function (i) {
                _countLi = _countLi + 1;
                if (i > _dataItem) {
                    $(this).addClass('link-other');
                }
            })
        });
        $(this).find('.vertical-menu').each(function () {
            var _main = $(this);
            _main.children('.menu-item.parent').each(function () {
                var curent = $(this).find('.submenu');
                $(this).children('.toggle-submenu').on('click', function () {
                    $(this).parent().children('.submenu').slideToggle(500);
                    _main.find('.submenu').not(curent).slideUp(500);
                    $(this).parent().toggleClass('show-submenu');
                    _main.find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                });
                var next_curent = $(this).find('.submenu');
                next_curent.children('.menu-item.parent').each(function () {
                    var child_curent = $(this).find('.submenu');
                    $(this).children('.toggle-submenu').on('click', function () {
                        $(this).parent().parent().find('.submenu').not(child_curent).slideUp(500);
                        $(this).parent().children('.submenu').slideToggle(500);
                        $(this).parent().parent().find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                        $(this).parent().toggleClass('show-submenu');
                    })
                });
            });
        });
        /* VERTICAL MENU ITEM */
        if (_verticalMenu.length > 0) {
            $(document).on('click', '.open-cate', function (e) {
                _blockNav.find('li.link-other').each(function () {
                    $(this).slideDown();
                });
                $(this).addClass('close-cate').removeClass('open-cate').html($(this).data('closetext'));
                e.preventDefault();
            });
            $(document).on('click', '.close-cate', function (e) {
                _blockNav.find('li.link-other').each(function () {
                    $(this).slideUp();
                });
                $(this).addClass('open-cate').removeClass('close-cate').html($(this).data('alltext'));
                e.preventDefault();
            });
            
            _blockTitle.on('click', function (e) {
                $(this).toggleClass('active');
                $(this).parent().find('.verticalmenu-content').stop().slideToggle();
                e.preventDefault();
                e.stopPropagation();
            });
            $(document).on('click', function (e) {
                var container = $('.block-nav-category');
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.find('.verticalmenu-content').slideUp();
                }
            });
        }
    };
    /* Animate */
    $.fn.funiter_animation_tabs = function (_tab_animated) {
        $(this).on('funiter_animation_tabs', function () {
            _tab_animated = (_tab_animated == undefined || _tab_animated == "") ? '' : _tab_animated;
            if (_tab_animated == "") {
                return;
            }
            $(this).find('.owl-slick .slick-active, .product-list-grid .product-item').each(function (i) {
                var _this = $(this),
                    _style = _this.attr('style'),
                    _delay = i * 200;
                
                _style = (_style == undefined) ? '' : _style;
                _this.attr('style', _style +
                    ';-webkit-animation-delay:' + _delay + 'ms;'
                    + '-moz-animation-delay:' + _delay + 'ms;'
                    + '-o-animation-delay:' + _delay + 'ms;'
                    + 'animation-delay:' + _delay + 'ms;'
                ).addClass(_tab_animated + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                    _this.removeClass(_tab_animated + ' animated');
                    _this.attr('style', _style);
                });
            });
        }).trigger('funiter_animation_tabs');
    };
    $.fn.funiter_init_carousel = function () {
        $(this).on('funiter_init_carousel', function () {
            $(this).not('.slick-initialized').each(function () {
                var _this = $(this),
                    _responsive = _this.data('responsive'),
                    _config = [];
                
                if (has_rtl) {
                    _config.rtl = true;
                }
                if (_this.hasClass('slick-vertical')) {
                    _config.prevArrow = '<span class="fa fa-angle-up prev"></span>';
                    _config.nextArrow = '<span class="fa fa-angle-down next"></span>';
                } else {
                    _config.prevArrow = '<span class="fa fa-angle-left prev"></span>';
                    _config.nextArrow = '<span class="fa fa-angle-right next"></span>';
                }
                _config.responsive = _responsive;
                
                _this.on('init', function (event, slick, direction) {
                    funiter_popover_button();
                });
                _this.slick(_config);
                _this.on('afterChange', function (event, slick, direction) {
                    _this.find('.lazy').funiter_init_lazy_load();
                });
            });
        }).trigger('funiter_init_carousel');
    };
    $.fn.funiter_product_thumb = function () {
        $(this).on('funiter_product_thumb', function () {
            $(this).not('.slick-initialized').each(function () {
                var _this = $(this),
                    _responsive = JSON.parse(funiter_global_frontend.data_responsive),
                    _config = JSON.parse(funiter_global_frontend.data_slick);
                
                console.log(_config);
                
                if (has_rtl) {
                    _config.rtl = true;
                }
                _config.infinite = false;
                _config.prevArrow = '<span class="fa fa-angle-left prev"></span>';
                _config.nextArrow = '<span class="fa fa-angle-right next"></span>';
                _config.responsive = _responsive;
                
                _this.slick(_config);
            });
        }).trigger('funiter_product_thumb');
    };
    $.fn.funiter_countdown = function () {
        $(this).on('funiter_countdown', function () {
            $(this).each(function () {
                var _this = $(this),
                    _text_countdown = '';
                
                _this.countdown(_this.data('datetime'), function (event) {
                    _text_countdown = event.strftime(
                        '<span class="days"><span class="number">%D</span><span class="text">' + funiter_global_frontend.countdown_day + '</span></span>' +
                        '<span class="hour"><span class="number">%H</span><span class="text">' + funiter_global_frontend.countdown_hrs + '</span></span>' +
                        '<span class="mins"><span class="number">%M</span><span class="text">' + funiter_global_frontend.countdown_mins + '</span></span>' +
                        '<span class="secs"><span class="number">%S</span><span class="text">' + funiter_global_frontend.countdown_secs + '</span></span>'
                    );
                    _this.html(_text_countdown);
                });
            });
        }).trigger('funiter_countdown');
    };
    $.fn.funiter_init_lazy_load = function () {
        var _this = $(this);
        _this.each(function () {
            var _config = [];
            
            _config.beforeLoad = function (element) {
                if (element.is('div') == true) {
                    element.addClass('loading-lazy');
                } else {
                    element.parent().addClass('loading-lazy');
                }
            };
            _config.afterLoad = function (element) {
                if (element.is('div') == true) {
                    element.removeClass('loading-lazy');
                } else {
                    element.parent().removeClass('loading-lazy');
                }
            };
            _config.effect = "fadeIn";
            _config.enableThrottle = true;
            _config.throttle = 250;
            _config.effectTime = 600;
            if ($(this).closest('.megamenu').length > 0)
                _config.delay = 0;
            $(this).lazy(_config);
        });
    };
    /* Add To Cart Button */
    $.fn.funiter_alert_variable_product = function () {
        $(this).on('funiter_alert_variable_product', function () {
            if ($(this).hasClass('disabled')) {
                $(this).popover({
                    content: 'Plz Select option before Add To Cart.',
                    trigger: 'hover',
                    placement: 'bottom'
                });
            } else {
                $(this).popover('destroy');
            }
        }).trigger('funiter_alert_variable_product');
    };
    $(document).change(function () {
        if ($('.single_add_to_cart_button').length > 0) {
            $('.single_add_to_cart_button').funiter_alert_variable_product();
        }
    });
    /* funiter_init_dropdown */
    $(document).on('click', function (event) {
        var _target = $(event.target).closest('.funiter-dropdown'),
            _parent = $('.funiter-dropdown');
        
        if (_target.length > 0) {
            _parent.not(_target).removeClass('open');
            if (
                $(event.target).is('[data-funiter="funiter-dropdown"]') ||
                $(event.target).closest('[data-funiter="funiter-dropdown"]').length > 0
            ) {
                _target.toggleClass('open');
                event.preventDefault();
            }
        } else {
            $('.funiter-dropdown').removeClass('open');
        }
    });
    /* category product */
    $.fn.funiter_category_product = function () {
        $(this).each(function () {
            var _main = $(this);
            _main.find('.cat-parent').each(function () {
                if ($(this).hasClass('current-cat-parent')) {
                    $(this).addClass('show-sub');
                    $(this).children('.children').slideDown(400);
                }
                $(this).children('.children').before('<span class="carets"></span>');
            });
            _main.children('.cat-parent').each(function () {
                var curent = $(this).find('.children');
                $(this).children('.carets').on('click', function () {
                    $(this).parent().toggleClass('show-sub');
                    $(this).parent().children('.children').slideToggle(400);
                    _main.find('.children').not(curent).slideUp(400);
                    _main.find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                });
                var next_curent = $(this).find('.children');
                next_curent.children('.cat-parent').each(function () {
                    var child_curent = $(this).find('.children');
                    $(this).children('.carets').on('click', function () {
                        $(this).parent().toggleClass('show-sub');
                        $(this).parent().parent().find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                        $(this).parent().parent().find('.children').not(child_curent).slideUp(400);
                        $(this).parent().children('.children').slideToggle(400);
                    })
                });
            });
        });
    };
    $.fn.funiter_magnific_popup = function () {
        $('.product-video-button a').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            disableOn: false,
            fixedContentPos: false
        });
        $('.product-360-button a').magnificPopup({
            type: 'inline',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            disableOn: false,
            preloader: false,
            fixedContentPos: false,
            callbacks: {
                open: function () {
                    $(window).resize();
                },
            },
        });
        $('.block-user .acc-popup').magnificPopup({
            type: 'inline',
            mainClass: 'mfp-fade acc-fade',
            removalDelay: 160,
            disableOn: false,
            preloader: false,
            fixedContentPos: false,
            callbacks: {
                open: function () {
                    $(window).resize();
                },
            },
        });
    };
    
    function funiter_better_equal_elems($elem) {
        $elem.each(function () {
            if ($(this).find('.equal-elem').length) {
                $(this).find('.equal-elem').css({
                    'height': 'auto'
                });
                var _height = 0;
                $(this).find('.equal-elem').each(function () {
                    if (_height < $(this).height()) {
                        _height = $(this).height();
                    }
                });
                $(this).find('.equal-elem').height(_height);
            }
        });
    }
    
    //Single tabs desc
    $(document).on("click", '.button-togole', function (e) {
        var $this = $(this);
        $this.parent().addClass('tab-show');
        $('html').addClass('body-hide');
        e.preventDefault();
    });
    $(document).on("click", '.close-tab', function (e) {
        var $this = $(this);
        $('.tabs-mobile-content').removeClass('tab-show');
        $('html').removeClass('body-hide');
        e.preventDefault();
    });
    /* Funiter Ajax Tabs */
    $(document).on('click', '.funiter-tabs .tab-link a', function (e) {
        e.preventDefault();
        var _this = $(this),
            _ID = _this.data('id'),
            _tabID = _this.attr('href'),
            _ajax_tabs = _this.data('ajax'),
            _sectionID = _this.data('section'),
            _tab_animated = _this.data('animate'),
            _loaded = _this.closest('.tab-link').find('a.loaded').attr('href');
        
        if (_ajax_tabs == 1 && !_this.hasClass('loaded')) {
            $(_tabID).closest('.tab-container').addClass('loading');
            _this.parent().addClass('active').siblings().removeClass('active');
            $.ajax({
                type: 'POST',
                url: funiter_ajax_frontend.ajaxurl,
                data: {
                    action: 'funiter_ajax_tabs',
                    security: funiter_ajax_frontend.security,
                    id: _ID,
                    section_id: _sectionID,
                },
                success: function (response) {
                    if (response['success'] == 'ok') {
                        $(_tabID).html($(response['html']).find('.vc_tta-panel-body').html());
                        $(_tabID).closest('.tab-container').removeClass('loading');
                        $('[href="' + _loaded + '"]').removeClass('loaded');
                        $(_tabID).find('.funiter-countdown').funiter_countdown();
                        $(_tabID).find('.owl-slick').funiter_init_carousel();
                        if ($('.owl-slick .product-item').length > 0) {
                            funiter_hover_product_item_both($(_tabID).find('.owl-slick .row-item,' +
                                '.owl-slick .product-item.style-1,' +
                                '.owl-slick .product-item.style-2'));
                        }
                        if ($(_tabID).find('.variations_form').length > 0) {
                            $(_tabID).find('.variations_form').each(function () {
                                $(this).wc_variation_form();
                            });
                        }
                        $(_tabID).trigger('funiter_ajax_tabs_complete');
                        _this.addClass('loaded');
                        $(_loaded).html('');
                    } else {
                        $(_tabID).closest('.tab-container').removeClass('loading');
                        $(_tabID).html('<strong>Error: Can not Load Data ...</strong>');
                    }
                },
                complete: function () {
                    $(_tabID).addClass('active').siblings().removeClass('active');
                    setTimeout(function (args) {
                        $(_tabID).funiter_animation_tabs(_tab_animated);
                    }, 10);
                }
            });
        } else {
            _this.parent().addClass('active').siblings().removeClass('active');
            $(_tabID).addClass('active').siblings().removeClass('active');
            $(_tabID).funiter_animation_tabs(_tab_animated);
        }
    });
    $(document).on('click', 'a.backtotop', function (e) {
        $('html, body').animate({scrollTop: 0}, 800);
        e.preventDefault();
    });
    $(document).on('scroll', function () {
        if ($(window).scrollTop() > 200) {
            $('.backtotop').addClass('active');
        } else {
            $('.backtotop').removeClass('active');
        }
        if ($(window).scrollTop() > 0) {
            $('body').addClass('scroll-mobile');
        } else {
            $('body').removeClass('scroll-mobile');
        }
    });
    $('body').on('click', '.quantity .quantity-plus', function (e) {
        var _this = $(this).closest('.quantity').find('input.qty'),
            _value = parseInt(_this.val()),
            _max = parseInt(_this.attr('max')),
            _step = parseInt(_this.data('step')),
            _value = _value + _step;
        if (_max && _value > _max) {
            _value = _max;
        }
        _this.val(_value);
        _this.trigger("change");
        e.preventDefault();
    });
    $(document).on('change', function () {
        $('.quantity').each(function () {
            var _this = $(this).find('input.qty'),
                _value = _this.val(),
                _max = parseInt(_this.attr('max'));
            if (_value > _max) {
                $(this).find('.quantity-plus').css('pointer-events', 'none')
            } else {
                $(this).find('.quantity-plus').css('pointer-events', 'auto')
            }
        })
    });
    $('body').on('click', '.quantity .quantity-minus', function (e) {
        var _this = $(this).closest('.quantity').find('input.qty'),
            _value = parseInt(_this.val()),
            _min = parseInt(_this.attr('min')),
            _step = parseInt(_this.data('step')),
            _value = _value - _step;
        if (_min && _value < _min) {
            _value = _min;
        }
        if (!_min && _value < 0) {
            _value = 0;
        }
        _this.val(_value);
        _this.trigger("change");
        e.preventDefault();
    });
    $.fn.funiter_product_gallery = function () {
        $(this).each(function () {
            var _items = $(this).closest('.product-inner').data('items'),
                _main_slide = $(this).find('.product-gallery-slick'),
                _dot_slide = $(this).find('.gallery-dots');
            
            _main_slide.not('.slick-initialized').each(function () {
                var _this = $(this),
                    _config = [];
                
                if ($('body').hasClass('rtl')) {
                    _config.rtl = true;
                }
                _config.prevArrow = '<span class="fa fa-angle-left prev"></span>';
                _config.nextArrow = '<span class="fa fa-angle-right next"></span>';
                _config.cssEase = 'linear';
                _config.infinite = true;
                _config.fade = true;
                _config.slidesMargin = 0;
                _config.arrows = false;
                _config.asNavFor = _dot_slide;
                _this.slick(_config);
            });
            _dot_slide.not('.slick-initialized').each(function () {
                var _config = [];
                if ($('body').hasClass('rtl')) {
                    _config.rtl = true;
                }
                _config.slidesToShow = _items;
                _config.infinite = true;
                _config.focusOnSelect = true;
                _config.vertical = true;
                _config.slidesMargin = 0;
                _config.prevArrow = '<span class="fa fa-angle-up prev"></span>';
                _config.nextArrow = '<span class="fa fa-angle-down next"></span>';
                _config.asNavFor = _main_slide;
                _config.responsive = [
                    {
                        breakpoint: 1024,
                        settings: {
                            vertical: false,
                            prevArrow: '<span class="fa fa-angle-left prev"></span>',
                            nextArrow: '<span class="fa fa-angle-right next"></span>',
                        }
                    }
                ];
                $(this).slick(_config);
            })
        })
    };
    
    $.fn.funiter_show_all_product_desc = function () {
        var _height = $('#tab-description').outerHeight();
        if (_height > 800) {
            $('#tab-description').addClass('active');
            $('#tab-description').append('<button class="show-all"><i class="text">Show more</i></span><span class="fa fa-angle-down"></span></button>');
        }
        $(document).on('click', '.show-all', function () {
            var _this = $(this),
                _text = _this.find('.text');
            _this.toggleClass('active');
            _this.closest('#tab-description').toggleClass('active');
            if (_text.html() == 'Show more') {
                _text.html('Show less')
            } else {
                _text.html('Show more')
            }
        });
    };
    
    function funiter_hover_product_item_both($elem) {
        $elem.each(function () {
            var _winw = $(window).innerWidth();
            if (_winw > 1024) {
                $(this).on('mouseenter', function () {
                    $(this).closest('.slick-list').css({
                        'padding-left': '30px',
                        'padding-right': '30px',
                        'padding-bottom': '15px',
                        'margin-left': '-30px',
                        'margin-right': '-30px',
                        'margin-bottom': '-15px'
                    });
                });
                $(this).on('mouseleave', function () {
                    $(this).closest('.slick-list').css({
                        'padding-left': '0',
                        'padding-right': '0',
                        'padding-bottom': '0',
                        'margin-left': '0',
                        'margin-right': '0',
                        'margin-bottom': '0'
                    });
                });
            }
        });
    }
    
    $.fn.funiter_google_map = function () {
        var _this = $(this);
        _this.each(function () {
            var $id = $(this).data('id'),
                $latitude = $(this).data('latitude'),
                $longitude = $(this).data('longitude'),
                $zoom = $(this).data('zoom'),
                $map_type = $(this).data('map_type'),
                $title = $(this).data('title'),
                $address = $(this).data('address'),
                $phone = $(this).data('phone'),
                $email = $(this).data('email'),
                $hue = '',
                $saturation = '',
                $modify_coloring = false,
                $coinpo_map = {
                    lat: $latitude,
                    lng: $longitude
                };
            
            if ($modify_coloring === true) {
                var $styles = [
                    {
                        stylers: [
                            {hue: $hue},
                            {invert_lightness: false},
                            {saturation: $saturation},
                            {lightness: 1},
                            {
                                featureType: "landscape.man_made",
                                stylers: [{
                                    visibility: "on"
                                }]
                            }
                        ]
                    }, {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [
                            {color: '#46bcec'}
                        ]
                    }
                ];
            }
            var map = new google.maps.Map(document.getElementById($id), {
                zoom: $zoom,
                center: $coinpo_map,
                mapTypeId:
                google.maps.MapTypeId.$map_type,
                styles: $styles
            });
            
            var contentString = '<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="coinpo-map-info">' +
                '<h4 class="map-title">' + $title + '</h4>' +
                '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' +
                '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;<a href="tel:' + $phone + '">' + $phone + '</a></span></div>' +
                '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' +
                '</div>';
            
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });
            
            var marker = new google.maps.Marker({
                position: $coinpo_map,
                map: map
            });
            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });
        });
    };
    $(document).on('click', '.loadmore-product a', function (e) {
        var _this = $(this),
            _main_content = _this.closest('.funiter-products'),
            _parent = _this.closest('.loadmore-product'),
            _loop_query = _parent.data('loop'),
            _loop_id = _parent.data('id'),
            _loop_style = _parent.data('style'),
            _loop_thumb = _parent.data('thumb'),
            _liststyle = _parent.data('type'),
            _loop_class = _parent.data('class');
        
        if (_main_content.is('.loading')) {
            return false;
        }
        
        _main_content.addClass('loading');
        $.ajax({
            type: 'POST',
            url: funiter_ajax_frontend.ajaxurl,
            data: {
                action: 'funiter_ajax_loadmore',
                security: funiter_ajax_frontend.security,
                loop_query: _loop_query,
                loop_class: _loop_class,
                loop_id: _loop_id,
                loop_style: _loop_style,
                loop_thumb: _loop_thumb,
            },
            success: function (response) {
                if (response['out_post'] == 'yes') {
                    _this.addClass('outofproduct');
                    _this.html(funiter['text']['out_of_product']);
                }
                if (_liststyle == 'owl') {
                    _main_content.find('.owl-slick').slick('unslick');
                }
                if (response['success'] == 'yes' && response['out_post'] == 'no') {
                    _main_content.find('.response-product').append(response['html']);
                    _parent.data('id', response['loop_id']);
                }
            },
            complete: function () {
                _main_content.find('.owl-slick').funiter_init_carousel();
                funiter_popover_button();
                _main_content.removeClass('loading');
            }
        });
        e.preventDefault();
        return false;
    });
    
    /* NOTIFICATIONS */
    function funiter_setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }
    
    function funiter_getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    
    $(document).on('click', '.remove_from_cart_button', function () {
        var cart_item_key = $(this).data('cart_item_key');
        funiter_setCookie("cart_item_key_just_removed", cart_item_key, 1);
        funiter_setCookie("undo_cart_link", funiter_ajax_frontend.wp_nonce_url + '&undo_item=' + cart_item_key, 1);
    });
    
    $(document.body).on('removed_from_cart', function (a, b) {
        var cart_item_key = funiter_getCookie("cart_item_key_just_removed");
        var undo_cart_link = funiter_getCookie("undo_cart_link");
        var config = [];
        config['title'] = funiter_ajax_frontend.growl_notice_text;
        config['message'] =
            '<p class="growl-content">' + funiter_ajax_frontend.removed_cart_text;
        
        $.growl.notice(config);
    });
    $body.on('click', 'a.add_to_cart_button', function () {
        $('a.add_to_cart_button').removeClass('recent-added');
        $(this).addClass('recent-added');
        
        if ($(this).is('.product_type_variable, .isw-ready')) {
            $(this).addClass('loading');
        }
        
    });
    
    // On single product page
    $body.on('click', 'button.single_add_to_cart_button', function () {
        $('button.single_add_to_cart_button').removeClass('recent-added');
        $(this).addClass('recent-added');
    });
    
    $body.on('click', '.add_to_wishlist', function () {
        $(this).addClass('loading');
    });
    
    $body.on('added_to_cart', function () {
        var config = [];
        config['title'] = funiter_ajax_frontend.growl_notice_text;
        
        $('.add_to_cart_button.product_type_variable.isw-ready,.funiter-single-add-to-cart-fixed-top').removeClass('loading');
        
        var $recentAdded = $('.add_to_cart_button.recent-added, button.single_add_to_cart_button.recent-added'),
            $img = $recentAdded.closest('.product').find('img.img-responsive'),
            pName = $recentAdded.attr('aria-label');
        
        // if add to cart from wishlist
        if (!$img.length) {
            $img = $recentAdded.closest('.wishlist_item').find('.wishlist_item_product_image img');
        }
        
        // if add to cart from single product page
        if (!$img.length) {
            $img = $recentAdded.closest('.main-contain-summary').find('img.wp-post-image');
        }
        
        // reset state after 5 sec
        setTimeout(function () {
            $recentAdded.removeClass('added').removeClass('recent-added');
            $recentAdded.next('.added_to_cart').remove();
        }, 5000);
        
        if (typeof pName == 'undefined' || pName == '') {
            pName = $recentAdded.closest('.summary').find('.product_title').text().trim();
        }
        
        if (typeof pName !== 'undefined') {
            
            config['message'] = (
                $img.length ? '<img src="' + $img.attr('src') + '"' + ' alt="' + pName + '" class="growl-thumb" />' : ''
            ) + '<div class="growl-content"><div class="igrowl-title">' + pName + '</div><div class="notice-text"><a href="' + funiter_ajax_frontend.wc_cart_url + '">' + funiter_ajax_frontend.view_cart_notification_text + '</a></div></div>';
            
        } else {
            config['message'] =
                '<div class="notice-text">' + funiter_ajax_frontend.added_to_cart_text + '</div> &nbsp;<a href="' + funiter_ajax_frontend.wc_cart_url + '">' + funiter_ajax_frontend.view_cart_notification_text + '</a>';
        }
        
        $.growl.notice(config);
    });
    $body.on('added_to_wishlist', function () {
        var config = [];
        config['title'] = funiter_ajax_frontend.growl_notice_text;
        
        $('#yith-wcwl-popup-message').remove();
        
        config['message'] =
            '<div class="growl-content">' + funiter_ajax_frontend.added_to_wishlist_text + '&nbsp;<a href="' + funiter_ajax_frontend.wishlist_url + '">' + funiter_ajax_frontend.browse_wishlist_text + '</a></div>';
        
        $.growl.notice(config);
    });
    
    function funiter_popover_button() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.product-item .group-button a').each(function () {
            if ($(this).closest('.add-to-cart').length == 0) {
                $(this).tooltip({
                    title: $(this).text(),
                    trigger: 'hover',
                    placement: 'top',
                });
            }
        });
    }
    
    //ToolTip Cart button
    function funiter_tooltop_button_cart() {
        $('.product-item .group-button .add-to-cart a').each(function () {
            var $titler = $(this).text();
            $(this).parent().tooltip({
                title: $titler,
                trigger: 'hover',
                placement: 'top',
            });
        });
    }
    
    // Variations hover
    function funiter_variations_hover() {
        $('.product-item').each(function () {
            if ($(this).find('.attribute-pa_color').length > 0) {
                $(this).addClass('product-item-has-variations');
            }
        });
    }
    
    function funiter_popup_newsletter() {
        var _popup = document.getElementById('popup-newsletter');
        if (_popup != null) {
            if (funiter_global_frontend.funiter_enable_popup_mobile != 1) {
                if ($(window).innerWidth() <= 992) {
                    return;
                }
            }
            var disabled_popup_by_user = getCookie('funiter_disabled_popup_by_user');
            if (disabled_popup_by_user == 'true') {
                return;
            } else {
                if (funiter_global_frontend.funiter_enable_popup == 1) {
                    setTimeout(function () {
                        $(_popup).modal({
                            keyboard: false
                        });
                        $(_popup).find('.lazy').lazy({
                            delay: 0
                        });
                    }, funiter_global_frontend.funiter_popup_delay_time);
                }
            }
            $(document).on('change', '.funiter_disabled_popup_by_user', function () {
                if ($(this).is(":checked")) {
                    setCookie('funiter_disabled_popup_by_user', 'true', 7);
                } else {
                    setCookie('funiter_disabled_popup_by_user', '', 0);
                }
            });
        }
        
        function setCookie() {
            var d = new Date();
            d.setTime(d.getTime() + (arguments[2] * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = arguments[0] + "=" + arguments[1] + "; " + arguments[2];
        }
        
        function getCookie() {
            var name = arguments[0] + "=",
                ca = document.cookie.split(';'),
                i = 0,
                c = 0;
            for (; i < ca.length; ++i) {
                c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    }
    
    function funiter_clone_main_menu() {
        if ($('#header .clone-main-menu').length > 0) {
            var _winw = $(window).innerWidth();
            var _clone_menu = $('#header .clone-main-menu');
            var _target = $('#box-mobile-menu .clone-main-menu');
            var main_menu_break_point = funiter_theme_frontend['main_menu_break_point'];
            
            if (_winw <= main_menu_break_point) {
                if (_clone_menu.length > 0 && _target.length == 0) {
                    _clone_menu.each(function () {
                        $(this).clone().appendTo("#box-mobile-menu .box-inner");
                    });
                }
            } else {
            
            }
        }
    }
    
    function prdctfltr_custom_remove() {
        var remove_prdctfltr_topbar = $('.prdctfltr_topbar').remove();
        remove_prdctfltr_topbar.insertAfter('.prdctfltr_filter_title');
        
    }
    
    // Toggle filter
    function funiter_prdctfltr_toggle_filter() {
        $(document).on('click', '.prdctfltr_woocommerce .prdctfltr_filter_title .prdctfltr_woocommerce_filter_title', function (e) {
            $(this).closest('.prdctfltr_wc').find('.prdctfltr_woocommerce_filter').toggleClass('prdctfltr_active');
            $(this).closest('.prdctfltr_wc').find('.prdctfltr_woocommerce_ordering').stop().slideToggle(300);
            e.preventDefault();
            return false;
        });
    }
    
    // Single product on real mobile
    function funiter_single_product_on_real_mobile() {
        if (!$('.single-product-mobile').length) {
            return;
        }
        if ($('.single-product-mobile').is('.mobile-processed')) {
            return;
        }
        var $singleLeft = $('.single-product-mobile .single-left');
        var $singleSummary = $('.single-product-mobile .summary');
        var $product = $singleSummary.closest('.product');
        var $variationsForm = $singleSummary.find('.variations_form');
        var is_variable = $variationsForm.length > 0;
        var product_title = $('.single-product-mobile .product .summary .product_title').text();
        var price_html = $singleSummary.find('> .price').length ? $singleSummary.find('> .price').html() : $singleSummary.find('.summary-inner > .price').html();
        var rating_html = '';
        
        if ($singleSummary.find('.woocommerce-product-rating').length) {
            rating_html = $singleSummary.find('.woocommerce-product-rating').html();
        }
        
        if (!$singleLeft.find('.quick-info').length) {
            $singleLeft.append('<div class="quick-info"></div>');
        }
        
        var $singleQuickInfo = $singleLeft.find('.quick-info');
        
        // Move title
        if (!$singleQuickInfo.find('.product_title').length) {
            $singleQuickInfo.append('<h1 class="product_title entry-title">' + product_title + '</h1>');
            $('.single-product-mobile .product .summary .product_title').remove();
        }
        
        if (!$singleQuickInfo.find('.price').length) {
            $singleQuickInfo.append('<p class="price">' + price_html + '</p>');
        }
        
        if (!$singleQuickInfo.find('.woocommerce-product-rating').length && rating_html != '') {
            $singleQuickInfo.append('<div class="woocommerce-product-rating">' + rating_html + '</div>').addClass('has-rating');
        }
        
        if (is_variable) {
            var list_atts_html = '';
        }
        
        // Tabs
        var tabs_html = '';
        if (!$singleSummary.find('.tabs-box-wrap').length) {
            var i = 0;
            $product.find('.product-mobile-toggle-tab-content').each(function () {
                var tab_title = $(this).text();
                i++;
                $(this).attr('data-tab-nav-num', i);
                tabs_html += '<div class="tabs-box-wrap box-wrap"><div class="box-inner"><a data-tab-nav-num="' + i + '" href="#" class="toggle-tab-mobile toggle-box-nav">' + tab_title + '</a></div></div>';
            });
            $singleSummary.append(tabs_html);
        }
        
        
        $('.single-product-mobile').addClass('mobile-processed');
    }
    
    // Tab box nav click
    $(document).on('click', '.single-product-mobile .tabs-box-wrap .toggle-tab-mobile', function (e) {
        var tab_nav_num = $(this).attr('data-tab-nav-num');
        $('.woocommerce-tabs-mobile .product-mobile-toggle-tab-content[data-tab-nav-num="' + tab_nav_num + '"]').trigger('click');
        e.preventDefault();
        return false;
    });
    
    $(document).ajaxComplete(function (event, xhr, settings) {
        if ($('.lazy').length > 0) {
            $('.lazy').funiter_init_lazy_load();
        }
        if ($('.equal-container').length) {
            funiter_better_equal_elems($('.equal-container'));
        }
        if ($('.block-minicart .cart_list').length > 0 && $.fn.scrollbar) {
            $('.block-minicart .cart_list').scrollbar();
        }
        if ($('.prdctfltr_cat label').hasClass('prdctfltr_active')) {
            $('.categories-product-woo').removeClass('archive-shop');
        }
        if ($('.owl-slick').length > 0) {
            $('.owl-slick').funiter_init_carousel();
        }
        funiter_popover_button();
        funiter_main_header_sticky();
        funiter_tooltop_button_cart();
        prdctfltr_custom_remove();
    
        if ($('input.qty').length) {
            $('input.qty').each(function () {
                var this_min_val = parseInt($(this).attr('min'));
                if (isNaN(this_min_val)) {
                    this_min_val = 1;
                }
                $(this).val(this_min_val);
            });
        }
        
    });
    $(window).on('resize', function () {
        if ($('.equal-container').length) {
            funiter_better_equal_elems($('.equal-container'));
        }
    });
    /* zopim chat */
    $(document).on('click', '.line-chat', function (e) {
        if ($('.zopim').length) {
            $('.zopim').toggle();
        }
        e.preventDefault();
    });
    // Live chat open
    $(document).on('click', '.line-chat', function (e) {
        if ($('jdiv').length) {
            jivo_api.open();
        }
        e.preventDefault();
        return false;
    });
    // close-notice
    $(document).on('click', '.close-notice', function (e) {
        if ($('.header-top-noitice').length) {
            $('.header-top-noitice').remove();
        }
        var visits = $.getCookie('funiter_setCookie');
        $.setCookie('funiter_setCookie',
            visits ? (parseInt(visits) + 1) : 1,   // if cookie not set, start with 1
            365);
        e.preventDefault();
    });
    
    $(document).ready(function () {
        prdctfltr_custom_remove();
        funiter_fix_vc_full_width_row();
        funiter_sticky_single();
        funiter_add_to_cart_single();
        funiter_prdctfltr_toggle_filter();
        // Single product mobile add to cart fixed button
        $(document).on('click', '.funiter-single-add-to-cart-fixed-top', function (e) {
            var $this = $(this);
            $this.addClass('loading');
            if ($('.product .summary button.single_add_to_cart_button').length) {
                $('.product .summary button.single_add_to_cart_button').trigger('click');
            }
            e.preventDefault();
        });
        $(document).on('click', '.prdctfltr_filter:not(.prdctfltr_byprice) label', function (e) {
            prdctfltr_custom_remove();
        });
        
        if ($('.lazy').length > 0) {
            $('.lazy').funiter_init_lazy_load();
        }
        if ($('.funiter-countdown').length > 0) {
            $('.funiter-countdown').funiter_countdown();
        }
        if ($('.owl-slick').length > 0) {
            $('.owl-slick').funiter_init_carousel();
        }
        if ($('.product-gallery').length > 0) {
            $('.product-gallery').funiter_product_gallery();
        }
        if ($('.block-nav-category').length > 0) {
            $('.block-nav-category').funiter_vertical_menu();
        }
        if ($('.flex-control-thumbs').length > 0) {
            $('.flex-control-thumbs').funiter_product_thumb();
        }
        if ($('.category-search-option').length > 0) {
            $('.category-search-option').chosen();
        }
        if ($('.category .chosen-results').length > 0 && $.fn.scrollbar) {
            $('.category .chosen-results').scrollbar();
        }
        if ($('.block-minicart .cart_list').length > 0 && $.fn.scrollbar) {
            $('.block-minicart .cart_list').scrollbar();
        }
        if ($('.funiter-recent-viewed-products-sliding').length > 0 && $.fn.scrollbar) {
            $('.funiter-recent-viewed-products-sliding').scrollbar();
        }
        if ($('.single_add_to_cart_button').length > 0) {
            $('.single_add_to_cart_button').funiter_alert_variable_product();
        }
        if ($('.widget_product_categories .product-categories').length > 0) {
            $('.widget_product_categories .product-categories').funiter_category_product();
        }
        if ($('.funiter-google-maps').length > 0) {
            $('.funiter-google-maps').funiter_google_map();
        }
        
        if ($('.equal-container').length) {
            funiter_better_equal_elems($('.equal-container'));
        }
        if ($('.funiter-tabs .tab-link.tab-responsive').length) {
            $('.funiter-tabs .tab-link.tab-responsive').funiter_responsive_tabs();
        }
        if ($('.funiter-categorywrap .category-list').length) {
            $('.funiter-categorywrap .category-list').funiter_responsive_tabs();
        }
        if ($('.owl-slick .product-item').length) {
            funiter_hover_product_item_both($(
                '.funiter-products .owl-slick .row-item,' +
                '.owl-slick .product-item'));
        }
        
        funiter_main_header_sticky();
        funiter_popover_button();
        funiter_tooltop_button_cart();
        funiter_popup_newsletter();
        funiter_clone_append_category();
        $body.funiter_show_all_product_desc();
        $body.funiter_magnific_popup();
        funiter_variations_hover();
        
        // Submit when choose sort by
        $(document).on('change', 'form.fami-woocommerce-ordering select[name="orderby"]', function () {
            var $this = $(this);
            var thisForm = $this.closest('form');
            var order_val = $this.val();
            var trigger_submit = true;
            $('.prdctfltr_wc .prdctfltr_woocommerce_ordering').each(function () {
                if ($(this).closest('.prdctfltr_sc_products').length == 0) {
                    if ($(this).find('.prdctfltr_orderby .prdctfltr_ft_' + order_val + ' input[type="checkbox"]').length) {
                        $(this).find('.prdctfltr_orderby .prdctfltr_ft_' + order_val).trigger('click');
                        trigger_submit = false;
                        return false;
                    }
                }
            });
            
            if (trigger_submit) {
                thisForm.submit();
            }
        });
        if ($('.prdctfltr_woocommerce_filter_submit').length > 0) {
            $('.fami-woocommerce-ordering').remove();
        }
        
        $(document).on('click', '.box-mobile-menu .close-menu, .body-overlay,.box-mibile-overlay', function (e) {
            $('body').removeClass('box-mobile-menu-open real-mobile-show-menu');
            $('.hamburger').removeClass('is-active');
        });
        /*  Mobile Menu on real mobile (if header mobile is enabled) */
        $(document).on('click', '.mobile-hamburger-navigation ', function (e) {
            $(this).find('.hamburger').toggleClass('is-active');
            if ($(this).find('.hamburger').is('.is-active')) {
                $('body').toggleClass('real-mobile-show-menu box-mobile-menu-open');
            } else {
                $('body').removeClass('real-mobile-show-menu box-mobile-menu-open');
            }
            e.preventDefault();
        });
        /* Mobile menu (Desktop/responsive) */
        $(document).on('click', '.box-mobile-menu .clone-main-menu .toggle-submenu', function (e) {
            var $this = $(this);
            var thisMenu = $this.closest('.clone-main-menu');
            var thisMenuWrap = thisMenu.closest('.box-mobile-menu');
            thisMenu.removeClass('active');
            var text_next = $this.prev().text();
            thisMenuWrap.find('.box-title').html(text_next);
            thisMenu.find('li').removeClass('mobile-active');
            $this.parent().addClass('mobile-active');
            $this.parent().closest('.submenu').css({
                'position': 'static',
                'height': '0'
            });
            thisMenuWrap.find('.back-menu, .box-title').css('display', 'block');
            // Fix lazy for mobile menu
            if ($this.parent().find('.fami-lazy:not(.already-fix-lazy)').length) {
                $this.parent().find('.fami-lazy:not(.already-fix-lazy)').lazy({
                    bind: 'event',
                    delay: 0
                }).addClass('already-fix-lazy');
            }
            e.preventDefault();
        });
        
        $(document).on('click', '.box-mobile-menu .back-menu', function (e) {
            var $this = $(this);
            var thisMenuWrap = $this.closest('.box-mobile-menu');
            var thisMenu = thisMenuWrap.find('.clone-main-menu');
            thisMenu.find('li.mobile-active').each(function () {
                thisMenu.find('li').removeClass('mobile-active');
                if ($(this).parent().hasClass('main-menu')) {
                    thisMenu.addClass('active');
                    $('.box-mobile-menu .box-title').html('MAIN MENU');
                    $('.box-mobile-menu .back-menu, .box-mobile-menu .box-title').css('display', 'none');
                } else {
                    thisMenu.removeClass('active');
                    $(this).parent().parent().addClass('mobile-active');
                    $(this).parent().css({
                        'position': 'absolute',
                        'height': 'auto'
                    });
                    var text_prev = $(this).parent().parent().children('a').text();
                    $('.box-mobile-menu .box-title').html(text_prev);
                }
                e.preventDefault();
            })
        });
        
        /* Single product on real mobile */
        funiter_single_product_on_real_mobile();
        
        /* Mobile Tabs on real mobile */
        $(document).on('click', '.box-tabs .box-tab-nav', function (e) {
            var $this = $(this);
            var thisTab = $this.closest('.box-tabs');
            var tab_id = $this.attr('href');
            
            if ($this.is('.active')) {
                return false;
            }
            
            thisTab.find('.box-tab-nav').removeClass('active');
            $this.addClass('active');
            
            thisTab.find('.box-tab-content').removeClass('active');
            thisTab.find(tab_id).addClass('active');
            
            e.preventDefault();
        });
        // Wish list on real menu mobile
        if ($('.box-mobile-menu .wish-list-mobile-menu-link-wrap').length) {
            if (!$('.box-mobile-menu').is('.moved-wish-list')) {
                var wish_list_html = $('.box-mobile-menu .wish-list-mobile-menu-link-wrap').html();
                $('.box-mobile-menu .wish-list-mobile-menu-link-wrap').remove();
                $('.box-mobile-menu .main-menu').append('<li class="menu-item-for-wish-list menu-item menu-item-type-custom menu-item-object-custom">' + wish_list_html + '</li>');
                $('.box-mobile-menu').addClass('moved-wish-list');
            }
        }
        // Lang real menu mobile
        if ($('.box-mobile-menu .header-lang-mobile').length) {
            if (!$('.box-mobile-menu').is('.moved-lang-mobile')) {
                var lang_mobile_html = $('.box-mobile-menu .header-lang-mobile').html();
                $('.box-mobile-menu .header-lang-mobile').remove();
                $('.box-mobile-menu .main-menu').append('' + lang_mobile_html + '');
                $('.box-mobile-menu').addClass('moved-lang-mobile');
            }
        }
        // Account mobile
        $(document).on('click', '.next-action', function (e) {
            $('.myaccount-action').show().addClass('element-acc-show');
            $(this).parent().hide().removeClass('element-acc-show');
            e.preventDefault();
        });
        // Footer mobile
        if ($('.vc_mobile .footer .widgettitle').length > 0) {
            $('.footer .widgettitle').each(function () {
                $(this).addClass('footer-title-mobile');
                $(this).parent().find('> div').hide();
                $(this).on('click', function (e) {
                    var $this = $(this);
                    var thisDiv = $this.closest('div');
                    var thisSub = thisDiv.find('> div');
                    if (thisSub.is('.sub-menu-open')) {
                        thisSub.stop().slideUp('fast');
                        thisSub.removeClass('sub-menu-open');
                    } else {
                        thisDiv.find('> .sub-menu-open').stop().slideUp('fast');
                        $('.footer .widgettitle').parent().find('> .sub-menu-open').removeClass('sub-menu-open').slideUp('fast');
                        thisSub.slideDown('fast');
                        thisSub.addClass('sub-menu-open');
                    }
                });
            });
        }
        // Share product
        $(document).on("click", '.button-share', function (e) {
            var $this = $(this);
            $this.parent().addClass('element-share-show');
            e.preventDefault();
        });
        $(document).on('click', '.share-overlay', function (e) {
            $('.social-share-product').removeClass('element-share-show');
        });
        // Recent viewed
        $(document).on("click", '.button-sliding', function (e) {
            var $this = $(this);
            $('.funiter-recent-viewed-products-wrap').addClass('show-sliding');
            e.preventDefault();
        });
        $(document).on("click", '.button-close', function (e) {
            var $this = $(this);
            $this.parent().removeClass('show-sliding');
            e.preventDefault();
        });
        $(document).on('click', function (e) {
            var container = $('.funiter-recent-viewed-products-wraper');
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('.funiter-recent-viewed-products-wrap').removeClass('show-sliding');
            }
        });
        // Count variable
        $('.summary .variations').each(function () {
            var dots2 = $('.summary .variations tr').length;
            $(this).addClass("has-" + dots2 + "tr")
        });
        
    });
    
})(jQuery, window, document);