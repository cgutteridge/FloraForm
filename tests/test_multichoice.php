<?php

$id="test_multichoice";
$value1="foo bacon";
$value2="ham";
$value3="eggs";
$multichoice = new FloraForm_Field_Multichoice(array('id'=>$id,"mode"=>'multi-list',"choices"=>array( "1" => $value1,"2" => $value2,"3" => $value3)));

$rendered = $multichoice->renderInput();

$test->expect(strpos($rendered,'name="'.$id.'[]"'),'multichoice - name rendered correctly');
$test->expect(strpos($rendered,'id="'.$id.'"'),'multichoice - id rendered correctly');
$test->expect(strpos($rendered,'>'.$value1.'<'),'multichoice - choice 1 rendered');
$test->expect(strpos($rendered,'>'.$value2.'<'),'multichoice - choice 2 rendered');
$test->expect(strpos($rendered,'>'.$value3.'<'),'multichoice - choice 3 rendered');

$_POST[$id] = $value1;
$result = array();
$multichoice->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "multichoice - The id is in the result array");
$test->expect( $result[$id] == $value1, "multichoice - the selected value was correctly found");

$_REQUEST[$id] = $value1;

$result1 = $multichoice->fromForm();

$test->expect( array_key_exists($id, $result1), "multichoice - From form without args - id in the result array");

$test->expect( $result1[$id] == $value1, "multichoice - From form without args value was correctly found");

$id2 = "test_multichoicetwo";
$value4 = "choice_four";
$value5 = "choice_five";
$multichoice2 = new FloraForm_Field_Multichoice(
	array('id'=>$id2,
	"choices" =>
	array( 
		"1" => $value1,
		"2" => $value2,
		"3" => $value3,
		"4" => $value4,
		"5" => $value5
	)));
$rendered = $multichoice2->render();


$test->expect(strpos($rendered,'<input class=\'ff_input_checkbox\''),'multichoice - checkbox field tag rendered');

$choices1 = array($value1,$value2);
$_POST[$id2] = $choices1;
$result2 = array();
$multichoice2->fromForm($result2, $_POST);

$test->expect( array_key_exists($id2, $result2), "multichoice - The id is in the result array for a multichoice selection");
$test->expect( $result2[$id2] == $choices1, "multichoice - the selected value was correctly found for a multichoice selection");

$_REQUEST[$id2] = $choices1;

$result3 = $multichoice2->fromForm();

$test->expect( array_key_exists($id2, $result3), "multichoice - From form without args - id in the result array for a multichoice selection");

$test->expect( $result3[$id2] == $choices1, "multichoice - From form without args value was correctly found for a multichoice selection");

$wrong_values1 = array("choice_one","choice_seven");
$_POST[$id2] = $wrong_values1;
$result3 = array();
$multichoice2->fromForm($result3,$_POST);

$test->expect( $result2[$id2] != $wrong_values1, "multichoice - invalid value is not returned by POST");

$_REQUEST[$id2] = $wrong_values1;

$result4 = $multichoice2->fromForm();

$test->expect( $result4[$id2] != $wrong_values1 , "multichoice - invalid value is not returned by REQUEST");


