<?php

$id="test_hidden";
$value="foo";
$hidden = new FloraForm_Field_Hidden(array("id"=>$id));

$rendered = $hidden->render(array($id=>$value));
$test->expect( strpos( $rendered, '<input ' ), "hidden - tag is rendered");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "hidden - id is rendered into the form");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "hidden - name is rendered into the form");

$test->expect( strpos( $rendered, 'value=\''.$value.'\'' ), "hidden - default value is rendered into input");
$test->expect( strpos( $rendered, 'type=\'hidden\''),"hidden - type is rendered into input");
var_dump($rendered);


$_POST[$id] = $value;
$result = array();
$hidden->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "hidden - The id is in the result array");

$test->expect( $result[$id] == $value, "hidden - the value was correctly found");

$_REQUEST[$id] = $value;

$result2 = $hidden->fromForm();

$test->expect( array_key_exists($id, $result2), "hidden - From form without args - id in the result array");

$test->expect( $result2[$id] == $value, "hidden - From form without args value was correctly found");

$id2 = "test_second";

$hidden->setId($id2);

$rendered = $hidden->render();

$test->expect( strpos( $rendered, 'id="'.$id2.'"' ), "hidden - after setId() the id is rendered into the form");
$test->expect( strpos( $rendered, 'name="'.$id2.'"' ), "hidden - after setId() the name is rendered into the form");

