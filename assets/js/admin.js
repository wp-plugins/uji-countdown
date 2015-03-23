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
    if (s == 'modern')
        lnk = 'options-general.php?page=uji-countdown&tab=tab_ujic_new&style=modern';
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

/**
 *
 * Preview Modern Panel Admin
 *
 *
 */

function init_circle(cids) {
    var myCircles = [];

    for (var i = 0; i <= cids.ids.length - 1; i++) {

        myCircles.push(Circles.create({
            parent: 'ujiCountdown2',
            id: cids.ids[i],
            value: parseInt(cids.time[i]),
            radius: parseInt(cids.size_val),
            width: cids.thick_val,
            maxValue: parseInt(cids.max[i]),
            colors: [cids.col_dw, cids.col_up],
            duration: 0
        }));
        
        jQuery('#ujiCountdown2').children("#"+cids.ids[i]).children(".circles-wrp").children('span').text(cids.label[i]);
 
    }
    
     jQuery('#ujiCountdown2').find(".circles-wrp").children('span').css('color', cids.col_lab);
     jQuery('#ujiCountdown2').find(".circles-text").css('color', cids.col_txt);

}

function change_modern(size, col_txt, col1, col2, thick, col_lab, lab_sz) {
    if (size == undefined || size == null)
        size = jQuery('#ujic_size').val();
    if (col_txt == undefined || col_txt == null)
        col_txt = jQuery('#ujic_col_txt').val();
    if (col1 == undefined || col1 == null)
        col1 = jQuery('#ujic_col_dw').val();
    if (col2 == undefined || col2 == null)
        col2 = jQuery('#ujic_col_up').val();
    if (thick == undefined || thick == null)
        thick = jQuery('#ujic_thick').val();
    if (col_lab == undefined || col_lab == null)
        col_lab = jQuery('#ujic_col_lab').val();
    if (lab_sz == undefined || lab_sz == null)
        lab_sz = jQuery('#ujic_col_lab').val();

//alert('dw'+ size + 'up'+ col2);
    jQuery(".ujicircle").remove();
    var format = [['ujic_y', 'Years', '1', '1'], ['ujic_o', 'Months', '9', '12'], ['ujic_w', 'Weeks', '2', '5'], ['ujic_d', 'Days', '12', '31'], ['ujic_h', 'Hours', '33', '23'], ['ujic_m', 'Minutes', '35', '59'], ['ujic_s', 'Seconds', '09', '59']];
    var cids   = {
                    ids:   [],
                    label:  [],
                    time:   [],
                    max:   [],
                    col_txt:  col_txt,
                    col_dw:   col1,
                    col_up:   col2,
                    thick:    thick,
                    col_lab: col_lab,
                    thick_val:  thick,
                    size_val:  size
  		};
                
    for (var i = 0; i < format.length; i++) {
        if (jQuery('#' + format[i][0]).is(":checked")) {
            //jQuery("#ujiCountdown2").append('<div class="uji_modhold"><input class="'+format[i][0]+' ujic_circle" data-readOnly=true data-width="'+ parseInt(size) +'" data-height="'+ parseInt(size) +'" data-fgColor="'+ col1 +'" data-bgColor="'+ col2 +'" data-thickness=".'+ thick +'" value="'+format[i][2]+'"><span class="countdown_txt">'+format[i][1]+'</span></div>');
            jQuery("#ujiCountdown2").append('<div class="ujicircle" id="u_'+format[i][0]+'"></div>');
            cids.ids.push('u_'+format[i][0]);
            cids.label.push(format[i][1]);
            cids.time.push(format[i][2]);
            cids.max.push(format[i][3]);
        }
    }
    //reload
    init_circle(cids);

}

(function ($) {
    modernSelect = {
        /// Init 
        init: function () {
            this.the_circle();
            this.the_colors();
            this.the_fonts();
            this.the_format();
            this.the_labels();
            this.the_lab_sz();
            this.handleSubscription();
        },
        the_circle: function () {
            change_modern(null);
        },
        /// Colors
        the_colors: function () {
            var col_txt = $('#ujic_col_txt').val();
            var col_lab = $('#ujic_col_lab').val();
            var col_sub = $('#ujic_subscrFrmSubmitColor').val();

            $('.ujic_circle').css('color', col_txt);
            $('.uji_modhold span').css('color', col_lab);
         
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
            switch (id)
            {
                case 'ujic_col_txt':
                    $('.circles-text').css('color', hex);
                    break;
                case 'ujic_col_lab':
                    $('.countdown_txt').css('color', hex);
                    break;
                case 'ujic_col_up':
                    change_modern(null, null, hex, $('#ujic_col_dw').val());
                    break;
                case 'ujic_col_dw':
                    change_modern(null, null, $('#ujic_col_up').val(), hex);
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
            modernSelect.the_fonts();
        },
        /// Size 
        the_size: function (val) {
            var size = $('#ujic_size');
            if (size.length) {
                var newsize = (val && val != 'undefined' && val.length) ? val : size.val();
            }
            
            change_modern(newsize);
            modernSelect.the_fonts();
        },
        
        /// Size 
        the_thick: function (val) {
            var size = $('#ujic_thick');
           // alert('ya'+ ( parseInt(val).length ));
            if (size.length && val && val != 'undefined') {
                
                change_modern(null, null, null, null, val);
                modernSelect.the_fonts();
            }
            
        },
         /// Label Size 
        the_lab_sz: function (val) {
            var size = $('#ujic_lab_sz');
            if (size.length) {
                $('.countdown_txt').css('font-size', ((val && val != 'undefined' && val.length) ? val : size.val()) + 'px');
            }
        },
        /// Size 
        the_lab_size: function (val) {
            var lsize = $('#ujic_lab_sz');
        
            if (lsize.length) {
                var labsize = (val && val != 'undefined' && val.length) ? val : size.val();
            }
            change_modern(newsize, labsize);
 
        },
        /// Format 
        the_format: function ( ) {
            //live change
            $('.iCheck-helper').click(function () {
                change_modern();
                modernSelect.the_fonts();
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
                
                (id === 'ujic_subscrFrmIsEnabled') ? classicSelect.handleSubscription() : null;
            });

        },
        /// Google Font
        the_fonts: function ( ) {
            var val = $('#ujic_goof').val();
            if (val && val != 'none') {
                var the_font = val.replace(/\s+/g, '+');
                //add reference to google font family
                $('head').append('<link href="http://fonts.googleapis.com/css?family=' + the_font + '" rel="stylesheet" type="text/css">');
                $('.circles-integer').css('font-family', val + ', sans-serif');
            }
            //live change
            $('#ujic_goof').bind("change keyup", function () {
                var val = $(this).val();
                var the_font = val.replace(/\s+/g, '+');
                //add reference to google font family
                $('head').append('<link href="http://fonts.googleapis.com/css?family=' + the_font + '" rel="stylesheet" type="text/css">');
                $('.circles-integer').css('font-family', val + ', sans-serif');

            });
        }

    };
})(jQuery);