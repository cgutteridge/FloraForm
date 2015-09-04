<?php

$id = "test_choice";
$value1 = "choice one";
$value2 = "choice_two";
$value3 = "choice_three";


$info = new FloraForm_Field_Choice(array("id"=>$id,
									"title"=>"test",
									"mode"=>"pull-down",
									"choices"=>
										array( 
											"1" => $value1,
											"2" => $value2,
											"3" => $value3
										)));

var_dump($rendered);