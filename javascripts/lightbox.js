/*
Created By: Chris Campbell
Website: http://particletree.com
Date: 2/1/2006

Inspired by the lightbox implementation found at http://www.huddletogether.com/projects/lightbox/
*/

/*-------------------------------GLOBAL VARIABLES------------------------------------*/

var detect = navigator.userAgent.toLowerCase();
var OS,browser,version,total,thestring;

/*-----------------------------------------------------------------------------------------------*/

//Browser detect script origionally created by Peter Paul Koch at http://www.quirksmode.org/

function getBrowserInfo() {
	if (checkIt('konqueror')) {
		browser = "Konqueror";
		OS = "Linux";
	}
	else if (checkIt('safari')) browser 	= "Safari"
	else if (checkIt('omniweb')) browser 	= "OmniWeb"
	else if (checkIt('opera')) browser 		= "Opera"
	else if (checkIt('webtv')) browser 		= "WebTV";
	else if (checkIt('icab')) browser 		= "iCab"
	else if (checkIt('msie')) browser 		= "Internet Explorer"
	else if (!checkIt('compatible')) {
		browser = "Netscape Navigator"
		version = detect.charAt(8);
	}
	else browser = "An unknown browser";

	if (!version) version = detect.charAt(place + thestring.length);

	if (!OS) {
		if (checkIt('linux')) OS 	= "Linux";
		else if (checkIt('x11')) OS 	= "Unix";
		else if (checkIt('mac')) OS 	= "Mac"
		else if (checkIt('win')) OS 	= "Windows"
		else OS 								= "an unknown operating system";
	}
}

function checkIt(string) {
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}

/*-----------------------------------------------------------------------------------------------*/

Event.observe(window, 'load', initialize, false);
Event.observe(window, 'load', getBrowserInfo, false);
Event.observe(window, 'unload', Event.unloadCache, false);

var progressbox = Class.create();

progressbox.prototype = {
	
	yPos : 0,
	xPos : 0,

	initialize: function(ctrl) {
		this.content = ctrl.href;		
		Event.observe(ctrl, 'click', this.activate.bindAsEventListener(this), false);
		ctrl.onclick = function(){return false;};
	},
	
	// Turn everything on - mainly the IE fixes
	activate: function(){
		if (browser == 'Internet Explorer'){
			this.getScroll();
			this.prepareIE('100%', 'hidden');
			this.setScroll(0,0);
			this.hideSelects('hidden');
		}
		this.displayLightbox("block");		
	},
	
	// Ie requires height to 100% and overflow hidden or else you can scroll down past the lightbox
	prepareIE: function(height, overflow){
		bod = document.getElementsByTagName('body')[0];
		bod.style.height = height;
		bod.style.overflow = overflow;
  
		htm = document.getElementsByTagName('html')[0];
		htm.style.height = height;
		htm.style.overflow = overflow; 
	},
	
	// In IE, select elements hover on top of the lightbox
	hideSelects: function(visibility){
		selects = document.getElementsByTagName('select');
		for(i = 0; i < selects.length; i++) {
			selects[i].style.visibility = visibility;
		}
	},
	
	// Taken from lightbox implementation found at http://www.huddletogether.com/projects/lightbox/
	getScroll: function(){
		if (self.pageYOffset) {
			this.yPos = self.pageYOffset;
		} else if (document.documentElement && document.documentElement.scrollTop){
			this.yPos = document.documentElement.scrollTop; 
		} else if (document.body) {
			this.yPos = document.body.scrollTop;
		}
	},
	
	setScroll: function(x, y){
		window.scrollTo(x, y); 
	},
	
	displayLightbox: function(display){		
		$('overlay2').style.display = display;
		$('progressbox').style.display = display;
		
		
		//$('progressbox').style.zIndex  = -1;
		
		if(display != 'none') this.loadInfo();		
	},
	
	// Begin Ajax request based off of the href of the clicked linked
	loadInfo: function() {
		var myAjax = new Ajax.Request(
        this.content,
        {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
		);
		
	},
	
	// Display Ajax response
	processInfo: function(response){
		info = "<div id='prgContent'>" + response.responseText + "</div>";
		new Insertion.Before($('prgLoadMessage'), info)
		$('progressbox').className = "done";	
		this.actions();			
	},
	
	processInfo2: function(response,xelement){		
		document.getElementById(xelement).innerHTML = response.responseText;
		this.actions2(xelement);
	},
	
	
	// Search through new links within the lightbox, and attach click event
	actions: function(){
		prgActions = document.getElementsByClassName('prgAction');

		for(i = 0; i < prgActions.length; i++) {
			Event.observe(prgActions[i], 'click', this[prgActions[i].rel].bindAsEventListener(this), false);
			prgActions[i].onclick = function(){return false;};
		}

	},
	
	actions2: function(xelement){
		prgActions = document.getElementsByClassName(xelement);

		for(i = 0; i < prgActions.length; i++) {
			Event.observe(prgActions[i], 'click', this[prgActions[i].rel].bindAsEventListener(this), false);
			prgActions[i].onclick = function(){return false;};
		}
	},
	
	// Example of creating your own functionality once lightbox is initiated
	insert: function(e){
	   link = Event.element(e).parentNode;
	   Element.remove($('prgContent'));
	 
	   var myAjax = new Ajax.Request(
			  link.href,
			  {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
	   );
	 
	},
	
	// Example of creating your own functionality once lightbox is initiated
	deactivate: function(){
		Element.remove($('prgContent'));
		
		if (browser == "Internet Explorer"){
			this.setScroll(0,this.yPos);
			this.prepareIE("auto", "auto");
			this.hideSelects("visible");
		}		
		this.displayLightbox("none");		
                
              
	},
	
	showprogressbox: function(xwidth,xheight)
	{
		
		$('progressbox').style.backgroundColor="transparent";
		$('progressbox').style.border="none";
		
		try
		{
		   Element.remove($('prgContent'));	
		}
		catch(ex)
		{
		   
		}
				
		
		if (browser == "Internet Explorer"){
			this.setScroll(0,this.yPos);
			this.prepareIE("auto", "auto");
			this.hideSelects("visible");
		}
		
		
		var p = 'px';
		$('progressbox').style.width=xwidth + p;
		$('progressbox').style.height=xheight + p;
		
		var sX=window.top.document.body.clientWidth;
		var sY=window.top.document.body.clientHeight;				
		
		var x_pos = (sX / 2) - (xwidth / 2);
		var y_pos = (sY / 2) - (xheight / 2);
		
		var tp=Math.floor(y_pos);
		var lt=Math.floor(x_pos);		
		
		$('progressbox').style.top=tp + p;
		$('progressbox').style.left=lt + p;		
		
		
		this.displayLightbox("block");		
		
	}
	
}


var lightbox = Class.create();

lightbox.prototype = {

	yPos : 0,
	xPos : 0,
	
	xHeight : 0,
	xWidth : 0,

	initialize: function(ctrl) {
		this.content = ctrl.href;
		
		Event.observe(ctrl, 'click', this.activate.bindAsEventListener(this), false);
		ctrl.onclick = function(){return false;};
	},
	
	// Turn everything on - mainly the IE fixes
	activate: function(){
		if (browser == 'Internet Explorer'){
			this.getScroll();
			this.prepareIE('100%', 'hidden');
			this.setScroll(0,0);
			this.hideSelects('hidden');
		}
		this.displayLightbox("block");
	},
	
	// Ie requires height to 100% and overflow hidden or else you can scroll down past the lightbox
	prepareIE: function(height, overflow){
		bod = document.getElementsByTagName('body')[0];
		bod.style.height = height;
		bod.style.overflow = overflow;
  
		htm = document.getElementsByTagName('html')[0];
		htm.style.height = height;
		htm.style.overflow = overflow; 
	},
	
	// In IE, select elements hover on top of the lightbox
	hideSelects: function(visibility){
		selects = document.getElementsByTagName('select');
		for(i = 0; i < selects.length; i++) {
			selects[i].style.visibility = visibility;
		}
	},
	
	// Taken from lightbox implementation found at http://www.huddletogether.com/projects/lightbox/
	getScroll: function(){
		if (self.pageYOffset) {
			this.yPos = self.pageYOffset;
		} else if (document.documentElement && document.documentElement.scrollTop){
			this.yPos = document.documentElement.scrollTop; 
		} else if (document.body) {
			this.yPos = document.body.scrollTop;
		}
	},
	
	setScroll: function(x, y){
		window.scrollTo(x, y); 
	},
	
	displayLightbox: function(display){
		$('overlay').style.display = display;
		$('lightbox').style.display = display;
		if(display != 'none') this.loadInfo();
	},
	
	// Begin Ajax request based off of the href of the clicked linked
	loadInfo: function() {
		var myAjax = new Ajax.Request(
        this.content,
        {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
		);
		
	},
	
	// Display Ajax response
	processInfo: function(response){
		info = "<div id='lbContent'>" + response.responseText + "</div>";
		new Insertion.Before($('lbLoadMessage'), info)
		$('lightbox').className = "done";	
		this.actions();
		
	},
	
	processInfo2: function(response,xelement){		
		document.getElementById(xelement).innerHTML = response.responseText;
		this.actions2(xelement);		
		hideprogressbar();		
	},
	
	
	// Search through new links within the lightbox, and attach click event
	actions: function(){
		lbActions = document.getElementsByClassName('lbAction');

		for(i = 0; i < lbActions.length; i++) {
			Event.observe(lbActions[i], 'click', this[lbActions[i].rel].bindAsEventListener(this), false);
			lbActions[i].onclick = function(){return false;};
		}

	},
	
	actions2: function(xelement){
		lbActions = document.getElementsByClassName(xelement);

		for(i = 0; i < lbActions.length; i++) {
			Event.observe(lbActions[i], 'click', this[lbActions[i].rel].bindAsEventListener(this), false);
			lbActions[i].onclick = function(){return false;};
		}
	},
	
	// Example of creating your own functionality once lightbox is initiated
	insert: function(e){
	   link = Event.element(e).parentNode;
	   Element.remove($('lbContent'));
	 
	   var myAjax = new Ajax.Request(
			  link.href,
			  {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
	   );
	 
	},
	
	// Example of creating your own functionality once lightbox is initiated
	deactivate: function(){
		Element.remove($('lbContent'));
		
		if (browser == "Internet Explorer"){
			this.setScroll(0,this.yPos);
			this.prepareIE("auto", "auto");
			this.hideSelects("visible");
		}
		
		this.displayLightbox("none");		
                
              
	},

        

	submit2: function(){
		Element.remove($('lbContent'));
		
		if (browser == "Internet Explorer"){
			this.setScroll(0,this.yPos);
			this.prepareIE("auto", "auto");
			this.hideSelects("visible");
		}
		
		this.displayLightbox("none");
                alert("helo3")
                //alert(xvalue)
                //alert(window.Parent.document.getElementById('txtmodal').value)
                //window.top.document.getElementById('txtsec1').value=xvalue
                //window.top.document.getElementById('txtsec1').value=document.getElementById('txtmodal').value
                submit3();
	},
	
	process_subform : function(xactionpage,xparameters,xelement){
		
		var myAjax = new Ajax.Request(
			  xactionpage,
			  {method: 'post', parameters: "" + xparameters, onComplete: this.processInfo2.bindAsEventListener(this,xelement)}
		);
			
	},	
	
	showsubform: function(xheight,xwidth,xactionpage)
	{
		
		
		try
		{
		   Element.remove($('lbContent'));	
		}
		catch(ex)
		{
		   
		}
				
		
		if (browser == "Internet Explorer"){
			this.setScroll(0,this.yPos);
			this.prepareIE("auto", "auto");
			this.hideSelects("visible");
		}
		
		var h='hidden';
		var b='block';
		var p='px';    		
		
		var sX=window.top.document.body.clientWidth;
		var sY=window.top.document.body.clientHeight;		
		
		//alert('X : ' + sX + '\nY' + sY);
		
		var eH=parseInt(xheight);
		var eW=parseInt(xwidth);
		
		var xPos = sX / 2 - (eW / 2);
		var yPos = sY / 2 - (eH / 2);
		
		var tp=Math.floor(yPos);
		var lt=Math.floor(xPos);		
		//alert(window.top.document.body.clientHeight);
		//alert(window.top.document.body.clientWidth);
		
		$('lightbox').style.top=tp + p;
		$('lightbox').style.left=lt + p;
		$('lightbox').style.width=eW+p;
		$('lightbox').style.height=eH+p;
		$('lightbox').style.backgroundColor="transparent";
		$('lightbox').style.border="none";
		
		var myAjax = new Ajax.Request(
			  xactionpage,
			  {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
		);
		
		this.displayLightbox("block");
		
		showprogressbar();
		
	},	
	
	show_detail_box: function(x_file,xheight,xwidth){
		try
		{
		   Element.remove($('lbContent'));	
		}
		catch(ex)
		{
		   
		}
				
		
		if (browser == "Internet Explorer"){
			this.setScroll(0,this.yPos);
			this.prepareIE("auto", "auto");
			this.hideSelects("visible");
		}
		
		var h='hidden';
		var b='block';
		var p='px';    		
		
		var sX=window.top.document.body.clientWidth;
		var sY=window.top.document.body.clientHeight;		
		
		//alert('X : ' + sX + '\nY' + sY);
		
		var eH=parseInt(xheight);
		var eW=parseInt(xwidth);
		
		var xPos = sX / 2 - (eW / 2);
		var yPos = sY / 2 - (eH / 2);
		
		var tp=Math.floor(yPos);
		var lt=Math.floor(xPos);		
		//alert(window.top.document.body.clientHeight);
		//alert(window.top.document.body.clientWidth);
		
		$('lightbox').style.top=tp + p;
		$('lightbox').style.left=lt + p;
		$('lightbox').style.width=eW+p;
		$('lightbox').style.height=eH+p;
		$('lightbox').style.backgroundColor="transparent";
		$('lightbox').style.border="none";
		
		info = "<div id='lbContent'><form class='subform'><iframe style='border: none;' src='";
			info += x_file + "' height='" + (eH-50) + p + "' width='" + (eW-20) + p + "' ></iframe>";
			info += "<center><br><input class='defaultfont' style='height: 28px; width: 100px;' type='button' value='Close' onclick=\"closeme();\" />";
			info += "</center><br></form></div>";
			
		new Insertion.Before($('lbLoadMessage'), info)
		
		$('lightbox').className = "done";	
		
		this.displayLightbox("block");
		
		//showprogressbar();
	},	
	
	showErrorMessageBox: function() {		
		
		try
		{
		   Element.remove($('lbContent'));	
		}
		catch(ex)
		{
		   
		}
				
		
		if (browser == "Internet Explorer"){
			this.setScroll(0,this.yPos);
			this.prepareIE("auto", "auto");
			this.hideSelects("visible");
		}
		
		var myAjax = new Ajax.Request(
			  './lperror.html',
			  {method: 'post', parameters: "", onComplete: this.processInfo.bindAsEventListener(this)}
		);
		
		this.displayLightbox("block");
		
		$('lightbox').style.height   = "200px";
		$('lightbox').style.width    = "350px";
		//$('lightbox').style.left     = window.innerWidth - 300;
		//$('lightbox').style.top      = window.innerHeight - 200;
		$('lightbox').style.top      = "50%";
		$('lightbox').style.left     = "50%";
		$('lightbox').style.leftmargin = "-266";
		$('lightbox').style.top      = "50%";
		$('lightbox').style.position = "fixed";
                
	},
	
	showcenter : function()
	{
		
		var h='hidden';
		var b='block';
		var p='px';    		
		
		var sX=window.top.document.body.clientWidth;
		var sY=window.top.document.body.clientHeight;		
		
		var eH=parseInt($('lightbox').innerHeight);
		var eW=parseInt($('lightbox').innerWidth);
		
		var xPos = sX / 2 - (eW / 2);
		var yPos = sY / 2 - (eH / 2);
		
		var tp=Math.floor(yPos);
		var lt=Math.floor(xPos);		
		//alert(window.top.document.body.clientHeight);
		//alert(window.top.document.body.clientWidth);
		
		$('lightbox').style.top=tp + p;
		$('lightbox').style.left=lt + p;
		$('lightbox').style.width=eW+p;
		$('lightbox').style.height=eH+p;
	}
	
	


}


function submit3(xbilist,xvalue)
{
    
    //alert("hell3"+xbilist)
    window.top.document.getElementById(xbilist).value=xvalue
    //window.top.document.getElementById('txtbigideas').value=xvalue
    //window.top.document.getElementById('txttopic').value=xvalue
    lightbox.prototype.deactivate();
    
}

function showmodalbox(xheight,xwidth,xactionpage)
{
	
	lightbox.prototype.showsubform(xheight,xwidth,xactionpage);
	
}

function closeme()
{
    try
    {
	lightbox.prototype.deactivate();
    }
    catch(e)
    {
	
    }
    
}


function close_modal_box()
{
    try
    {
	lightbox.prototype.deactivate();
    }
    catch(e)
    {
	
    }
}

function showprogressbar()
{
    try
    {
	progressbox.prototype.showprogressbox('200','200');
    }
    catch(e)
    {
	
    }
}

function hideprogressbar()
{
    
    try
    {
		progressbox.prototype.displayLightbox("none");
    }
    catch(e)
    {
	
    }
}

function alertme(xmsg)
{
	alert(xmsg);
}

/*-----------------------------------------------------------------------------------------------*/

// Onload, make all links that need to trigger a lightbox active
function initialize(){
	addLightboxMarkup();
	lbox = document.getElementsByClassName('lbOn');
	for(i = 0; i < lbox.length; i++) {
		valid = new lightbox(lbox[i]);
	}
	prgx =  document.getElementsByClassName('prgOn');
	for(i = 0; i < prgx.length; i++) {
		valid = new progressbox(prgx[i]);
	}
	
}

// Add in markup necessary to make this work. Basically two divs:
// Overlay holds the shadow
// Lightbox is the centered square that the content is put into.
function addLightboxMarkup() {
	bod 				= document.getElementsByTagName('body')[0];
	overlay 			= document.createElement('div');	
	overlay.id		= 'overlay';
	
	overlay2 			= document.createElement('div');	
	overlay2.id		= 'overlay2';
	
	lb					= document.createElement('div');
	prg					= document.createElement('div');
	
	prg.id = 'progressbox';
	prg.className = 'loading';
	lb.id				= 'lightbox';
	lb.className 	= 'loading';
	/*
	lb.innerHTML	= '<div id="lbLoadMessage">' +
						  '<p>Loading . . .</p>' +
						  '</div>';
	*/
	
	prg.innerHTML =  '<div id="prgLoadMessage" style="height: 200px; width: 200px; background-color: transparent;" >' +
				'<table align="center" height="100%" width="100%" border="0" class="subform" >' +
				'<tr>' +
				'<td align="center">' +
				'<img alt="Loading" src="./progressbar.gif" />' +
				'</td>' +
				'</tr>' +
				'</table>' +
				'</div>';
	
	lb.innerHTML	= '<div id="lbLoadMessage">' +
				'<p align="left">' +
				'<img alt="Loading" src="./progress.gif" />' +
				'</p>' +
				'</div>';
	
	bod.appendChild(overlay);
	bod.appendChild(overlay2);	
	bod.appendChild(lb);
	bod.appendChild(prg);
	//progressbox.prototype.displayLightbox("none");
}
