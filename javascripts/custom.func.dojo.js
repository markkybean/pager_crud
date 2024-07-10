    dojo.require("dijit.dijit");
    dojo.require("dojo.parser");
    dojo.require("dijit.Declaration");
    dojo.require("dijit.form.Button");
    dojo.require("dijit.Toolbar");
    dojo.require("dijit.layout.BorderContainer");
    dojo.require("dijit.layout.LayoutContainer");
    dojo.require("dijit.layout.SplitContainer");
    dojo.require("dijit.layout.AccordionContainer");
    dojo.require("dijit.layout.ContentPane");

    dojo.addOnLoad(function(){
      dijit.setWaiRole(dojo.body(), "application");
    });

    var paneId=1;


dojo.addOnLoad(function(){

    dojo.parser.parse();
    dijit.setWaiRole(dojo.body(), "application");

    var n = dojo.byId("preLoader");
    dojo.fadeOut({
        node:n,
        duration:720,
        onEnd:function(){
	        // dojo._destroyElement(n); 
	        dojo.style(n,"display","none");
        }
    }).play();

});

function hide_app_toolbar(){
    document.getElementById('tbrmain').style.display='none';
    dijit.byId('main').resize();
}

function show_app_toolbar(){
    document.getElementById('tbrmain').style.display='';
    dijit.byId('main').resize();
}

function show_app_sidemenu(){
    document.getElementById('sidemenu').style.display='';
    dijit.byId('contents').resize();
}

function hide_app_sidemenu(){
    document.getElementById('sidemenu').style.display='none';
    dijit.byId('contents').resize();
}
