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


$to_send = "example data";
$_POST[$id] = $to_send;
$result = array();
$submit->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "submit - The id is in the result array");

$test->expect( $result[$id] == $to_send, "submit - the value was correctly found");

$to_send2 = "more_example_data";
$_REQUEST[$id] = $to_send2;

$result2 = $submit->fromForm();

$test->expect( array_key_exists($id, $result2), "submit - From form without args - id in the result array");

$test->expect( $result2[$id] == $to_send2, "submit - From form without args value was correctly found");
