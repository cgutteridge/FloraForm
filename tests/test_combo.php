<?php

$id="test_combo";
$combo = new FloraForm_Field_Combo(array("id"=>$id));

$rendered = $combo->render();

$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "combo - Empty Combo has correct Id");

$textid = "some_text";

$combo->add("TEXT", array("id"=>$textid));

$rendered = $combo->render();

$test->expect(strpos($rendered, 'name="'.$id.'_'.$textid.'"'), "combo - Name of subtext field is correct");

$value = "cream cheese";
$defaults = array($id=>array($textid=>$value));
$rendered = $combo->render($defaults);

$test->expect(strpos($rendered, $value), "combo - Default value rendered into field");

$_REQUEST["${id}_${textid}"] = $value;

$from_form = $combo->fromForm();

$test->expect(array_key_exists($id, $from_form), "combo - Combo array key in from form");
$test->expect(array_key_exists($textid, $from_form[$id]), "combo - sub text field array key found in from form");
$test->expect($from_form[$id][$textid] == $value, "combo - correct value retrieved from form");

## add a second field

$textid2 = "text_test";
$value2 = "happy joy";

$combo->add("TEXT", array("id"=>$textid2));

$defaults2 = array($id=>array($textid=>$value, $textid2=>$value2));
$rendered = $combo->render($defaults2);

$test->expect(strpos($rendered, 'name="'.$id.'_'.$textid2.'"'), "combo - Name of second subtext field is correct");
$test->expect(strpos($rendered, $value2), "combo - Second default value rendered into field");

$_REQUEST["${id}_${textid2}"] = $value2;

$from_form = $combo->fromForm();

$test->expect(array_key_exists($textid2, $from_form[$id]), "combo - second sub text field array key found in from form");
$test->expect($from_form[$id][$textid2] == $value2, "combo - correct value for second field retrieved from form");
$test->expect($from_form[$id][$textid] == $value, "combo - correct value for first field still retrieved from form");

## setid()

$newid = "double_combo";
$combo->setId($newid);

$rendered = $combo->render($defaults2);

$test->expect(strpos( $rendered, 'id="'.$newid.'"'), "combo - After setId() Combo has correct Id");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$textid.'"'), "combo - After setId() name of subtext field is correct");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$textid2.'"'), "combo - After setId() name of second subtext field is correct");

$id2 = "triple_combo";
$textid3 = "footle_text";
$value3 = "rubber duck";
$combo2 = $combo->add("COMBO", array("id"=>$id2));
$combo2->add("TEXT", array("id"=>$textid3));
$defaults3 = array($id=>array($textid=>$value, $textid2=>$value2, $id2=>array($textid3=>$value3)));

$rendered = $combo->render($defaults3);

$test->expect(strpos( $rendered, 'id="'.$newid.'"'), "combo - After second combo first Combo has correct Id");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$textid.'"'), "combo - After second combo name of subtext field is correct");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$textid2.'"'), "combo - After second combo name of second subtext field is correct");
$test->expect(strpos($rendered, 'name="'.$newid.'_'.$id2.'_'.$textid3.'"'), "combo - After second combo name of second subtext field is correct");

$_REQUEST["${newid}_${textid}"] = $value;
$_REQUEST["${newid}_${textid2}"] = $value2;
$_REQUEST["${newid}_${id2}_${textid3}"] = $value3;


$from_form = $combo->fromForm();

$test->expect(array_key_exists($textid3, $from_form[$newid][$id2]), "combo - sub sub text field array key found in from form");
$test->expect($from_form[$newid][$id2][$textid3] == $value3, "combo - correct value for sub sub text field retrieved from form");
$test->expect($from_form[$newid][$textid2] == $value2, "combo - correct value for second field still retrieved from form");
$test->expect($from_form[$newid][$textid] == $value, "combo - correct value for first field still retrieved from form");

$newid2 = "plant_pot";
$combo->setId($newid2);

$rendered = $combo->render($defaults3);

$test->expect(strpos($rendered, 'name="'.$newid2.'_'.$id2.'_'.$textid3.'"'), "combo - After first combo setId() sub sub text field is correct");
