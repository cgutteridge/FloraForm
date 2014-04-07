<?php

$id="test_list";
$text_id = "text_test";
$list = new FloraForm_Field_List(array("id"=>$id));

$rendered = $list->render();

$test->expect( strpos( $rendered, '<ul id="'.$id.'_list"' ), "Empty list has correct Id");

$list->add("TEXT", array("id"=>$text_id));

$rendered = $list->render();
$test->expect( strpos( $rendered, 'id="'.$id.'_1_'.$text_id.'"' ), "List containing text has correct text Id");
$test->expect( strpos( $rendered, 'name="'.$id.'_1_'.$text_id.'"' ), "List containing text has correct text name");

$min_items = $list->option("min-items");

$test->expect( $min_items==3, "The minimum number of items is 3");
$test->expect( strpos( $rendered, 'name="'.$id.'_3_'.$text_id.'"' ), "There are 3 fields rendered");
$test->expect( !strpos( $rendered, 'name="'.$id.'_4_'.$text_id.'"' ), "There are not 4 fields rendered");

$value1  = "FOOO BAR BAZ";
$_REQUEST["${id}_1_${text_id}"] = $value1;

$result = $list->fromForm();

$test->expect(array_key_exists($id, $result), "The list comes back fromForm()");
$test->expect(count($result)==1, "The list has a result in it");
$test->expect($result[$id][1][$text_id]==$value1, "correct value returned fromForm()");

$result = $list->fromForm();

$rendered = $list->render($result);
$test->expect( strpos( $rendered, 'value="'.$value1.'"' ), "The results get rendered back into the form");

$value2 = "hat scuzz munge";
$_REQUEST["${id}_2_${text_id}"] = $value2;

$result = $list->fromForm();
$test->expect($result[$id][1][$text_id]==$value1, "value1  returned fromForm()");
$test->expect($result[$id][2][$text_id]==$value2, "value2  returned fromForm()");
$rendered = $list->render($result);
$test->expect( strpos( $rendered, 'value="'.$value1.'"' ), "The value1 still gets rendered back into the form");
$test->expect( strpos( $rendered, 'value="'.$value2.'"' ), "The value2 get rendered back into the form");


