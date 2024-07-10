jQuery(document).ready(function(){

	jQuery("#loading").dialog({
			autoOpen: false,
			width:200,
			height:100,
			resizable:false,
			modal: true,
			closeOnescape:false,
			open: function(event, ui) { jQuery('.ui-dialog-titlebar-close').hide(); jQuery('.ui-widget-header').hide(); },
			close : function(event, ui) { jQuery('.ui-widget-header').show(); },
			resize : false
		});

});

function open_loading()
{
	jQuery('#loading').dialog('open');
}
function close_loading()
{
	jQuery('#loading').dialog('close');
}