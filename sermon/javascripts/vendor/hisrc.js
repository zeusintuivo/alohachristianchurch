// Copyright (c) 2012 Adam Bradley

// Licensed under the MIT license.

// Copyright (c) Faruk Ates, Paul Irish, Alex Sexton

// Available under the BSD and MIT licenses: www.modernizr.com/license/

(function(e){e.hisrc={bandwidth:null,connectionTestResult:null,connectionKbps:null,connectionType:null,devicePixelRatio:null},e.hisrc.defaults={useTransparentGif:!1,transparentGifSrc:"data:image/gif;base64,R0lGODlhAQABAIAAAMz/AAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==",minKbpsForHighBandwidth:300,speedTestUri:"https://s3.amazonaws.com/cdeutsch/50K",speedTestKB:50,speedTestExpireMinutes:30,forcedBandwidth:!1},e.hisrc.speedTest=function(){e(window).hisrc()},e.fn.hisrc=function(t){var n=e.extend({},e.hisrc.defaults,t),r=e(this),i=navigator.connection||{type:0},s=i.type==3||i.type==4||/^[23]g$/.test(i.type);e.hisrc.devicePixelRatio=1,window.devicePixelRatio!==undefined&&(e.hisrc.devicePixelRatio=window.devicePixelRatio);var o=n.speedTestUri,u="loading",a="complete",f="fsjs",l,c=function(){if(l)return;if(n.forcedBandwidth){e.hisrc.bandwidth=n.forcedBandwidth,e.hisrc.connectionTestResult="forced",l=a,r.trigger("speedTestComplete.hisrc");return}if(e.hisrc.devicePixelRatio===1){e.hisrc.connectionTestResult="skip",l=a,r.trigger("speedTestComplete.hisrc");return}e.hisrc.connectionType=i.type;if(s){e.hisrc.connectionTestResult="connTypeSlow",l=a,r.trigger("speedTestComplete.hisrc");return}try{var t=JSON.parse(localStorage.getItem(f));if(t!==null&&(new Date).getTime()<t.exp){e.hisrc.bandwidth=t.bw,e.hisrc.connectionKbps=t.kbps,e.hisrc.connectionTestResult="localStorage",l=a,r.trigger("speedTestComplete.hisrc");return}}catch(c){}var p=document.createElement("img"),d,v,m;p.onload=function(){d=(new Date).getTime();var t=(d-v)/1e3;t=t>1?t:1,e.hisrc.connectionKbps=n.speedTestKB*1024*8/t/1024,e.hisrc.bandwidth=e.hisrc.connectionKbps>=n.minKbpsForHighBandwidth?"high":"low",h("networkSuccess")},p.onerror=function(){h("networkError",5)},p.onabort=function(){h("networkAbort",5)},v=(new Date).getTime(),l=u,document.location.protocol==="https:"&&(o=o.replace("http:","https:")),p.src=o+"?r="+Math.random(),m=n.speedTestKB*8/n.minKbpsForHighBandwidth*1e3+350,setTimeout(function(){h("networkSlow")},m)},h=function(t,i){if(l===a)return;l=a,e.hisrc.connectionTestResult=t;try{i||(i=n.speedTestExpireMinutes);var s={kbps:e.hisrc.connectionKbps,bw:e.hisrc.bandwidth,exp:(new Date).getTime()+i*6e4};localStorage.setItem(f,JSON.stringify(s))}catch(o){}r.trigger("speedTestComplete.hisrc")},p=function(e,t){n.useTransparentGif?e.attr("src",n.transparentGifSrc).css("max-height","100%").css("max-width","100%").css("background",'url("'+t+'") no-repeat 0 0').css("background-size","contain"):e.attr("src",t)};return r.each(function(){var t=e(this);t.data("m1src")||t.data("m1src",t.attr("src")),!t.attr("width")&&t.width()>0&&t.attr("width",t.width()),!t.attr("height")&&t.height()>0&&t.attr("height",t.height()),t.on("speedTestComplete.hisrc",function(){l===a&&(s?t.attr("src",t.data("m1src")):e.hisrc.devicePixelRatio>1&&e.hisrc.bandwidth==="high"?p(t,t.data("2x")):p(t,t.data("1x")),t.off("speedTestComplete.hisrc"))})}),c(),r}})(jQuery);