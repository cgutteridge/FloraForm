<?php

$id="test_number";
$value=123;
$min =0;
$max=1000;
$number = new FloraForm_Field_Number(array("id"=>$id,"min"=>$min,"max"=>$max));

$rendered = $number->render(array($id=>$value));

$test->expect( strpos( $rendered, '<input ' ), "number - tag is rendered");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "number - id is rendered into the form");
$test->expect( strpos( $rendered, 'type="number"' ), "number - type is rendered into the form");
#$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "number - name is rendered into the form");


$_POST[$id] = $value;
$result = array();
$number->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "number - The id is in the result array");
$test->expect( $result[$id] == $value, "number - the value was correctly found");

$_REQUEST[$id] = $value;

$result2 = $number->fromForm();
var_dump($result2[$id] == $value);
$test->expect( array_key_exists($id, $result2), "number - From form without args - id in the result array");
$test->expect( $result2[$id] == $value, "number - From form without args value was correctly found");

$test->expect(strpos($rendered,'min="'.$min.'"' ),"number - Correct min");
$test->expect(strpos($rendered,'max="'.$max.'"' ),"number - Correct max");
/*
$value = 1001;
$_POST[$id] = $value;
$result = array();
$number->fromForm($result, $_POST);
*/

$value = 1001;
$rendered = $number->render(array($id=>$value));
$_POST[$id] = $value;
$result = array();
$number->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "number - The id is in the result array");
$test->expect( $result[$id] != $value, "number - the new value was ignored due to it being too big");

$value = -1;
$rendered = $number->render(array($id=>$value));
$_POST[$id] = $value;
$result = array();
$number->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "number - The id is in the result array");
$test->expect( $result[$id] != $value, "number - the new value was ignored due to it being too small");

$value = "abc";
$rendered = $number->render(array($id=>$value));
$_POST[$id] = $value;
$result = array();
$number->fromForm($result, $_POST);


$test->expect( array_key_exists($id, $result), "number - The id is in the result array");
$test->expect( $result[$id] != $value, "number - the new value was ignored due to it not being a number");


