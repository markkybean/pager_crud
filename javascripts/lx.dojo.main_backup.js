dojo.require("dijit.layout.ContentPane");
dojo.require("dijit.layout.BorderContainer");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.layout.AccordionContainer");
dojo.require("dijit.form.Form");
dojo.require("dijit.form.DateTextBox");
dojo.require("dijit.form.NumberSpinner");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.ValidationTextBox");
dojo.require("dijit.form.ComboBox");
dojo.require("dijit.form.FilteringSelect");
dojo.require("dijit.form.Select");

dojo.require("dojo.parser");

var hidepreloader = function(){
	try
	{
		//dojo.style("lxyuicrud", "display", "");
		YAHOO.example.container.wait.hide();		
	}
	catch(e)
	{
	
	}
}

dojo.addOnLoad(function(){
    dojo.parser.parse();
    hidepreloader();
});
