<?php

$id="test_conditional";
$conditional = new FloraForm_Field_Conditional(array("id"=>$id));

$rendered = $conditional->render(array("conditions"=>array()));
var_dump($rendered);
