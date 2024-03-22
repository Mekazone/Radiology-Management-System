function createRequestObject(){
var request_o;
var browser = navigator.appName;
if(browser == "Microsoft Internet Explorer"){
request_o = new ActiveXObject("Microsoft.XMLHTTP");
} else{
request_o = new XMLHttpRequest();
}
return request_o;
}

var http = createRequestObject();

function getEvent(eventid){
http.open('get', 'internal_request.php?action=getevent&id=' + eventid);
http.onreadystatechange = handleEvent;
http.send(null);
}

function getLogin(){
http.open('get', 'change_login.php');
http.onreadystatechange = handleEvent;
http.send(null);
}

function goHome(){
http.open('get', 'index.php');
http.onreadystatechange = handleEvent1;
http.send(null);
}

function reminder(){
http.open('get', 'reminder.php');
http.onreadystatechange = handleEvent4;
http.send(null);
}

function searchPage(){
http.open('get', 'search.php');
http.onreadystatechange = handleEvent;
http.send(null);
}

function printSchedule(){
http.open('get', 'print_schedule.php');
http.onreadystatechange = handleEvent;
http.send(null);
}

function backupInfo(){
http.open('get', 'backup.php');
http.onreadystatechange = handleEvent;
http.send(null);
}

function restoreInfo(){
http.open('get', 'info_restore.php');
http.onreadystatechange = handleEvent;
http.send(null);
}

function thisMonth(month,year){
http.open('get', 'calendar.php?date=' + month + '-' + year);
http.onreadystatechange = handleEvent2;
http.send(null);
}

function searchMonth(calendar){
http.open('get', 'calendar.php?date=' + calendar);
http.onreadystatechange = handleEvent2;
http.send(null);
}

function handleEvent(){
if(http.readyState == 4){
var response = http.responseText;
document.getElementById('eventcage').innerHTML = response;
}
}

function handleEvent1(){
if(http.readyState == 4){
var response = http.responseText;
document.getElementById('login').innerHTML = response;
}
}

function handleEvent2(){
if(http.readyState == 4){
var response = http.responseText;
document.getElementById('calendar').innerHTML = response;
}
}

function handleEvent4(){
if(http.readyState == 4){
var response = http.responseText;
document.getElementById('reminder').innerHTML = response;
}
}

function newEvent(eventdate, error){
http.open('get', 'neweventform.php?error=' + error + "&date=" + eventdate);
http.onreadystatechange = handleNewEvent;
http.send(null);
}

function handleNewEvent(){
if(http.readyState == 4){
var response = http.responseText;
document.getElementById('eventcage').innerHTML = response;
}
}

function demo_info(){
	alert("This feature can only be accessed in the full version.");
}