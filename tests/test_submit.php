<?php 
$id="test_submit";
$value = 'Submit';
$submit= new FloraForm_Field_Submit(array("id"=>$id,"text"=>$value));

$rendered = $submit->render();

$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "submit - id is rendered into the form");
$test->expect( strpos( $rendered, 'id="'.$id.'_container"' ), "submit - id is rendered into the container form");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "submit - name is rendered into the form");
$test->expect( strpos( $rendered, 'ff_input_submit' ), "submit - class is rendered");
$test->expect( strpos( $rendered, 'type=\'submit\'' ), "submit - type is rendered");
$test->expect( strpos( $rendered, 'ff_block' ), "submit - rendered as a block");
$test->expect( strpos( $rendered,'value=\''.$value.'\''),"submit - value rendered to button");
var_dump($rendered);