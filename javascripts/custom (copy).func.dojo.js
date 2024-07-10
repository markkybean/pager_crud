    dojo.require("dijit.dijit");
    dojo.require("dijit.dijit-all");

    dojo.require("dojo.parser");
    dojo.require("dijit.Declaration");
    dojo.require("dijit.form.Button");
    dojo.require("dijit.Menu");
    dojo.require("dijit.Tree");
    dojo.require("dijit.Tooltip");
    dojo.require("dijit.Dialog");
    dojo.require("dijit.Toolbar");
    dojo.require("dijit._Calendar");
    dojo.require("dijit.ColorPalette");
    dojo.require("dijit.Editor");
    dojo.require("dijit._editor.plugins.LinkDialog");
    dojo.require("dijit.ProgressBar");

    dojo.require("dijit.form.ComboBox");
    dojo.require("dijit.form.CheckBox");
    dojo.require("dijit.form.FilteringSelect");
    dojo.require("dijit.form.Textarea");

    dojo.require("dijit.layout.LayoutContainer");
    dojo.require("dijit.layout.SplitContainer");
    dojo.require("dijit.layout.AccordionContainer");
    dojo.require("dijit.layout.TabContainer");
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

    // make tooltips go down (from buttons on toolbar) rather than to the right
    dijit.Tooltip.defaultPosition = ["above", "below"];	
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
