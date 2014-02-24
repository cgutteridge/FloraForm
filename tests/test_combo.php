<?php

$id="test_combo";
$combo = new FloraForm_Field_Combo(array("id"=>$id));

$rendered = $combo->render();

$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "Empty Combo has correct Id");

$textid = "some_text";

$combo->add("TEXT", array("id"=>$textid));

$rendered = $combo->render();

$test->expect(strpos($rendered, 'name="'.$id.'_'.$textid.'"'), "Name of subtext field is correct");

$value = "cream cheese";
$defaults = array($id=>array($textid=>$value));
$rendered = $combo->render($defaults);

$test->expect(strpos($rendered, $value), "Default value rendered into field");

$_REQUEST["${id}_${textid}"] = $value;

$from_form = $combo->fromForm();

$test->expect(array_key_exists($id, $from_form), "Combo array key in from form");
$test->expect(array_key_exists($textid, $from_form[$id]), "sub text field array key found in from form");
$test->expect($from_form[$id][$textid] == $value, "correct value retrieved from form");

## add a second field

$textid2 = "text_test";
$value2 = "happy joy";

$combo->add("TEXT", array("id"=>$textid2));

$defaults2 = array($id=>array($textid=>$value, $textid2=>$value2));
$rendered = $combo->render($defaults2);

$test->expect(strpos($rendered, 'name="'.$id.'_'.$textid2.'"'), "Name of second subtext field is correct");
$test->expect(strpos($rendered, $value2), "Second default value rendered into field");

$_REQUEST["${id}_${textid2}"] = $value2;

$from_form = $combo->fromForm();

$test->expect(array_key_exists($textid2, $from_form[$id]), "second sub text field array key found in from form");
$test->expect($from_form[$id][$textid2] == $value2, "correct value for second field retrieved from form");
$test->expect($from_form[$id][$textid] == $value, "correct value for first field still retrieved from form");

## setid()

$newid = "double_combo";
$combo->setId($newid);

$rendered = $combo->render($defaults2);

$test->expect(strpos( $rendered, 'id="'.$newid.'"'), "After setId() Combo has correct Id");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$textid.'"'), "After setId() name of subtext field is correct");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$textid2.'"'), "After setId() name of second subtext field is correct");

$id2 = "triple_combo";
$textid3 = "footle_text";
$value3 = "rubber duck";
$combo2 = $combo->add("COMBO", array("id"=>$id2));
$combo2->add("TEXT", array("id"=>$textid3));
$defaults3 = array($id=>array($textid=>$value, $textid2=>$value2, $id2=>array($textid3=>$value3)));

$rendered = $combo->render($defaults3);

$test->expect(strpos( $rendered, 'id="'.$newid.'"'), "After second combo first Combo has correct Id");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$textid.'"'), "After second combo name of subtext field is correct");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$textid2.'"'), "After second combo name of second subtext field is correct");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$id2.'_'.$textid3.'"'), "After second combo name of second subtext field is correct");

$_REQUEST["${newid}_${textid}"] = $value;
$_REQUEST["${newid}_${textid2}"] = $value2;
$_REQUEST["${newid}_${id2}_${textid3}"] = $value3;


$from_form = $combo->fromForm();

$test->expect(array_key_exists($textid3, $from_form[$newid][$id2]), "sub sub text field array key found in from form");
$test->expect($from_form[$newid][$id2][$textid3] == $value3, "correct value for sub sub text field retrieved from form");
$test->expect($from_form[$newid][$textid2] == $value2, "correct value for second field still retrieved from form");
$test->expect($from_form[$newid][$textid] == $value, "correct value for first field still retrieved from form");

$newid2 = "plant_pot";
$combo->setId($newid2);

$rendered = $combo->render($defaults3);

$test->expect(strpos($rendered, 'name="'.$newid2.'_'.$id2.'_'.$textid3.'"'), "After first combo setId() sub sub text field is correct");
