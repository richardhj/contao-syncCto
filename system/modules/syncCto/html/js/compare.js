/* Copyright (c) MEN AT WORK 2012 :: LGPL license */
window.addEvent("domready",function(){if($("db_form")){$$("input[name=transfer]").set("disabled",true)}else{$$("input[name=delete]").set("disabled",true)}$each($$(".checkbox input[type=checkbox]"),function(b){b.addEvents({click:function(){var c=($$(".checkbox input[type=checkbox]:checked").length!=0)?true:false;if(c==true){$$("input[name=delete]").set("disabled",false);if($("db_form")){$$("input[name=transfer]").set("disabled",false)}else{$$("input[name=transfer]").set("disabled",true)}}else{$$("input[name=delete]").set("disabled",true);if($("db_form")){$$("input[name=transfer]").set("disabled",true)}else{$$("input[name=transfer]").set("disabled",false)}}}})});if(window.HtmlTable){$$("body").addClass("table-sort");HtmlTable.defineParsers({dimension:{match:".*(Bytes|kB|mB|gB)",convert:function(){var c=this.get("text").replace(/.*(\d|,| )/,"").toString().toLowerCase();var b=this.get("text").replace(",",".").toFloat();if(c=="kb"){b=b*1024}else{if(c=="mb"){b=b*[Math.pow(1024,2)]}else{if(c=="gb"){b=b*[Math.pow(1024,3)]}}}return b},number:true}});var a=new HtmlTable($("filelist"),{sortIndex:0,parsers:["string","dimension","string"],sortable:true}).enableSort({sortable:true,sortIndex:0})}});