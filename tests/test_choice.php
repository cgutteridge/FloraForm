<?php

$id = "test_choice";
$value1 = "choice one";
$value2 = "choice_two";
$value3 = "choice_three";


$choice= new FloraForm_Field_Choice(array("id"=>$id,
									"title"=>"test",
									"mode"=>"pull-down",
									"choices"=>
										array( 
											"1" => $value1,
											"2" => $value2,
											"3" => $value3
										)));
$rendered = $choice->render();

$test->expect( strpos( $rendered, '<select ' ), "choice - select tag is rendered");
$test->expect( strpos( $rendered, '<option value="1" ' ), "choice - option tag for value one is rendered");
$test->expect( strpos( $rendered, '<option value="2" ' ), "choice - option tag for value two is rendered");
$test->expect( strpos( $rendered, '<option value="3" ' ), "choice - option tag for value three is rendered");
$test->expect( strpos( $rendered, '<label ' ), "choice - label tag is rendered");
$test->expect( strpos( $rendered, 'id="'.$id.'_title"' ), "choice - label tag is rendered");



$_POST[$id] = $value1;
$result = array();
$choice->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "choice - The id is in the result array");
$test->expect( $result[$id] == $value1, "choice - the selected value was correctly found");

$_REQUEST[$id] = $value1;

$result1 = $choice->fromForm();

$test->expect( array_key_exists($id, $result1), "choice - From form without args - id in the result array");

$test->expect( $result1[$id] == $value1, "choice - From form without args value was correctly found");

$id2 = "test_choice_two";
$value4 = "choice_four";
$value5 = "choice_five";
$choice2 = new FloraForm_Field_Choice(array("id" => $id2,
						"title" => "test2",
						"title" => "Example Title",
						"layout" => "vertical",
						"choices" =>
						array( 
							"1" => $value1,
							"2" => $value2,
							"3" => $value3,
							"4" => $value4,
							"5" => $value5
						)));
$rendered = $choice2->render();


$test->expect(strpos($rendered,'<input class=\'ff_input_radio\''),'choice - radio type is rendered');
$test->expect(strpos($rendered,'ff_vertical'),'choice - Vertical layout rendered');

$_POST[$id2] = $value5;
$result2 = array();
$choice2->fromForm($result2, $_POST);

$test->expect( array_key_exists($id2, $result2), "choice - The id is in the result array");
$test->expect( $result2[$id2] == $value5, "choice - the selected value was correctly found");

$_REQUEST[$id2] = $value5;

$result3 = $choice2->fromForm();

$test->expect( array_key_exists($id2, $result3), "choice - From form without args - id in the result array");

$test->expect( $result3[$id2] == $value5, "choice - From form without args value was correctly found");

$wrong_value1 = "choice_six";
$_POST[$id2] = $wrong_value1;
$result3 = array();
$choice2->fromForm($result3,$_POST);

$test->expect( $result2[$id2] != $wrong_value1, "choice - invalid value is not returned by POST");

$wrong_value2 = "choice_seven";
$_REQUEST[$id2] = $wrong_value2;

$result4 = $choice2->fromForm();

$test->expect( $result4[$id2] != $wrong_value2 , "choice - invalid value is not returned by REQUEST");

$too_many_choices = array("choice_one","choice_two");
$_POST[$id2.'[]'] = $too_many_choices;
$result5 = array();
$choice2->fromForm($result5,$_POST);
$test->expect(!array_key_exists($id.'[]',$result5) || $result5[$id2.'[]'] != $too_many_choices , "choice - invalid number of choices not returned by POST");




