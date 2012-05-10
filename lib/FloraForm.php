<?php


class FloraForm extends FloraForm_Section
{

	function renderTitle( $context )
	{
		return "";
	}

	function render( $defaults=array(), $context=array() )
	{
		$h="<form method='POST'>";
		$h.=parent::render($defaults, $context );
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

	function render( $defaults=array(), $context=array() )
	{
		return "render() should be subclassed";
	}

	function renderTitle($context=array())
	{
		$title = $this->id;
		if( array_key_exists( "title",$this->options ) ) { $title = $this->options["title"]; }
		return htmlspecialchars( $title );
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
	function renderBlock( $parts, $context )
	{
		if( $this->options["layout"] == "section" )
		{
			$h.= "<div ".$this->renderIDAttr("container")." class='".$this->classes( $context )." ff_section'>";
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
		}
		else
		{	
			$h.= "<div ".$this->renderIDAttr("container")." class='".$this->classes( $context )."'>";
			$h.= "<div ".$this->renderIDAttr("title")." class='ff_title'>".$parts["title"]."</div>";
			
			$h.= $parts["content"];
			$h.= "</div>";
		}

		return $h;
	}

	function classes( $context = array() )
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
		elseif( $type == "SELECT" ) { $field = new FloraForm_Field_Select( $options ); }
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

	function render( $defaults=array(), $context=array() )
	{
		$parts = array();
		$parts["title"] = $this->renderTitle($context);
		$parts["content"] = $this->renderInput( $defaults, $context );
	
		return $this->renderBlock( $parts, $context );
	}	
	
	function renderInput( $defaults=array(), $context=array() )
	{
		$default = $defaults[$this->id];
		$html = array();
		foreach( $this->fields as $field )
		{
			$html []= $field->render( $default );
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

	function classes( $context = array() )
	{
		return parent::classes( $context)." ff_combo";
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

		$field = $this->factory( $type, $options );
		$this->fields []= $field;

		return $field;
	}

	function render( $defaults=array(), $context=array() )
	{
		$parts = array();
		$parts["title"] = $this->renderTitle($context);
		$html = array();
		foreach( $this->fields as $field )
		{
			$html []= $field->render( $defaults );
		}
		$parts["content"] = join( "", $html );
	
		return $this->renderBlock( $parts, $context );
	}	

	function fromForm( &$values )
	{
		foreach( $this->fields as $field )
		{
			$field->fromForm( $values );
		}
	}

	function classes( $context = array() )
	{
		return parent::classes( $context)." ff_section";
	}
}



abstract class FloraForm_Field extends FloraForm_Component
{



	function render( $defaults=array(), $context=array() )
	{
		$parts = array();
		
		$parts["title"] = "<label for='".$this->id."' ".$this->renderIDAttr("label").">"
		       . $this->renderTitle($context)."</label>";
		$parts["content"] = $this->renderInput($defaults, $context);

		return $this->renderBlock( $parts, $context );
	}

	function renderInput( $defaults=array(), $context=array() )
	{
		return "<div>renderInput() must be subclassed!</div>";
	}

	function classes( $context = array() )
	{
		return parent::classes( $context)." ff_field";
	}
}

class FloraForm_Field_Text extends FloraForm_Field
{

	function renderInput( $defaults=array(), $context=array() )
	{
		$default = $defaults[$this->id];
		$html = "<input name='".$this->id."' ".$this->renderIDAttr()." class='' value='".htmlspecialchars($default)."' />";
		return $html;
	}
	
	function classes( $context = array() )
	{
		return parent::classes( $context)." ff_text";
	}
}

class FloraForm_Field_HTML extends FloraForm_Field
{

	function renderInput( $defaults=array(), $context=array() )
	{
		$default = $defaults[$this->id];
		$html = "<textarea name='".$this->id."' ".$this->renderIDAttr()." class='mceEditor'>".htmlspecialchars($default)."</textarea>";

		return $html;
	}
	
	function classes( $context = array() )
	{
		return parent::classes( $context)." ff_html";
	}
}

class FloraForm_Field_Select extends FloraForm_Field
{

	#TODO multiple choices allowed
	#TODO alternate views (radio, check)
	function renderInput( $defaults=array(), $context=array() )
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
	
	function classes( $context = array() )
	{
		return parent::classes( $context)." ff_select";
	}
}

class FloraForm_Info extends FloraForm_Component
{
	
	function classes( $context = array() )
	{
		return parent::classes( $context)." ff_info";
	}

	function render( $context = array() )
	{
		$parts = array();
		$parts["title"] = $this->renderTitle($context);
		$parts["content"] = $this->htmlOption( "content" );
	
		return $this->renderBlock( $parts, $context );
	}
		
}

class FloraForm_Field_List extends FloraForm_Field
{
	var $field;

	function __construct( $options=array() )
	{
		parent::__construct( $options );
		if( !$this->hasOption( "min-items" ) ) { $this->options[ "min-items" ] = 3; }
		if( !$this->hasOption( "extra-items" ) ) { $this->options[ "extra-items" ] = 1; }
	}

	function classes( $context = array() )
	{
		return parent::classes( $context)." ff_list";
	}

	function setListType( $type, $options=array() )
	{
		$options["id-prefix"] = $this->fullId();
		$this->field = $this->factory( $type, $options );
		return $this->field;
	}

	function render( $defaults=array(), $context=array() )
	{
		$parts = array();
		$parts["title"] = $this->renderTitle($context);
		$parts["content"] = $this->renderInput( $defaults, $context );
	
		return $this->renderBlock( $parts, $context );
	}
	function renderInput( $defaults=array(), $context=array() )
	{
		$default = $defaults[$this->id];
		$n = sizeof( $default ) + $this->option( "extra-items" );
		if( $n < $this->option( "min-items" ) ) { $n = $this->option( "min-items" ); }
		$html = "";	
		for( $i=0; $i<$n; ++$i )
		{
			$field = clone $this->field;
			$field->setId( $i );
			$html .= "<div class='ff_item ".($i%2?"ff_even":"ff_odd")." ".($i?"":"ff_first")."'>";
			$html .= "<div class='ff_item_number'>".($i+1)."</div>";
			$html .= "<div class='ff_item_remove'><a href='#'>remove</a></div>";
			$html .= "<div class='ff_item_value ".$field->classes( $context )."'>";
			$html .= $field->renderInput( $default , $context );
			$html .= "</div>";
			$html .= "</div>";
		}	
		
		$html.="<script>\n";
		$html.="ff['add_html']['".$this->fullId()."'] = ".json_encode( "hello world" ).";\n";
		$html.="ff_bindAddButton( '".$this->fullId()."' );\n";
		$html.="</script>\n";
		$html.= "<div>add</div>";
		return $html;
	}
	
}
	





