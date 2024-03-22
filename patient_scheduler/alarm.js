<!-- Copyright 2002, Sandeep Gangadharan -->
<!-- For more free scripts go to http://sivamdesign.com/scripts/ -->

<!--
var sound = "alarm.wma";   // change the name of the wave file to be used as the alarm at left. include
                              // the full path to the file. remember not to remove the quotation marks.

function sivamtime() {
	now=new Date();
	hour=now.getHours();
	min=now.getMinutes();
	sec=now.getSeconds();

if (min<=9) {
	min="0"+min;
 }
if (sec<=9) {
	sec="0"+sec;
 }
if (hour>12) {
	hour=hour-12;
	add="pm";
 }
else {
	hour=hour;
	add="am";
 }
if (hour==12) {
	add="pm";
 }
if (hour==00) {
	hour="12";
 }

  document.hours.clock.value = (hour<=9) ? "0"+hour : hour;
  document.minutes.clock.value = min;
  document.seconds.clock.value = sec;
  document.ampm.clock.value= add;
  setTimeout("sivamtime()", 1000);

}

function alarm() {
    note = document.arlm.message.value;
    if (note == '') {note = 'ALARM!!';}

    hrs = document.arlm.hr.value;
    min = document.arlm.mts.value;
    apm = document.arlm.am_pm.value;

 if ((document.hours.clock.value == hrs) &&
    (document.minutes.clock.value == min) &&
    (document.ampm.clock.value == apm) &&
    (document.arlm.music.checked == true)) {
   musicwin=window.open("","","width=200,height=50")
   musicwin.document.write("<title>ALARM!!!</title>");
  if (navigator.appName=="Microsoft Internet Explorer")
   musicwin.document.write("<bgsound src=" + sound + " loop='infinite'>" + note)
  else
   musicwin.document.write("<embed src=" + sound + " hidden='true' border='0' width='20' height='20' autostart='true' loop='true'>" + note)
   musicwin.document.close(); return false; }

 if ((document.hours.clock.value == hrs) &&
    (document.minutes.clock.value == min) &&
    (document.ampm.clock.value == apm) &&
    (document.arlm.music.checked == false)) {
 alert(note); return false; }

 if (hrs == '') {alert('The Hour field is empty'); return false}
 if (min == '') {alert('The Minute field is empty'); return false}
 if (apm == '') {alert('The am/pm field is empty'); return false}

 if (hrs.length == 1) {document.arlm.hr.value = '0' + hrs}
 if (min.length == 1) {document.arlm.mts.value = '0' + min}
 if (hrs.length > 2) {alert('The Hour is wrongly typed.'); return false}
 if (min.length > 2) {alert('The Minute is wrongly typed.'); return false}
 if (apm != 'am' && apm != 'pm' ) {alert('The am/pm is wrongly typed.'); return false}

 t=setTimeout("alarm()", 1000);
 
}

//-->