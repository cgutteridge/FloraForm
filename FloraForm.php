<?php


$template = new Template;

abstract class FloraForm_Component
{
	var $id;
	var $options;
	var $fields = array();
	var $default_options = array("template"=>"floraform/floraform_default.htm", "surround"=>"floraform/component_surround.htm");
	function __construct( $options=array() )
	{
		$this->options = array_merge($this->default_options, $options);
		if( array_key_exists( "id", $options ))
		{
			$this->id = $options["id"];
		}
		if(!empty($options["fields"]))
		{
			$this->processConfig($options["fields"]);
			#unset($options["fields"]);
			#unset($this->options["fields"]);
		}
	}

	function processConfig( &$config )
	{
		foreach ( $config as $field_def )
		{
			foreach($field_def as $field_type => $options)
			{
				$this->add($field_type, $options);
			}
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

	function fullId($suffix=null)
	{
		if( !isset($this->id) ) { return ""; }
		$id = "";
		if( !empty($this->options["id-prefix"]) ) { $id .= $this->options["id-prefix"]."_"; }
		$id .= $this->id;
		if( isset( $suffix ) ){ $id .= "_$suffix"; }
		return $id;
	}

	function fromForm( &$values, $form_data )
	{
		return "fromForm() should be subclassed";
	}

	function render( $defaults=array() )
	{
		global $f3, $template;
		$default = isset($defaults[$this->id]) ?  $default = $defaults[$this->id] : "";
		$f3->set('default', $default);
		$f3->set('defaults', $defaults);
		$f3->set('self', $this);

		return $template->render($this->options["surround"]);
	}

	function renderInput( $defaults=array() )
	{
		global $f3, $template;
		$default = isset($defaults[$this->id]) ?  $default = $defaults[$this->id] : "";
		$f3->set('default', $default);
		$f3->set('self', $this);
		return $template->render($this->options["template"]);
	}

	function renderTitle()
	{
		if( !array_key_exists( "title",$this->options ) ) { return ""; }
		return htmlentities( $this->options["title"] );
	}

	function htmlOption( $opt_key )
	{
		if( $this->hasOption( $opt_key."_html" ) ) { return $this->options[$opt_key."_html"]; }
		if( $this->hasOption( $opt_key ) ) { return htmlentities($this->options[$opt_key]); }
	}	

	function hasHtmlOption( $opt_key )
	{
		return array_key_exists( $opt_key."_html", $this->options ) || array_key_exists( $opt_key, $this->options );
	}	

	function option( $opt_key )
	{
		if( $this->hasOption( $opt_key ) ) { return $this->options[$opt_key]; }
	}
	
	function hasOption( $opt_key )
	{
		return array_key_exists( $opt_key, $this->options );
	}	

	function add( $type, $options=array() )
	{
		$options["heading"] = $this->option("heading") + 1;
		$options["resourcesURL"] = $this->option("resourcesURL");

		$field = $this->factory( $type, $options );
		$this->fields []= $field;

		return $field;
	}

	function classes()
	{
		return "ff_component";
	}
	
	function error( $msg )
	{
		print "<h1>FloraForm has encountered an error: ".htmlentities( $msg )."</h1>";
		exit;
	}

	function factory( $type, $options )
	{
		if( $type == "TEXT" ) { $field = new FloraForm_Field_Text( $options ); }
		elseif( $type == "TEXTAREA" ) { $field = new FloraForm_Field_Textarea( $options ); }
		elseif( $type == "HTML" ) { $field = new FloraForm_Field_HTML( $options ); }
		elseif( $type == "CHOICE" ) { $field = new FloraForm_Field_Choice( $options ); }
		elseif( $type == "FILE" ) { $field = new FloraForm_Field_File( $options ); }
		elseif( $type == "CONDITIONAL" ) { $field = new FloraForm_Field_Conditional( $options ); }
		elseif( $type == "SUBMIT" ) { $field = new FloraForm_Field_Submit( $options ); }
		elseif( $type == "HIDDEN" ) { $field = new FloraForm_Field_Hidden( $options ); }
		elseif( $type == "LIST" ) { $field = new FloraForm_Field_List( $options ); }
		elseif( $type == "COMBO" ) { $field = new FloraForm_Field_Combo( $options ); }
		elseif( $type == "INFO" ) { $field = new FloraForm_Info( $options ); }
		elseif( $type == "SECTION" ) { $field = new FloraForm_Section( $options ); }
		else { $this->error( "bad field type: $type <br />options: <pre>".print_r($options, true)."</pre>" ); return; }

		return $field;
	}


}

abstract class FloraForm_Field extends FloraForm_Component
{
	var $default_options = array("template"=>"floraform/floraform_default.htm", "surround"=>"floraform/field_surround.htm");
	function fromForm( &$values, $form_data )
	{
		global $_POST;
		if( $this->id == "" ) { return; }
		if(array_key_exists($this->fullID(), $form_data)){
			$values[$this->id] = $form_data[$this->fullID()];
		}
	}

	function classes()
	{
		return parent::classes()." ff_field";
	}
}

class FloraForm_Section extends FloraForm_Component
{
	var $default_options = array("template"=>"floraform/section.htm", "heading"=>2);

	function __construct( $options=array() )
	{
		parent::__construct( $options );
		$this->options["layout"] = "section"; 
	}

	function render( $defaults=array() )
	{
		global $f3, $template;

		$f3->set('self', $this);
		$f3->set('defaults', $defaults);

		return $template->render($this->options["template"]);
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

class FloraForm extends FloraForm_Section
{

	function render( $defaults=array() )
	{
		global $f3, $template;
		$f3->set('form_content', parent::render($defaults));
		$f3->set('self', $this);
		return $template->render('floraform/form.htm');
	}
	
}

class FloraForm_Field_Combo extends FloraForm_Component
{
	var $default_options = array("template"=>"floraform/combo.htm", "surround"=>"floraform/component_surround.htm", "heading"=>2);
	var $fields = array();

	function __construct( $options=array() )
	{
		parent::__construct( $options );
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

class FloraForm_Info extends FloraForm_Component
{
	
	var $default_options = array("surround"=>"floraform/component_surround.htm");
	function classes()
	{
		return parent::classes()." ff_info";
	}
	
	function renderInput($defaults=array())
	{
		return $this->htmlOption("content");
	}
		
}

class FloraForm_Field_Text extends FloraForm_Field
{
	var $default_options = array("template"=>"floraform/text.htm", "surround"=>"floraform/field_surround.htm");
	
	function classes()
	{
		return parent::classes()." ff_text";
	}
}

class FloraForm_Field_Conditional extends FloraForm_Field
{
	var $default_options = array("template"=>"floraform/conditional.htm", "surround"=>"floraform/field_surround.htm");
	
	function fromForm( &$values, $form_data )
	{
		$this->fields[0]->fromForm( $values, $form_data );
		$value = $values[$this->fields[0]->fullId()];
		foreach($this->options["conditions"] as $pattern_field)
		{
			$regex = $pattern_field[0];
			$field = $pattern_field[1];

			$regex = preg_replace('#/#','\/',$regex);
			if(preg_match("/$regex/i", $value))
			{
				$field->fromForm( $values, $form_data);
				break;
			}
		}
	}

	function conditionsJson()
	{
		$conditions = array();
		foreach($this->options["conditions"] as $condition)
		{
			$conditions[] = array($condition[0], $condition[1]->render());
		}
		return json_encode($conditions);
	}
	function classes()
	{
		return parent::classes()." ff_text";
	}
}

class FloraForm_Field_Textarea extends FloraForm_Field
{
	var $default_options = array("template"=>"floraform/textarea.htm", "surround"=>"floraform/field_surround.htm");
	
	function classes()
	{
		return parent::classes()." ff_textarea";
	}
}

class FloraForm_Field_File extends FloraForm_Field
{
	var $default_options = array("template"=>"floraform/file.htm", "surround"=>"floraform/field_surround.htm");
	
	function classes()
	{
		return parent::classes()." ff_textarea";
	}

	function fromForm( &$values, $form_data )
	{
		global $_FILES;
			
		if( $this->id == "" ) { return; }
		if ($_FILES[$this->fullId()]["error"] > 0){ return; }

		if(array_key_exists($this->fullID(), $_FILES)){
			$values[$this->id] = $_FILES[$this->fullID()];
		}
	}
}

class FloraForm_Field_HTML extends FloraForm_Field
{

	var $default_options = array("template"=>"floraform/htmlarea.htm", "surround"=>"floraform/component_surround.htm");
	
	function classes()
	{
		return parent::classes()." ff_html";
	}
}

#TODO MultiChoice field (different to choice

class FloraForm_Field_Choice extends FloraForm_Field
{
	var $default_options = array("template"=>"floraform/choice.htm", "surround"=>"floraform/field_surround.htm");
	
	function classes()
	{
		return parent::classes()." ff_select";
	}
}

class FloraForm_Field_List extends FloraForm_Field
{
	var $field;
	var $default_options = array("template"=>"floraform/list.htm", "list_template"=>"floraform/list_item.htm", "heading"=>2, "min-items"=>3, "extra-items"=>0, "surround"=>"floraform/component_surround.htm");

	function classes()
	{
		return parent::classes()." ff_list";
	}

	function add( $type, $options=array() )
	{
		$options["id-prefix"] = $this->fullId();
		$this->field = parent::add($type, $options);

		return $this->field;
	}
	
	function setListType( $type, $options=array() )
	{
		#this should probably be removed from the documentation
		return $this->add( $type, $options);
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
	
	function renderInputRow( $defaults, $i )
	{
		global $f3, $template;
                $default = isset($defaults[$this->id]) ?  $default = $defaults[$this->id] : "";
                $f3->set('i', $i);
                $f3->set('default', $default);
                $f3->set('defaults', $defaults);
                $f3->set('self', $this);
                return $template->render($this->options["list_template"]);

	}
}

class FloraForm_Field_Submit extends FloraForm_Field
{
	var $default_options = array( "template"=>"floraform/submit.htm", "layout"=>"block", "surround"=>"floraform/field_surround.htm" );
	
	function classes()
	{
		return parent::classes()." ff_submit";
	}
}

class FloraForm_Field_Hidden extends FloraForm_Field
{
	var $default_options =  array("template"=>"floraform/hidden.htm", "surround"=>"floraform/field_surround.htm");
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









