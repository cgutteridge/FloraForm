<?php


class FloraForm extends FloraForm_Section
{

	function renderTitle()
	{
		return "";
	}

	function render( $defaults=array() )
	{
		$h="<form method='POST'>";
		$h.=parent::render($defaults);
		$h.="</form>";
		return $h;
	}


}

abstract class FloraForm_Component
{
	function __construct( $options=array() )
	{
		$this->options = $options;
		if( array_key_exists( "id", $options ))
		{
			$this->id = $options["id"];
		}
	}

	var $id;
	var $options;

	function setId( $new_id )
	{
		$this->id = $new_id;
	}
	function setIdPrefix( $new_id_prefix )
	{
		$this->options["id-prefix"] = $new_id_prefix;
	}

	function fromForm( &$values )
	{
	}

	function render( $defaults=array() )
	{
		return "render() should be subclassed";
	}

	function renderTitle()
	{
		if( !array_key_exists( "title",$this->options ) ) { return ""; }
		return htmlspecialchars( $this->options["title"] );
	}

	function htmlOption( $opt_key )
	{
		if( $this->hasOption( $opt_key."_html" ) ) { return $this->options[$opt_key."_html"]; }
		if( $this->hasOption( $opt_key ) ) { return htmlspecialchars($this->options[$opt_key]); }
	}	
	function option( $opt_key )
	{
		if( $this->hasOption( $opt_key ) ) { return $this->options[$opt_key]; }
	}	
	function hasOption( $opt_key )
	{
		return array_key_exists( $opt_key, $this->options );
	}	
	function renderBlock( $parts )
	{
		# other layout options may go here later
		if( $this->options["layout"] == "section" ) { return $this->renderBlockSection($parts ); }
		if( $this->options["layout"] == "horizontal" ) { return $this->renderBlockHorizontal($parts ); }
		if( $this->options["layout"] == "vertical" ) { return $this->renderBlockVertical($parts ); }

		$h = "";
		$h.= "<span ".$this->renderIDAttr("container")." class='".$this->classes()."'>";
		if( $parts["title"] != "" )
		{	
			$h.= "<span ".$this->renderIDAttr("title")." class='ff_title'>".$parts["title"].":</span>";
		}
		$h.= $parts["content"];
		$h.= "</span>";

		return $h;
	}
	function renderBlockVertical( $parts )
	{
		$h = "";
		$h.= "<span ".$this->renderIDAttr("container")." class='".$this->classes()." ff_vertical'>";
		if( $parts["title"] != "" )
		{	
			$h.= "<span ".$this->renderIDAttr("title")." class='ff_title'>".$parts["title"].":</span>";
		}
		$h.= $parts["content"];
		$h.= "</span>";

		return $h;
	}
	
	function renderBlockHorizontal( $parts )
	{
		$h = "";
		$h.= "<span ".$this->renderIDAttr("container")." class='".$this->classes()." ff_horizontal'>";
		if( $parts["title"] != "" )
		{	
			$h.= "<span ".$this->renderIDAttr("title")." class='ff_title'>".$parts["title"].":</span>";
		}
		$h.= $parts["content"];
		$h.= "</span>";

		return $h;
	}
	
	function renderBlockSection( $parts )
	{
		$h = "";
		$h.= "<div ".$this->renderIDAttr("container")." class='".$this->classes()." ff_section'>";
		if( array_key_exists( "title", $parts ) && $parts["title"] != "" )
		{
			$h.= "<h".$this->options["heading"]." class='ff_title'>";
			$h.= $parts["title"];
			$h.= "</h".$this->options["heading"].">";
		}

		if( $this->hasOption( "description" ) )
		{
			$h.= "<div class='ff_description' ".$this->renderIDAttr("description").">";
			$h.= $this->option( "description" );
			$h.= "</div>";
		}
				
		$h.= $parts["content"];

		$h.= "</div>";

		return $h;
	}

	function classes()
	{
		return "ff_component";
	}

	function renderIDAttr($suffix=null)
	{
		if( !$this->id ) { return "";  }
		$html = "id='".$this->fullId();
		if( $suffix ) { $html.="_".$suffix; }
		$html.= "'";

		return $html;
	}
	
	function fullId()
	{
		if( !$this->id ) { return ""; }
		if( $this->options["id-prefix"] ) { return $this->options["id-prefix"]."_".$this->id; }
		return $this->id;
	}

	function error( $msg )
	{
		print "<h1>FloraForm has encountered an error: ".htmlspecialchars( $msg )."</h1>";
		exit;
	}

	function factory( $type, $options )
	{
		if( $type == "TEXT" ) { $field = new FloraForm_Field_Text( $options ); }
		elseif( $type == "HTML" ) { $field = new FloraForm_Field_HTML( $options ); }
		elseif( $type == "CHOICE" ) { $field = new FloraForm_Field_Choice( $options ); }
		elseif( $type == "LIST" ) { $field = new FloraForm_Field_List( $options ); }
		elseif( $type == "COMBO" ) { $field = new FloraForm_Field_Combo( $options ); }
		elseif( $type == "INFO" ) { $field = new FloraForm_Info( $options ); }
		elseif( $type == "SECTION" ) { $field = new FloraForm_Section( $options ); }
		else { $this->error( "bad field type: $type" ); return; }

		return $field;
	}


}

class FloraForm_Field_Combo extends FloraForm_Component
{
	var $fields = array();

	function __construct( $options=array() )
	{
		parent::__construct( $options );
	}

	function add( $type, $options=array() )
	{
		$options["id-prefix"] = $this->fullId();
		if( !array_key_exists( "resourcesURL", $options ) )
		{
			$options["resourcesURL"] = $this->options["resourcesURL"];
		}
		$field = $this->factory( $type, $options );
		$this->fields []= $field;

		return $field;
	}

	function setIdPrefix( $new_id_prefix )
	{
		$this->options["id-prefix"] = $new_id_prefix;
		foreach( $this->fields as $field )
		{
			$field->setIdPrefix( $this->fullId() );
		}
	}
	function setId( $new_id )
	{
		parent::setId( $new_id );
		foreach( $this->fields as $field )
		{
			$field->setIdPrefix( $this->fullId() );
		}
	}

	function render( $defaults=array() )
	{
		$parts = array();
		$parts["title"] = $this->renderTitle();
		$parts["content"] = $this->renderInput( $defaults );
	
		return $this->renderBlock( $parts );
	}	
	
	function renderInput( $defaults=array() )
	{
		$default = $defaults[$this->id];
		$html = array();
		if( $this->hasOption( "layout" ) )
		{
			$html [] = "<span class='ff_combo_".$this->options["layout"]."'>";
		}
		foreach( $this->fields as $field )
		{
			$html []= $field->render( $default );
		}
		if( $this->hasOption( "layout" ) )
		{
			$html [] = "</span>";
		}
		return join( "", $html );
	}

	function fromForm( &$values )
	{
		foreach( $this->fields as $field )
		{
			$field->fromForm( $values );
		}
	}

	function classes()
	{
		return parent::classes()." ff_combo";
	}
}

class FloraForm_Section extends FloraForm_Component
{
	var $fields = array();

	function __construct( $options=array() )
	{
		parent::__construct( $options );
		$this->options["layout"] = "section"; 
	}

	function add( $type, $options=array() )
	{
		if( !array_key_exists( "heading", $options ))
		{
			if( array_key_exists( "heading", $this->options ) && $this->options["heading"]>0 )
			{
				$options["heading"] = $this->options["heading"] + 1;
			}
			else
			{
				$options["heading"] = 2;
			}
		}
		if( !array_key_exists( "resourcesURL", $options ) )
		{
			$options["resourcesURL"] = $this->options["resourcesURL"];
		}

		$field = $this->factory( $type, $options );
		$this->fields []= $field;

		return $field;
	}

	function render( $defaults=array() )
	{
		$parts = array();
		$parts["title"] = $this->renderTitle();
		$html = array();
		foreach( $this->fields as $field )
		{
			$html []= $field->render( $defaults );
		}
		$parts["content"] = join( "", $html );
	
		return $this->renderBlock( $parts );
	}	

	function fromForm( &$values )
	{
		foreach( $this->fields as $field )
		{
			$field->fromForm( $values );
		}
	}

	function classes()
	{
		return parent::classes()." ff_section";
	}
}



abstract class FloraForm_Field extends FloraForm_Component
{



	function render( $defaults=array() )
	{
		$parts = array();
		
		$parts["title"] = "<label for='".$this->id."' ".$this->renderIDAttr("label").">"
		       . $this->renderTitle()."</label>";
		$parts["content"] = $this->renderInput($defaults );

		return $this->renderBlock( $parts );
	}

	function renderInput( $defaults=array() )
	{
		return "<div>renderInput() must be subclassed!</div>";
	}

	function classes()
	{
		return parent::classes()." ff_field";
	}
}

class FloraForm_Field_Text extends FloraForm_Field
{

	function renderInput( $defaults=array() )
	{
		$default = $defaults[$this->id];
		$html = "<input name='".$this->id."' ".$this->renderIDAttr()." class='ff_input_text' value='".htmlspecialchars($default)."' />";
		return $html;
	}
	
	function classes()
	{
		return parent::classes()." ff_text";
	}
}

class FloraForm_Field_HTML extends FloraForm_Field
{

	function renderInput( $defaults=array() )
	{
		$default = $defaults[$this->id];
		$html = "<textarea name='".$this->id."' ".$this->renderIDAttr()." class='ff_input_html'>".htmlspecialchars($default)."</textarea>";

		return $html;
	}
	
	function classes()
	{
		return parent::classes()." ff_html";
	}
}

#TODO MultiChoice field (different to choice

class FloraForm_Field_Choice extends FloraForm_Field
{

	function renderInput( $defaults=array() )
	{
		if( $this->options['mode'] == 'pull-down' ) { return $this->renderInputPulldown( $defaults ); }
		if( $this->options['mode'] == 'radio' ) { return $this->renderInputRadio( $defaults ); }
		return $this->renderInputPulldown( $defaults ); 
	}

	function renderInputPulldown( $defaults )
	{
		$default = $defaults[$this->id];
		$html = "<select name='".$this->id."' ".$this->renderIDAttr()." class=''>";
		foreach( $this->option( "choices" ) as $code=>$value )
		{
			$html .= "<option value='".htmlspecialchars( $code )."'";
			if( $default == $code ) { $html .= " selected='selected'"; }
			$html .= ">".htmlspecialchars( $value )."</option>";
		}
		$html.="</select>";
		return $html;
	}

	function renderInputRadio( $defaults )
	{
		$default = $defaults[$this->id];
		#$html = "<select name='".$this->id."' ".$this->renderIDAttr()." class=''>";
		foreach( $this->option( "choices" ) as $code=>$value )
		{
			$html .= "<label class='ff_radio_option'>";
			$html .= "<input class='ff_input_radio' value='".htmlspecialchars( $code )."'";
			$html .= " name='".$this->id."'";
			$html .= " type='radio' ";
			if( $default == $code ) { $html .= " checked='checked'"; }
			$html .= " />";
			$html .= $value;
			$html .= "</label>";
		}
		#$html.="</select>";
		return $html;
	}
	
	function classes()
	{
		return parent::classes()." ff_select";
	}
}

class FloraForm_Info extends FloraForm_Component
{
	
	function classes()
	{
		return parent::classes()." ff_info";
	}

	function render()
	{
		$parts = array();
		$parts["title"] = $this->renderTitle();
		$parts["content"] = $this->htmlOption( "content" );
	
		return $this->renderBlock( $parts );
	}
		
}

class FloraForm_Field_List extends FloraForm_Field
{
	var $field;

	function __construct( $options=array() )
	{
		parent::__construct( $options );
		if( !$this->hasOption( "min-items" ) ) { $this->options[ "min-items" ] = 3; }
		if( !$this->hasOption( "extra-items" ) ) { $this->options[ "extra-items" ] = 0; }
	}

	function classes()
	{
		return parent::classes()." ff_list";
	}

	function setListType( $type, $options=array() )
	{
		$options["id-prefix"] = $this->fullId();
		if( !array_key_exists( "resourcesURL", $options ) )
		{
			$options["resourcesURL"] = $this->options["resourcesURL"];
		}
		$this->field = $this->factory( $type, $options );
		return $this->field;
	}

	function render( $defaults=array() )
	{
		$parts = array();
		$parts["title"] = $this->renderTitle();
		$parts["content"] = $this->renderInput( $defaults );
	
		return $this->renderBlock( $parts );
	}
	function renderInput( $defaults=array() )
	{
		$default = $defaults[$this->id];
		$n = sizeof( $default ) + $this->option( "extra-items" );
		if( $n < $this->option( "min-items" ) ) { $n = $this->option( "min-items" ); }
		$html = "";	
		$html.= "<ul ".$this->renderIDAttr($i."list").">";
		for( $i=0; $i<$n; ++$i )
		{
			$html.= $this->renderInputRow( $defaults, $i );
		}	
		$html.= "</ul>";

		# create template for new rows
		$template = $this->renderInputRow( $defaults, "{{ROW_ID}}" );
		
		$html.= "<span class='ff_item_add' ".$this->renderIDAttr("add")."><img src='".$this->options["resourcesURL"]."/images/add.png' /> More</span>";
		$html.="<script>\n";
		$html.="ff_bindAddButton( '".$this->fullId()."' );\n";
		for( $i=0; $i<$n; ++$i )
		{
			$html.="ff_bindRemoveButton( '".$this->fullId()."',$i );\n";
		}
		$html.="ff['lists']['".$this->fullId()."'] = ".json_encode( array(
			"template" => $template,
			"next_index" => $n )).";\n";
		$html.="</script>\n";
		return $html;
	}

	function renderInputRow( $defaults, $i )
	{
		$default = $defaults[$this->id];
		$field = clone $this->field;
		$field->setId( $i );
		$html = "";
		$html .= "<li ".$this->renderIDAttr($i."_row")." class='ff_item ".($i%2?"ff_even":"ff_odd")." ".($i?"":"ff_first")."'>";
		$html .= "<span class='ff_item_number' ".$this->renderIDAttr($i."_number").">".($i+1)."</span>";
		$html .= "<span class='ff_item_remove'><img ".$this->renderIDAttr($i."_remove")." src='".$this->options["resourcesURL"]."/images/delete.png' /></span>";
		$html .= "<span class='ff_item_value ".$field->classes( )."'>";
		$html .= $field->renderInput( $default );
		$html .= "</span>";
		$html .= "</li>";
		return $html;
	}
}
	





