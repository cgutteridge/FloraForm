<?php 
$id="test_time";
$value = "9.00 AM";
$time = new FloraForm_Field_Time(array("id"=>$id));

$rendered = $time->renderInput(array($id=>$value));

var_dump($rendered);

$test->expect( strpos( $rendered, "<div " ), "time - container tag is rendered");
$test->expect( strpos( $rendered, 'class="bootstrap-timepicker"' ), "time boostrap class rendered correctly");
$test->expect( strpos( $rendered, '<input'), "time - input tag rendered");
$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "time - id is rendered");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "time - name is rendered correctly");
$test->expect( strpos( $rendered, 'value="'.$value.'"' ), "time - time value rendered");
$test->expect( strpos( $rendered,'<script'),"time - script tag rendered");
$test->expect( strpos( $rendered,'$(\'#'.$id.'\').timepicker'),"time - id rendered in script code");