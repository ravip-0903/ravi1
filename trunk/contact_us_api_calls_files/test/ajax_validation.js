var httpRequest=createRequestObject();
var newTitlePage = "";
var divname = "";
var emailEntered = false;
var phoneEntered = false;
var OrderIdEntered  = false;
var IssueTypeEnetered = false;
var subjectEntered  = false;
function createRequestObject()
	{
		var browser=navigator.appName;
		if(browser == "Microsoft Internet Explorer"){
    		return new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
    		return new XMLHttpRequest();
		}  
		
	}
function showData(fname,csum){
	//displayToggle('_');
	//alert('here1');
	if(fname.length >0){
		//loadAjaxElementDiv();
		var filename=fname;
		var chksum=csum;
		var start = new Date();
		setval="YES";
		//alert(filename+"?AJAX="+setval+"&checksum="+chksum);
		httpRequest.open("GET",filename+"?AJAX=YES&checksum="+chksum+"&ts="+start);
		httpRequest.onreadystatechange = handleResponseMenuData;
		httpRequest.send(null);
	}else{
		alert('Please Select an option First');
	}
}
function evalScript(scripts)
{	try
	{	if(scripts != '')	
		{	var script = "";
			scripts = scripts.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, function(){
	       	                         if (scripts !== null) script += arguments[1] + '\n';
 	        	                        return '';});
 	        	                      //  alert(script);
			if(script) (window.execScript) ? window.execScript(script) : window.setTimeout(script, 0);
		}
		return false;
	}
	catch(e)
	{	alert(e)
	}
}
function handleResponseMenuData()
{
	if(httpRequest.readyState == 4)
	{
		var response=httpRequest.responseText;
		//alert(httpRequest.Content-Type);
		if(response.indexOf("Not Found") != -1)
			document.getElementById('htm2display').innerHTML="Requested Page is not available now.<br> Please try later";
		else{
				document.getElementById('htm2display').innerHTML=response;
				//script2exe = document.getElementById("script2exe");
				evalScript( response );
				//if(document.getElementById('PageTitle')!= null)
				//document.getElementById('PageTitle').value = newTitlePage;
				//drawChart();	
				//alert('operationDone');
		}

		if(response == 1)
		{
			//return false;
		}
		hideAjaxElementDiv();
	}else{
			document.getElementById('htm2display').innerHTML="<img src='loadera64.gif'>";
		}
}
function showPostData(fname,csum,form){
 //displayToggle('_');
 if( OrderIdEntered && IssueTypeEnetered && subjectEntered  ){
 if(fname.length >0){
  var filename=fname;
  var chksum=csum;
  var start = new Date();
  var postString = generatePostQuery(form);
  if(document.getElementById('orderid').value == "" ){
   return false;
  }
  setval="YES";
  if(divname == ""){
   divname = "htm2display";
  }
  document.getElementById(divname).innerHTML="<img src='loadera64.gif'>";
  httpRequest.open("POST",filename+"?AJAX=YES&checksum="+chksum+"&ts="+start);
  httpRequest.onreadystatechange = handleResponseMenuData;
  httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  httpRequest.setRequestHeader("Content-length", postString.length);
  httpRequest.setRequestHeader("Connection", "close");
  httpRequest.send(postString);
 }else{
  alert('Please Select an option First');
 }
 }
}

function generatePostQuery(form)
{
doc = form;
var alertString = "";
var    postString = "";
    for(i=0; i<doc.elements.length; i++)
    {
        alertString = "";
        if(doc.elements[i].disabled ==false){
	        if(doc.elements[i].type=="button"){
	        	continue;
	        }else if(doc.elements[i].type=="checkbox"){
                if(doc.elements[i].checked==true){alertString += doc.elements[i].value;}
                else{ continue;}
            }else if(doc.elements[i].type=="radio"){
                if(doc.elements[i].checked==true){alertString += doc.elements[i].value;}
                else{continue;}
            }else if(doc.elements[i].type=="select-one"){
                alertString += doc.elements[i].value;
            }else if(doc.elements[i].type=="checkbox"){
                if(doc.elements[i].checked==true){alertString += doc.elements[i].value;}
                else{continue;}
            }
            else {alertString += doc.elements[i].value;}
            if(alertString == ""){alertString="NULL";}
            postString += doc.elements[i].name+"="+encodeURI(alertString)+"&";
        }
    }
    return postString;
}
function drawChart(){
         Highcharts.setOptions(
    	   {"global":{"useUTC":true}}
	    	);
	  var piechart = new Highcharts.Chart(
       {"chart":{"renderTo":"piechart","type":"pie"},"title":{"text":"Pie Chart"},"series":[{"name":"myData","data":[5324,7534,6234,7234,8251,10324]}]}
    );
     $("pre.htmlCode").snippet("html",{style: "the", showNum: false});
        $("pre.phpCode").snippet("php",{style: "the", showNum: false});
       
}
function showOrderDetails(fname,csum,value){
	if(fname.length >0){
		var filename=fname;
		var chksum=csum;
		var start = new Date();
		setval="YES";
//		value ;
//		alert(value.length);
		if(value.length == 0 )
			return;
		
		getAjaxData(fname,csum+'&orderid='+value,'htm2display');
	}else{
		alert('Please Select an option First');
	}
}
function getAjaxData(fname,csum,divname){
	var filename=fname;
	var chksum=csum;
	var start = new Date();
	setval="YES";
		document.getElementById(divname).innerHTML="<img src='loadera64.gif'>";
	httpRequest.open("POST",filename+"?AJAX=YES&checksum"+csum+"&ts="+start);
	httpRequest.onreadystatechange = function(){
		if(httpRequest.readyState == 4){
			var response=httpRequest.responseText;
			//alert(response);
			if(response.indexOf("Not Found") != -1)
				document.getElementById(divname).innerHTML="Requested Page is not available now.<br> Please try later";
			else{
				document.getElementById(divname).innerHTML=response;
				evalScript( response );
			}
			if(response == 1){
				//return false;
			}
			//hideAjaxElementDiv();
		}else{
		
		}
	};
	httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	httpRequest.setRequestHeader("Content-length", csum.length);
	httpRequest.setRequestHeader("Connection", "close");
	httpRequest.send(csum);
}
function getSubAjaxData(fname,csum,divname){
	var filename=fname;
	var chksum=csum;
	var start = new Date();
	setval="YES";
	loadAjaxElementDiv();
	//alert(filename+"?AJAX="+setval+"&checksum="+chksum);
	httpRequest.open("GET",filename+"?AJAX=YES&GET_KEY=1&checksum="+chksum+"&ts="+start);
	httpRequest.onreadystatechange = function(){
		if(httpRequest.readyState == 4){
			var response=httpRequest.responseText;
			//alert(response);
			if(response.indexOf("Not Found") != -1)
				document.getElementById(divname).innerHTML="Requested Page is not available now.<br> Please try later";
			else{
				document.getElementById(divname).innerHTML=response;
				evalScript( response );
			}
			if(response == 1){
				//return false;
			}
			hideAjaxElementDiv();
		}
	};
	httpRequest.send(null);
}
function loadAjaxElementDiv(){
	document.getElementById('htm2display').innerHTML = "";
	if(document.getElementById('loadAjaxElementDiv')!=null)
	document.getElementById('loadAjaxElementDiv').style.display = "Block";
	if(document.getElementById('loadAjaxElementDiv')!=null)
	document.getElementById('loadAjaxElementDiv').style.visibility = "visible";
}
function hideAjaxElementDiv(){
	if(document.getElementById('loadAjaxElementDiv')!=null)
	document.getElementById('loadAjaxElementDiv').style.display = "none";
	if(document.getElementById('loadAjaxElementDiv')!=null)
	document.getElementById('loadAjaxElementDiv').style.visibility = "hidden";

}
