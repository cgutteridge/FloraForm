<?php

$id="test_text";
$value="foo bacon";
$text = new FloraForm_Field_Text(array("id"=>$id));

$rendered = $text->render(array($id=>$value));

$test->expect( strpos( $rendered, '<input ' ), "text - input tag is rendered");
$test->expect( strpos( $rendered, 'type="text"' ), "text - input type is text");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "text - id is rendered into the form");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "text - name is rendered into the form");
$test->expect( strpos( $rendered, 'value="'.$value.'"' ), "text - default value is rendered into input");

$_POST[$id] = $value;
$result = array();
$text->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "text - The id is in the result array");

$test->expect( $result[$id] == $value, "text - the value was correctly found");

$_REQUEST[$id] = $value;

$result2 = $text->fromForm();

$test->expect( array_key_exists($id, $result2), "text - From form without args - id in the result array");

$test->expect( $result2[$id] == $value, "text - From form without args value was correctly found");

$id2 = "test_second";

$text->setId($id2);

$rendered = $text->render();

$test->expect( strpos( $rendered, 'id="'.$id2.'"' ), "text - after setId() the id is rendered into the form");
$test->expect( strpos( $rendered, 'name="'.$id2.'"' ), "text - after setId() the name is rendered into the form");

