<?php

$id="test_hidden";
$value="foo";
$hidden = new FloraForm_Field_Hidden(array("id"=>$id));

$rendered = $hidden->render(array($id=>$value));

var_dump($rendered);

$test->expect( strpos( $rendered, '<input' ), "hidden - tag is rendered");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "hidden - id is rendered into the form");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "hidden - name is rendered into the form");

$test->expect( strpos( $rendered, 'value=\''.$value.'\'' ), "hidden - default value is rendered into input");
