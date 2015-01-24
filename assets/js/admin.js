jQuery.noConflict();

/** Fire up jQuery - let's dance! 
 */
jQuery(document).ready(function ($) {

    var fname = jQuery("#ujic-style").val() + 'Select';

    /* jQuery UI Slider */
    jQuery('.ujic_sliderui').each(function () {

        var obj = jQuery(this);
        var sId = "#" + obj.data('id');
        var val = parseInt(obj.data('val'));
        var min = parseInt(obj.data('min'));
        var max = parseInt(obj.data('max'));
        var step = parseInt(obj.data('step'));

        //slider init
        obj.slider({
            value: val,
            min: min,
            max: max,
            step: step,
            range: "min",
            slide: function (event, ui) {
                jQuery(sId).val(ui.value);
                if("#ujic_size" == sId)
                    window[fname].the_size(ui.value);
                if("#ujic_thick" == sId)
                    window[fname].the_thick(ui.value);
                if("#ujic_lab_sz" == sId)
                    window[fname].the_lab_sz(ui.value);
                if('#ujic_subscrFrmWidth' == sId)
                    window[fname].subscrFormWidth(ui.value);
            }
        });

    });

    /* jQuery Color Picker */
    jQuery('.ujic_colorpick').wpColorPicker({change: function (event, ui) {
            window[fname].new_colors($(this).attr("id"), $(this).val())
        }});


    /* JQuery Checkbox/Radio */
    jQuery('input').iCheck({
        checkboxClass: 'icheckbox_flat-pink',
        radioClass: 'iradio_flat-pink'
    });

    jQuery(".ujic-preview").draggable();
    jQuery(".ujic-preview").find('.handlediv').hide();
    jQuery(".ujic-preview").find('.postbox').click(function () {
        $(this).removeClass('closed');
    });
    

    if (jQuery('#ujic_name').length) {
        /* Style Preview */
        window[fname].init();
    }



});


//select styles
function sel_style(s) {
    var lnk;
    if (s == 'classic')
        lnk = 'options-general.php?page=uji-countdown&tab=tab_ujic_new&style=classic';
    if (s == 'custom')
        lnk = 'options-general.php?page=uji-countdown&tab=tab_ujic_new&style=custom';
    window.location.href = "" + lnk + "";
}


//redirect to home admin
function ujic_admin_home() {
    window.location.href = 'options-general.php?page=uji-countdown';
}


/**
 *
 * Preview Clasic Panel Admin
 *
 *
 */

(function ($) {
    classicSelect = {
        /// Init
        init: function () {
            var style = $('#ujic-style');
            if (style.length) {
                this.the_size();
                this.the_lab_sz();
                this.the_format();
                this.the_colors();
                this.the_labels();
                this.the_fonts();
                this.handleSubscription();
            }
        },
        /// Size 
        the_size: function (val) {
            var size = $('#ujic_size');
            if (size.length) {
                var newsize = (val && val != 'undefined' && val.length) ? val : size.val();
            }
            $('#ujiCountdown').find('.countdown_amount').css('font-size', newsize + 'px');
        },
        /// Label Size 
        the_lab_sz: function (val) {
            var size = $('#ujic_lab_sz');
            if (size.length) {
                $('.countdown_txt').css('font-size', ((val && val != 'undefined' && val.length) ? val : size.val()) + 'px');
            }
        },
        /// Format 
        the_format: function ( ) {
            var format = new Array('ujic_d', 'ujic_h', 'ujic_m', 'ujic_s', 'ujic_y', 'ujic_o', 'ujic_w');
            for (var i = 0; i < format.length; i++) {
                if ($('#' + format[i]).is(":checked")) {
                    $('#ujiCountdown').find('.' + format[i]).show();
                } else {
                    $('#ujiCountdown').find('.' + format[i]).hide();
                }
            }

            //live change
            $('.iCheck-helper').click(function () {
                var id = $(this).parent().find(":checkbox").attr("id");
                if ($(this).parent().hasClass('checked')) {
                    $('#ujiCountdown').find('.' + id).show();
                } else {
                    $('#ujiCountdown').find('.' + id).hide();
                }

                (id === 'ujic_subscrFrmIsEnabled') ? classicSelect.handleSubscription() : null;

            });
        },
        subscrFormWidth : function(newWidth){
            $('#ujiCountdown form p').width(newWidth + '%');
        },
        handleSubscription : function(){
            var formElements = {

                subscrFrmElm           : $('#ujiCountdown form'),
                subscrFrmWidth         : $('#ujic_subscrFrmWidth').parent(),
                subscrFrmAboveTxt      : $('#ujic_subscrFrmAboveText').parent(),
                subscrFrmInputTxt      : $('#ujic_subscrFrmInputText').parent(),
                subscrFrmSubmitTxt     : $('#ujic_subscrFrmSubmitText').parent(),
                subscrFrmThanksMessage : $('#ujic_subscrFrmThanksMessage').parent(),
                subscrFrmErrorMessage  : $('#ujic_subscrFrmErrorMessage').parent(),
                subscrFrmSubmitColor   : $('#ujic_subscrFrmSubmitColor').closest( ".ujic-color" )

            };

            var isFormEnabled = $('#ujic_subscrFrmIsEnabled').is(':checked');
            this.toggleSubscriptionElements(formElements, isFormEnabled);

            formElements.subscrFrmAboveTxt.children('input').keyup(function(){
                formElements.subscrFrmElm.children('span').text($(this).val());
            }).keydown(function(evt){13 == evt.which ? evt.preventDefault() : '';});

            formElements.subscrFrmInputTxt.children('input').keyup(function(){
                formElements.subscrFrmElm.find('input:text').attr('placeholder', $(this).val());
            }).keydown(function(evt){13 == evt.which ? evt.preventDefault() : '';});

            formElements.subscrFrmSubmitTxt.children('input').keyup(function(){
                formElements.subscrFrmElm.find('input:submit').val($(this).val()).click(function(evt){evt.preventDefault();});
            }).keydown(function(evt){13 == evt.which ? evt.preventDefault() : '';});


        },
        toggleSubscriptionElements : function(elements, visible){

            $.each(elements, function(prop, elm){
                (!!visible) ? elm.show() : elm.hide(1000);
            });
        },
        //color light 
        shadeColor : function(color, percent) {  
                var num = parseInt(color.slice(1),16), amt = Math.round(2.55 * percent), R = (num >> 16) + amt, G = (num >> 8 & 0x00FF) + amt, B = (num & 0x0000FF) + amt;
                return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255)).toString(16).slice(1);
            },
        /// Colors 
        the_colors: function (id, hex) {
            var col_txt = $('#ujic_col_txt').val();
            var col_sw = $('#ujic_col_sw').val();
            var col_up = $('#ujic_col_up').val();
            var col_dw = $('#ujic_col_dw').val();
            var col_lab = $('#ujic_col_lab').val();
            var col_sub = $('#ujic_subscrFrmSubmitColor').val();

            $('.countdown_amount').css('color', col_txt);
            $('.countdown_amount').css("text-shadow", '1px 1px 1px ' + col_sw);

            $('.ujic-classic').find('.countdown_amount').css("background", "-moz-linear-gradient(top,  " + col_up + " 50%, " + col_dw + " 50%)"); /* FF3.6+ */
            $('.ujic-classic').find('.countdown_amount').css("background", "-webkit-gradient(linear, left top, left bottom, color-stop(50%," + col_up + "), color-stop(50%," + col_dw + "))"); /* Chrome,Safari4+ */
            $('.ujic-classic').find('.countdown_amount').css("background", "-webkit-linear-gradient(top,  " + col_up + " 50%," + col_dw + " 50%)"); /* Chrome10+,Safari5.1+ */
            $('.ujic-classic').find('.countdown_amount').css("background", "-o-linear-gradient(top,  " + col_up + " 50%," + col_dw + " 50%)"); /* Opera 11.10+ */
            $('.ujic-classic').find('.countdown_amount').css("background", "-ms-linear-gradient(top,  " + col_up + " 50%," + col_dw + " 50%)"); /* IE10+ */
            $('.ujic-classic').find('.countdown_amount').css("background", "linear-gradient(to bottom,  " + col_up + " 50%," + col_dw + " 50%)"); /* W3C */
            $('.ujic-classic').find('.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + col_up + "', endColorstr='" + col_dw + "',GradientType=0 )"); /* IE6-9 */

            $('.countdown_txt').css('color', col_lab);
            
            $('#ujiCountdown').find('input[type=submit]').css("background", "-moz-linear-gradient(top,  " + col_sub + " 0%, " + this.shadeColor(col_sub, 20) + " 100%)"); /* FF3.6+ */
            $('#ujiCountdown').find('input[type=submit]').css("background", "-webkit-gradient(linear, left top, left bottom, color-stop(0%," + col_sub + "), color-stop(100%," + this.shadeColor(col_sub, 20) + "))"); /* Chrome,Safari4+ */
            $('#ujiCountdown').find('input[type=submit]').css("background", "-webkit-linear-gradient(top,  " + col_sub + " 50%," + this.shadeColor(col_sub, 20) + " 100%)"); /* Chrome10+,Safari5.1+ */
            $('#ujiCountdown').find('input[type=submit]').css("background", "-o-linear-gradient(top,  " + col_sub + " 0%," + this.shadeColor(col_sub, 20) + " 100%)"); /* Opera 11.10+ */
            $('#ujiCountdown').find('input[type=submit]').css("background", "-ms-linear-gradient(top,  " + col_sub + " 0%," + this.shadeColor(col_sub, 20) + " 100%)"); /* IE10+ */
            $('#ujiCountdown').find('input[type=submit]').css("background", "linear-gradient(to bottom,  " + col_sub + " 0%," + this.shadeColor(col_sub, 20) + " 100%)"); /* W3C */
            $('#ujiCountdown').find('input[type=submit]').css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + col_sub + "', endColorstr='" + this.shadeColor(col_sub, 20) + "',GradientType=0 )"); /* IE6-9 */

        },
        /// Colors 
        new_colors: function (id, hex) {
            var col_up = $('#ujic_col_up').val();
            var col_dw = $('#ujic_col_dw').val();
            
         
            //console.log(id);
            switch (id)
            {
                case 'ujic_col_txt':
                    $('.countdown_amount').css('color', hex);
                    break;
                case 'ujic_col_sw':
                    $('.countdown_amount').css("text-shadow", '1px 1px 1px ' + hex);
                    break;
                case 'ujic_col_up':
                    $('.ujic-classic').find('.countdown_amount').css("background", "-moz-linear-gradient(top,  " + hex + " 50%, " + col_dw + " 50%)"); /* FF3.6+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "-webkit-gradient(linear, left top, left bottom, color-stop(50%," + hex + "), color-stop(50%," + col_dw + "))"); /* Chrome,Safari4+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "-webkit-linear-gradient(top,  " + hex + " 50%," + col_dw + " 50%)"); /* Chrome10+,Safari5.1+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "-o-linear-gradient(top,  " + hex + " 50%," + col_dw + " 50%)"); /* Opera 11.10+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "-ms-linear-gradient(top,  " + hex + " 50%," + col_dw + " 50%)"); /* IE10+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "linear-gradient(to bottom,  " + hex + " 50%," + col_dw + " 50%)"); /* W3C */
                    $('.ujic-classic').find('.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + hex + "', endColorstr='" + col_dw + "',GradientType=0 )"); /* IE6-9 */
                    break;
                case 'ujic_col_dw':
                    $('.ujic-classic').find('.countdown_amount').css("background", "-moz-linear-gradient(top,  " + col_up + " 50%, " + hex + " 50%)"); /* FF3.6+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "-webkit-gradient(linear, left top, left bottom, color-stop(50%," + col_up + "), color-stop(50%," + hex + "))"); /* Chrome,Safari4+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "-webkit-linear-gradient(top,  " + col_up + " 50%," + hex + " 50%)"); /* Chrome10+,Safari5.1+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "-o-linear-gradient(top,  " + col_up + " 50%," + hex + " 50%)"); /* Opera 11.10+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "-ms-linear-gradient(top,  " + col_up + " 50%," + hex + " 50%)"); /* IE10+ */
                    $('.ujic-classic').find('.countdown_amount').css("background", "linear-gradient(to bottom,  " + col_up + " 50%," + hex + " 50%)"); /* W3C */
                    $('.ujic-classic').find('.countdown_amount').css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + col_up + "', endColorstr='" + hex + "',GradientType=0 )"); /* IE6-9 */
                    break;
                case 'ujic_col_lab':
                    $('.countdown_txt').css('color', hex);
                    break;
                case 'ujic_subscrFrmSubmitColor':
                    //console.log(this.shadeColor(hex, 70));
                    $('#ujiCountdown').find('input[type=submit]').css("background", "-moz-linear-gradient(top,  " + hex + " 0%, " + this.shadeColor(hex, 20) + " 100%)"); /* FF3.6+ */
                    $('#ujiCountdown').find('input[type=submit]').css("background", "-webkit-gradient(linear, left top, left bottom, color-stop(0%," + hex + "), color-stop(100%," + this.shadeColor(hex, 20) + "))"); /* Chrome,Safari4+ */
                    $('#ujiCountdown').find('input[type=submit]').css("background", "-webkit-linear-gradient(top,  " + hex + " 50%," + this.shadeColor(hex, 20) + " 100%)"); /* Chrome10+,Safari5.1+ */
                    $('#ujiCountdown').find('input[type=submit]').css("background", "-o-linear-gradient(top,  " + hex + " 0%," + this.shadeColor(hex, 20) + " 100%)"); /* Opera 11.10+ */
                    $('#ujiCountdown').find('input[type=submit]').css("background", "-ms-linear-gradient(top,  " + hex + " 0%," + this.shadeColor(hex, 20) + " 100%)"); /* IE10+ */
                    $('#ujiCountdown').find('input[type=submit]').css("background", "linear-gradient(to bottom,  " + hex + " 0%," + this.shadeColor(hex, 20) + " 100%)"); /* W3C */
                    $('#ujiCountdown').find('input[type=submit]').css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + hex + "', endColorstr='" + this.shadeColor(hex, 20) + "',GradientType=0 )"); /* IE6-9 */
                    break;
            }
        },
        /// Text Labels
        the_labels: function ( ) {
            if ($('#ujic_txt').is(":checked")) {
                $('.countdown_txt').show();
            } else {
                $('.countdown_txt').hide();
            }

            //live change
            $('.iCheck-helper').click(function () {
                var id = $(this).parent().find(":checkbox").attr("id");
                if (id == 'ujic_txt' && $(this).parent().hasClass('checked')) {
                    $('.countdown_txt').show();
                }
                else if (id == 'ujic_txt') {
                    $('.countdown_txt').hide();
                }
            });

        },
        /// Google Font
        the_fonts: function ( ) {
            var val = $('#ujic_goof').val();
            if (val && val != 'none') {
                var the_font = val.replace(/\s+/g, '+');
                //add reference to google font family
                $('head').append('<link href="http://fonts.googleapis.com/css?family=' + the_font + '" rel="stylesheet" type="text/css">');
                $('.countdown_amount').css('font-family', val + ', sans-serif');
            }
            //live change
            $('#ujic_goof').bind("change keyup", function () {
                var val = $(this).val();
                var the_font = val.replace(/\s+/g, '+');
                //add reference to google font family
                $('head').append('<link href="http://fonts.googleapis.com/css?family=' + the_font + '" rel="stylesheet" type="text/css">');
                $('.countdown_amount').css('font-family', val + ', sans-serif');

            });
        }

    };
})(jQuery);