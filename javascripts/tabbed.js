///////////////////////////////////////////////////////////////////////////////////
//* mhaTabPane version 0.1 by Martin Hintzmann Andersen http://www.hintzmann.dk *// 
///////////////////////////////////////////////////////////////////////////////////

/////////////////
// Namespacing //
/////////////////
if (!dk) {
  var dk = {} ;
  dk.hintzmann = {} ;
  dk.hintzmann.mhaTabPane = {};
} else if (!dk.hintzmann) {
  dk.hintzmann = {};
  dk.hintzmann.mhaTabPane = {};
} else {
  dk.hintzmann.mhaTabPane = {};
}
var tp = dk.hintzmann.mhaTabPane;

// Global variables
var mhaTabPaneChoosen;

// Local default variables
tp.mhaTabPaneSetup = {
  defTabPaneName    : 'Tab ',
	defTabChoosen			: 0    
}

tp.standards = (document.getElementById &&
								document.getElementsByTagName && 
                document.createElement);
								
///////////////////
// Find TabPanes //
///////////////////
tp.findTabPanes = function() {
	aMhaTabPane = tp.getElementsWithClassName(document, 'mhaTabPane')
	for (var i=0;i<aMhaTabPane.length;i++) { 
		tp.buildTabPane(aMhaTabPane[i].id);
	}
}

////////////////////
// Build TabPanes //
////////////////////
tp.buildTabPane = function(oID) {
	oTabs = document.getElementById(oID);

	aTabpanel = tp.getElementsWithClassName(oTabs, 'tabpanel')
	aTabpanel[0].setAttribute('id','tabpanel-'+oID+'');
	oTabpanel = document.getElementById('tabpanel-'+oID+'');

	aTabpage = tp.getElementsWithClassName(oTabs,'tabpage');

	for (var i=0;i<aTabpage.length;i++) {
		aTabpage[i].setAttribute('id','tabpage-'+oID+'-'+i+'');
		oTabpage = document.getElementById('tabpage-'+oID+'-'+i+'');
		oTabpage.style.display = 'none';

		aTabname = tp.getElementsWithClassName(oTabpage, 'tabname')
		if (aTabname[0]) {
			oTabname = aTabname[0];
			oTabname.style.display = 'none';
			sTabname = oTabname.childNodes[0].nodeValue;
//			sTabname = oTabname.innerHTML;
		} else {
			sTabname = tp.mhaTabPaneSetup.defTabPaneName+(i+1);
		}

		oA = document.createElement('A');
		oA.onfocus = function(){ this.blur() };
		oA.setAttribute('href','javascript:showtab(\''+oID+'\',\''+i+'\')')
		oA.appendChild(document.createTextNode(sTabname))		
		oTabpanel.appendChild(oA)
	}
	showtab(oID, mhaTabPaneChoosen? mhaTabPaneChoosen: tp.mhaTabPaneSetup.defTabChoosen );	
}

//////////////////////////////////
// Get Elements With Class Name //
//////////////////////////////////
tp.getElementsWithClassName = function (el,className) {
	var nodeList;
	var searchObj = new Array();
	nodeList = el.all || el.getElementsByTagName("*");
		for (var i = 0, c = null, cn; (document.all ? c = nodeList(i) : c = nodeList.item(i)); i++) {
		if (c.nodeType == 1) {
				cn = c.className.split(" ");
				for (j = 0; j < cn.length; j++) {
					if (cn[j]==className) {
						searchObj[searchObj.length] = c;
					}
				}
		}
		}
  return searchObj;
}

//////////////////////
// Link to CSS-file //
//////////////////////
tp.link2CSS = function(uri) { 
	var oLink = document.createElement('link'); // Create a new stylesheet
	oLink.setAttribute('rel','stylesheet'); 
	oLink.setAttribute('type','text/css'); 
	oLink.setAttribute('href',uri); 
	var oHead = document.getElementsByTagName('head').item(0); 
	oHead.appendChild(oLink); // Append stylesheet to current site
} 

///////////////
// Show Tabs //
///////////////
function showtab(oID, tabid) {
	oTabs = document.getElementById(oID);
	oTabpanel = document.getElementById('tabpanel-'+oID+'');
	aTabpanel = oTabpanel.getElementsByTagName('A');
	aTabpage = tp.getElementsWithClassName(oTabs,'tabpage');
	for (var z=0;z<aTabpanel.length;z++) {
		if (z == tabid) {
			aTabpage[z].style.display = 'block';
			aTabpanel[z].className = 'activeTab';
		}
		else {
			aTabpage[z].style.display = 'none';
			aTabpanel[z].className = '';
		}
	}
}


//////////
// init //
//////////
tp.init = function() {
	tp.findTabPanes();
	tp.link2CSS('mhaTabPane.css');
}

////////////
// Onload //
////////////

if (tp.standards) 
{
	if (window.addEventListener) {
		window.addEventListener("load",tp.init,true);
	} else if (window.attachEvent){
		window.attachEvent("onload",tp.init);
	} else {
		window.onload = tp.init;
	}
}
