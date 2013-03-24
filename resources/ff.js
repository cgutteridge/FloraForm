
var ff = { "lists": {} };

function ff_init()
{
	ff_initWysiwyg();
}
function ff_initWysiwyg()
{
	tinyMCE.init({
		mode : 'textareas',
		theme : 'advanced',
		plugins : 'table,layer',
		theme_advanced_toolbar_location : 'top',
        	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,link,unlink,|,sub,sup,|,cleanup,removeformat,|,indent,outdent",
        	theme_advanced_buttons2 : "",
        	theme_advanced_buttons3 : "",
        	theme_advanced_buttons4 : "",
		editor_selector : 'ff_input_html',
		content_css : "/db/syllabus/FloraForm/resources/tinymce.css"  
	});
}


function ff_bindRemoveButton( list_id, n )
{
	$('#'+list_id+"_"+n+'_remove').click( function(){ff_removeRow(list_id,n); return false;} );
}
function ff_removeRow( list_id, n )
{
	row = $('#'+list_id+"_"+n+'_row' );
	row.effect( "blind", {}, 200, function() { row.remove(); ff_restyleList( list_id ); } ); 
}


function ff_bindAddButton( list_id )
{
	$('#'+list_id+'_add').click( function(){ff_addRow(list_id); return false;} );
}
function ff_addRow( list_id )
{
	// get next id and increment counter
	new_n = ff['lists'][list_id]["next_index"];
	new_id = list_id + "_" + new_n;
	ff['lists'][list_id]["next_index"]++;

	html = ff['lists'][list_id]["template"];
	html = html.replace( /ROW_ID/g, new_n );

	$("#"+list_id+"_list" ).append( $(html) );

	ff_restyleList( list_id );

	ff_bindRemoveButton( list_id, new_n );
}


function ff_restyleList( list_id )
{
	list = $( "#"+list_id+"_list li" );
	for( i=0; i<list.length; ++i )
	{
		row_id = list[i].id;
		item_id = row_id.slice(0,-4);
		$("#"+item_id+"_number").html( i+1 );
		row = $("#"+row_id );
		if( i==0 )
		{
			row.addClass( "ff_first" );
		}
		else
		{
			row.removeClass( "ff_first" );
		}
		if( i%2 )
		{
			row.addClass( "ff_even" );
			row.removeClass( "ff_odd" );
		}
		else
		{
			row.addClass( "ff_odd" );
			row.removeClass( "ff_even" );
		}
		
	}
	ff_initWysiwyg();
}
