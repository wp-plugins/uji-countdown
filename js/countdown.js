var uji_plugin		 = 	ujiCount.uji_plugin;

var expire			 = 	ujiCount.expire;

var Years 	 		 = 	ujiCount.Years;
var Months	 		 = 	ujiCount.Months;
var Weeks 	 		 = 	ujiCount.Weeks;
var Days 	 		 = 	ujiCount.Days;
var Hours 	 		 = 	ujiCount.Hours;
var Minutes  		 = 	ujiCount.Minutes;
var Seconds	 		 = 	ujiCount.Seconds;

var Year 			 = 	ujiCount.Year;
var Month			 = 	ujiCount.Month;
var Week 			 = 	ujiCount.Week;
var Day 	 		 = 	ujiCount.Day;
var Hour 	 		 = 	ujiCount.Hour;
var Minute  		 = 	ujiCount.Minute;
var Second			 = 	ujiCount.Second;

var ujic_txt_size 	 = 	ujiCount.ujic_txt_size;
var ujic_col_dw		 = 	ujiCount.ujic_col_dw;
var ujic_col_up 	 = 	ujiCount.ujic_col_up;
var ujic_col_txt 	 = 	ujiCount.ujic_col_txt;
var ujic_col_sw 	 =  ujiCount.ujic_col_sw;
var ujic_txt 		 = 	(ujiCount.ujic_txt == "true") ? true : false ;
var ujic_ani	 	 = 	(ujiCount.ujic_ani == "true") ? true : false ;
var ujic_url	 	 = 	(ujiCount.ujic_url) ? ujiCount.ujic_url : false;

var uji_center	 	 = 	ujiCount.uji_center;

jQuery(document).ready(function($){

            var austDay = new Date(''+ expire +'');
            jQuery.countdown.regional["uji"] = {
                labels: [''+ Years +'', ''+ Months +'', ''+ Weeks +'', ''+ Days +'', ''+ Hours +'', ''+ Minutes +'', ''+ Seconds +''],
                labels1: [''+ Year +'', ''+ Month +'', ''+ Week +'', ''+ Day +'', ''+ Hour +'', ''+ Minute +'', ''+ Second +''],
                compactLabels: ["A", "L", "S", "Z"],
                whichLabels: null,
                timeSeparator: ':', isRTL: false};
            jQuery.countdown.setDefaults(jQuery.countdown.regional["uji"]);
            jQuery("#ujiCountdown").countdown({until: austDay, serverSync: serverTime, text_size: ''+ ujic_txt_size +'', color_down: ''+ ujic_col_dw +'', color_up: ''+ ujic_col_up +'', color_txt:  ''+ ujic_col_txt +'', color_sw:  ''+ ujic_col_sw +'',  ujic_txt: ujic_txt, animate_sec: ujic_ani, ujic_url: ''+ ujic_url +'' });
			if(uji_center && uji_center!='')	
           		 jQuery("#ujiCountdown").css({"width": (jQuery("#ujiCountdown").width()+5)+"px", "padding-left": "10px", "display": "block"});
			});
 
// Get Server Time
function serverTime() { 
    var time = null; 
    jQuery.ajax({url: uji_plugin + '/js/serverTime.php', 
        async: false, dataType: 'text', 
        success: function(text) { 
            time = new Date(text); 
        }, error: function(http, message, exc) { 
            time = new Date(); 
    }}); 
    return time; 
} 