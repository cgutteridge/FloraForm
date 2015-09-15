<?php
$id="test_time";
$value = "9:30AM";
$size = 10;
$time = new FloraForm_Field_Time(array("id"=>$id));

$rendered = $time->renderInput(array($id=>$value,"size"=>$size));

#This test will likely need to be extended when the time component is extended with configurable javascript

#$test->expect( strpos( $rendered, "<div " ), "time - container tag is rendered");
$test->expect( strpos( $rendered, 'class="bootstrap-timepicker"' ), "time - boostrap class rendered correctly");
$test->expect( strpos( $rendered, '<input'), "time - input tag rendered");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "time - id is rendered");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "time - name is rendered correctly");
$test->expect( strpos( $rendered, 'value="'.$value.'"' ), "time - time value rendered");
$test->expect( strpos( $rendered,'<script'),"time - script tag rendered");
$test->expect( strpos( $rendered,'$(\'#'.$id.'\').timepicker'),"time - id rendered in script code");


$_POST[$id] = $value;
$result = array();
$time->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "time - The id is in the result array");
$test->expect( $result[$id] == $value, "time - the selected value was correctly found");



$_REQUEST[$id] = $value;

$result2 = $time->fromForm();

$test->expect( array_key_exists($id, $result2), "time - From form without args - id in the result array");

$test->expect( $result2[$id] == $value, "time - From form without args value was correctly found");

$wrong_value = "haven't the time";
$_POST[$id] = $wrong_value;
$result3 = array();
$time->fromForm($result3, $_POST);

$test->expect( $result3[$id] != $wrong_value, "time - invalid value is not returned by POST");

$wrong_value2 = "still haven't the time";
$_REQUEST[$id] = $wrong_value2;
$result4 = $time->fromForm();
$test->expect( $result4[$id] != $wrong_value2, "time - invalid value is not returned by REQUEST");
