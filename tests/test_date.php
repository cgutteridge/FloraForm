<?php

$id = 'test_date';
$value = "15-01-01";


$date = new FloraForm_Field_Date(array("id"=>$id));
$rendered = $date->render(array($id=>$value));


$test->expect( strpos( $rendered, 'class="bootstrap-datepicker"' ), "date - boostrap class rendered correctly");
$test->expect( strpos( $rendered, '<input'), "date - input tag rendered");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "date - id is rendered");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "date - name is rendered correctly");
#$test->expect( strpos( $rendered, 'value="'.$value.'"' ), "date - date value rendered");
$test->expect( strpos( $rendered,'<script'),"date - script tag rendered");
$test->expect( strpos( $rendered,'$(\'#'.$id.'\').datepicker'),"date - id rendered in script code");
$test->expect( strpos( $rendered,'dateFormat: "yy-mm-dd"'),"date - date format is rendered");

$test_value1 = "2015-02-02";
$rendered = $date->render(array($id=>$test_value1));
$test->expect( strpos( $rendered, 'value="'.$test_value1.'"' ), "date - date value (1) rendered");

$test_value2 = "14-03-03";
$rendered = $date->render(array($id=>$test_value2));
$test->expect( strpos( $rendered, 'value="'.$test_value2.'"' ), "date - date value (2) rendered");

$wrong_value1 = "not a date";
$rendered = $date->render(array($id=>$wrong_value1));
$test->expect( strpos( $rendered, 'value=""' ), "date - incorrect value (1) not rendered");

$wrong_value2 = "9:00AM";
$rendered = $date->render(array($id=>$wrong_value2));
$test->expect( strpos( $rendered, 'value=""' ), "date - incorrect value (2) not rendered");

$_POST[$id] = $value;
$result = array();
$date->fromForm($result, $_POST);
$test->expect( array_key_exists($id, $result), "date - The id is in the result array");
$test->expect( $result[$id] == $value, "date - the selected value was correctly found");


$_REQUEST[$id] = $value;
$result2 = $date->fromForm();
$test->expect( array_key_exists($id, $result2), "date - From form without args - id in the result array");
$test->expect( $result2[$id] == $value, "date - From form without args value was correctly found");


$wrong_value1 = "not a date";
$_POST[$id] = $wrong_value1;
$result2 = array();
$date->fromForm($result2, $_POST);
$test->expect( $result2[$id] != $wrong_value1, "date - invalid value is not returned by POST");


$wrong_value2 = "also not a date";
$_REQUEST[$id] = $wrong_value2;
$result3 = $date->fromForm();
$test->expect( $result3[$id] != $wrong_value2, "date - invalid value is not returned by REQUEST");

