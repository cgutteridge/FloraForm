<?php

$id = 'test_date';
$value = "15-01-01";


$date = new FloraForm_Field_Date(array("id"=>$id));
$rendered = $date->render(array($id=>$value));


$test->expect( strpos( $rendered, 'class="bootstrap-datepicker"' ), "date - boostrap class rendered correctly");
$test->expect( strpos( $rendered, '<input'), "date - input tag rendered");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "date - id is rendered");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "date - name is rendered correctly");
$test->expect( strpos( $rendered, 'value="'.$value.'"' ), "date - date value rendered");
$test->expect( strpos( $rendered,'<script'),"date - script tag rendered");
$test->expect( strpos( $rendered,'$(\'#'.$id.'\').datepicker'),"date - id rendered in script code");
$test->expect( strpos( $rendered,'dateFormat: "yy-mm-dd"'),"date - date format is rendered");

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

