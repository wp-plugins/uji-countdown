(function(e){"use strict";e(function(){function Y(){var e=null;if(V){return false}else{e=new Date(z);var t=new Date(e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate(),e.getUTCHours(),e.getUTCMinutes(),e.getUTCSeconds());e=t;return e}}var t="ujiCount";var n=window[t];var r=n.uji_style;var i=n.uji_plugin;var s=n.ujic_id;var o=n.expire;var u=n.exp_days;var a=n.Years;var f=n.Months;var l=n.Weeks;var c=n.Days;var h=n.Hours;var p=n.Minutes;var d=n.Seconds;var v=n.Year;var m=n.Month;var g=n.Week;var y=n.Day;var b=n.Hour;var w=n.Minute;var E=n.Second;var S=n.ujic_txt_size;var x=n.ujic_col_dw;var T=n.ujic_col_up;var N=n.ujic_col_txt;var C=n.ujic_col_sw;var k=n.ujic_col_lab;var L=n.ujic_lab_sz;var A=n.ujic_y;var O=n.ujic_o;var M=n.ujic_w;var _=n.ujic_d;var D=n.ujic_h;var P=n.ujic_m;var H=n.ujic_s;var B=n.ujic_thick;var j=n.ujic_txt=="true"?true:false;var F=n.ujic_ani=="true"?true:false;var I="http://";var q=n.ujic_url?n.ujic_url.substr(0,I.length)!==I?I+n.ujic_url:n.ujic_url:"";var R=n.ujic_goof;var U=n.uji_center;var z=n.uji_time;var W=n.uji_hide;var X=n.ujic_rtl=="true"?true:false;var V=n.uji_utime=="true"?true:false;var J=new Date(""+o+"");var K="";K+=A=="true"?"Y":"";K+=O=="true"?"O":"";K+=M=="true"?"W":"";K+=_=="true"?"D":"";K+=D=="true"?"H":"";K+=P=="true"?"M":"";K+=H=="true"?"S":"";var Q=[];if(A=="true")Q.push("uji_year");if(O=="true")Q.push("uji_mont");if(M=="true")Q.push("uji_week");if(_=="true")Q.push("uji_days");if(D=="true")Q.push("uji_hour");if(P=="true")Q.push("uji_minu");if(H=="true")Q.push("uji_secu");e.countdown.regionalOptions["uji"]={labels:[""+a+"",""+f+"",""+l+"",""+c+"",""+h+"",""+p+"",""+d+""],labels1:[""+v+"",""+m+"",""+g+"",""+y+"",""+b+"",""+w+"",""+E+""],compactLabels:["A","L","S","Z"],format:K,whichLabels:null,timeSeparator:":",isRTL:false};e.countdown.setDefaults(e.countdown.regionalOptions["uji"]);if(R){var G=R.replace(/\s+/g,"+");e("head").append('<link href="http://fonts.googleapis.com/css?family='+G+'" rel="stylesheet" type="text/css">')}e("#ujiCountdown").countdown({until:J,ujic_id:""+s+"",serverSync:Y,isRTL:X,text_size:""+S+"",color_down:""+x+"",color_up:""+T+"",color_txt:""+N+"",color_sw:""+C+"",color_lab:""+k+"",lab_sz:""+L+"",ujic_txt:j,animate_sec:F,ujic_hide:W,expiryUrl:q,ujic_goof:""+R+""})})})(jQuery)