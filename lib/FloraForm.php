<?php

$f3=require(__DIR__.'/base.php');

$f3->set('DEBUG',3);
$f3->set('UI',__DIR__.'/../resources/');
$f3->set('CACHE',FALSE);
$template = new Template;
class FloraForm extends FloraForm_Section
{

	function renderTitle()
	{
		return "";
	}

	function render( $defaults=array() )
	{
		$h="<form method='POST'";
		if(isset($this->options["action"])){
			$h.=" action='".htmlentities($this->options["action"])."'";
		}
		$h.=">";
		$h.=parent::render($defaults);
		$h.="</form>";
		return $h;
	}
	
	function fromForm( &$values, $form_data )
	{
		#$values = array();
		parent::fromForm( $values, $form_data );
	}
}

abstract class FloraForm_Component
{

	var $id;
	var $options;
	var $default_options = array();
	function __construct( $options=array() )
	{
		$this->options = array_merge($this->default_options, $options);
		if( array_key_exists( "id", $options ))
		{
			$this->id = $options["id"];
		}
	}

	function setId( $new_id )
	{
		$this->id = $new_id;
	}
	function setIdPrefix( $new_id_prefix )
	{
		$this->options["id-prefix"] = $new_id_prefix;
	}

	function fromForm( &$values, $form_data )
	{
		return "fromForm() should be subclassed";
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
	function renderComponent( $parts )
	{
		# other layout options may go here later
		if( $this->hasOption( "layout" ) && $this->options["layout"] == "section" ) { return $this->renderComponentSection($parts ); }
		if( $this->hasOption( "layout" ) && $this->options["layout"] == "block" ) { return $this->renderComponentBlock($parts ); }
		if( $this->hasOption( "layout" ) && $this->options["layout"] == "horizontal" ) { return $this->renderComponentHorizontal($parts ); }
		if( $this->hasOption( "layout" ) && $this->options["layout"] == "vertical" ) { return $this->renderComponentVertical($parts ); }
		if( $this->hasOption( "layout" ) && $this->options["layout"] == "vertical2up" ) { return $this->renderComponentVertical2up($parts ); }

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

	# nb cut and paste of vertical -- need to refactor?
	function renderComponentVertical2up( $parts )
	{
		$h = "";
		$h.= "<span ".$this->renderIDAttr("container")." class='".$this->classes()." ff_vertical2up'>";
		if( $parts["title"] != "" )
		{	
			$h.= "<span ".$this->renderIDAttr("title")." class='ff_title'>".$parts["title"].":</span>";
		}
		$h.= $parts["content"];
		$h.= "</span>";

		return $h;
	}
	
	function renderComponentVertical( $parts )
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
	
	function renderComponentHorizontal( $parts )
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
	
	function renderComponentBlock( $parts )
	{
		$h = "";
		$h.= "<div ".$this->renderIDAttr("container")." class='".$this->classes()." ff_block'>";
				
		$h.= $parts["content"];

		$h.= "</div>";

		return $h;
	}
	function renderComponentSection( $parts )
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
		if( !isset($this->id) ) { return "";  }
		return "id='".$this->fullId($suffix)."'";

	}
	function renderNameAttr($suffix=null)
	{
		if( !isset($this->id) ) { return "";  }
		return "name='".$this->fullId($suffix)."'";
	}
	
	
	function fullId($suffix=null)
	{
		if( !isset($this->id) ) { return ""; }
		$id = "";
		if( !empty($this->options["id-prefix"]) ) { $id .= $this->options["id-prefix"]."_"; }
		$id .= $this->id;
		if( isset( $suffix ) ){ $id .= "_$suffix"; }
		return $id;
	}

	function error( $msg )
	{
		print "<h1>FloraForm has encountered an error: ".htmlspecialchars( $msg )."</h1>";
		exit;
	}

	function factory( $type, $options )
	{
		if( $type == "TEXT" ) { $field = new FloraForm_Field_Text( $options ); }
		elseif( $type == "TEXTAREA" ) { $field = new FloraForm_Field_Textarea( $options ); }
		elseif( $type == "HTML" ) { $field = new FloraForm_Field_HTML( $options ); }
		elseif( $type == "CHOICE" ) { $field = new FloraForm_Field_Choice( $options ); }
		elseif( $type == "SUBMIT" ) { $field = new FloraForm_Field_Submit( $options ); }
		elseif( $type == "HIDDEN" ) { $field = new FloraForm_Field_Hidden( $options ); }
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
	
		return $this->renderComponent( $parts );
	}	
	
	function renderInput( $defaults=array() )
	{
		$default = "";
		if( !empty($defaults[$this->id]) ){ $default = $defaults[$this->id]; }
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

	function fromForm( &$values, $form_data )
	{
		$values[$this->id] = array();
		foreach( $this->fields as $field )
		{
			$field->fromForm( $values[$this->id], $form_data );
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
	
		return $this->renderComponent( $parts );
	}	

	function fromForm( &$values, $form_data )
	{
		foreach( $this->fields as $field )
		{
			$field->fromForm( $values, $form_data );
		}
	}

	function classes()
	{
		return parent::classes()." ff_section";
	}
}



abstract class FloraForm_Field extends FloraForm_Component
{
	var $default_options = array("template"=>"floraform_default.htm");
	function fromForm( &$values, $form_data )
	{
		global $_POST;
		if( $this->id == "" ) { return; }
		if(array_key_exists($this->fullID(), $form_data)){
			$values[$this->id] = $form_data[$this->fullID()];
		}
	}

	function render( $defaults=array() )
	{
		$parts = array();
		
		$parts["title"] = "<label for='".$this->id."' ".$this->renderIDAttr("label").">"
		       . $this->renderTitle()."</label>";
		$parts["content"] = $this->renderInput($defaults );

		return $this->renderComponent( $parts );
	}

	function renderInput( $defaults=array() )
	{
		global $f3, $template;
		$default = isset($defaults[$this->id]) ?  $default = $defaults[$this->id] : "";
		$f3->set('default', $default);
		$f3->set('self', $this);
		return "". $template->render($this->options["template"]);
	}

	function classes()
	{
		return parent::classes()." ff_field";
	}
}

class FloraForm_Field_Text extends FloraForm_Field
{
	var $default_options = array("template"=>"text.htm");
	
	function classes()
	{
		return parent::classes()." ff_text";
	}
}

class FloraForm_Field_Textarea extends FloraForm_Field
{
	var $default_options = array("template"=>"textarea.htm");
	
	function classes()
	{
		return parent::classes()." ff_textarea";
	}
}

class FloraForm_Field_HTML extends FloraForm_Field
{

	var $default_options = array("template"=>"htmlarea.htm");
	
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
		$default = "";
		if( !empty($defaults[$this->id]) ){ $default = $defaults[$this->id]; }
		$html = "";
		if( $this->hasOption( "prefix" ) ) { $html .= $this->options["prefix"]; }
		$html.= "<select ".$this->renderNameAttr()." ".$this->renderIDAttr()." class=''>";
		foreach( $this->option("choices") as $code=>$value )
		{
			$html .= "<option value='".htmlspecialchars( $code )."'";
			if( $default == $code ) { $html .= " selected='selected'"; }
			$html .= ">".htmlspecialchars( $value )."</option>";
		}
		$html.="</select>";
		if( $this->hasOption( "suffix" ) ) { $html .= $this->options["suffix"]; }
		return $html;
	}

	function renderInputRadio( $defaults )
	{
		$default = "";
		if( !empty($defaults[$this->id]) ){ $default = $defaults[$this->id]; }
		#$html = "<select name='".$this->id."' ".$this->renderIDAttr()." class=''>";
		$html = "";
		foreach( $this->option( "choices" ) as $code=>$value )
		{
			$class = "ff_radio_option";
			if( @$this->option( "lots-of-class" ) )
			{
				$class .= " ff_radio_option_".$code;
			}
				
			$html .= "<label class='$class'>";
			$html .= "<input class='ff_input_radio' value='".htmlspecialchars( $code )."'";
			$html .= " ".$this->renderNameAttr();
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

	function render($defaults=array())
	{
		$parts = array();
		$parts["title"] = $this->renderTitle();
		$parts["content"] = $this->htmlOption( "content" );
	
		return $this->renderComponent( $parts );
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
		if( !array_key_exists( "heading", $options ))
		{
			if( array_key_exists( "heading", $this->options ) && $this->options["heading"]>0 )
			{
				# Lower
				$options["heading"] = $this->options["heading"]; 
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
		$this->field = $this->factory( $type, $options );
		return $this->field;
	}

	function fromForm( &$values, $form_data )
	{
		$i = 0;
		$done = false;
		$values[$this->id] = array();
		while( !$done )
		{
			$done = true;
			foreach( $form_data as $key=>$value )
			{
				if( strpos( $key, $this->fullID()."_".$i ) === 0 )
				{
					$done = false;
					break; // done on this loop, try next increment of $i
				}
			}
			if( !$done )
			{
				$values[$this->id][$i] = array();
				$field = clone $this->field;
				$field->setId( $i );
				$field->fromForm( $values[$this->id], $form_data );
			}	
			$i++;
		}

		# remove empty lines
		$values[ $this->id ] = array_filter( $values[$this->id], "FloraForm_var_is_set" );
	}
	
	function render( $defaults=array() )
	{
		$parts = array();
		$parts["title"] = $this->renderTitle();
		$parts["content"] = $this->renderInput( $defaults );
	
		return $this->renderComponent( $parts );
	}
	function renderInput( $defaults=array() )
	{
		$default = "";
		if( !empty($defaults[$this->id]) ){ $default = $defaults[$this->id]; }
		$n = sizeof( $default ) + $this->option( "extra-items" );
		if( $n < $this->option( "min-items" ) ) { $n = $this->option( "min-items" ); }
		$html = "";	
		$i = "";
		$html.= "<ul ".$this->renderIDAttr($i."list").">";
		for( $i=0; $i<$n; ++$i )
		{
			$html.= $this->renderInputRow( $defaults, $i );
		}	
		$html.= "</ul>";

		# create template for new rows
		#TODO GET HELP FROM CHRIS
		#$template = $this->renderInputRow( $defaults, "{{ROW_ID}}" );
		$template = $this->renderInputRow( $defaults, "ROW_ID" );
		
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
		$default = "";
		if( !empty($defaults[$this->id]) ){ $default = $defaults[$this->id]; }
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

class FloraForm_Field_Submit extends FloraForm_Field
{
	var $default_options = array( "template"=>"submit.htm", "layout"=>"block" );
	
	function classes()
	{
		return parent::classes()." ff_submit";
	}
}

class FloraForm_Field_Hidden extends FloraForm_Field
{
	var $default_options =  array("template"=>"hidden.htm");
	function render( $defaults=array() )
	{
		return $this->renderInput( $defaults );
	}
	
	function classes()
	{
		return parent::classes()." ff_hidden";
	}
}

function FloraForm_var_is_set( $thing )
{
	if( !isset( $thing ) ) { return false; }

	if( is_array($thing) )
	{
		if( sizeof( $thing ) == 0 ) { return false; }
		foreach( $thing as $k=>$v )
		{
			if( FloraForm_var_is_set( $v ) ) { return true; }
		}
		return false;
	}

	return !empty( $thing );
}









