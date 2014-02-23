<?php
$test = new Test;

$id="test_text";
$value="foo bacon";
$text = new FloraForm_Field_Text(array("id"=>$id));

$rendered = $text->render(array($id=>$value));

$test->expect( strpos( $rendered, '<input ' ), "input tag is rendered");
$test->expect( strpos( $rendered, 'type="text"' ), "input type is text");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "id is rendered into the form");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "name is rendered into the form");
$test->expect( strpos( $rendered, 'value="'.$value.'"' ), "default value is rendered into input");

$_POST[$id] = $value;
$result = array();
$text->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "The id is in the result array");

$test->expect( $result[$id] == $value, "the value was correctly found");

$id2 = "test_second";

$text->setId($id2);

$rendered = $text->render();

$test->expect( strpos( $rendered, 'id="'.$id2.'"' ), "after setId() the id is rendered into the form");
$test->expect( strpos( $rendered, 'name="'.$id2.'"' ), "after setId() the name is rendered into the form");

