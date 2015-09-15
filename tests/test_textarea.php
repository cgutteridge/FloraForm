<?php 
$id="test_textarea";
$value="foo bacon\r\n
	foo eggs\r\n
	foo ham\r\n";
$numberOfRows = 3;
$numberOfColumns = 2;
$textarea = new FloraForm_Field_Textarea(array("id"=>$id,"rows"=>$numberOfRows,"cols"=>$numberOfColumns));

$rendered = $textarea->render(array($id=>$value));

$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "textarea - id is rendered into the area form");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "textarea - name is rendered into the area form");
$test->expect( strpos( $rendered, '<textarea ' ), "textarea - area textarea tag is rendered");

$_POST[$id] = $value;
$result = array();
$textarea->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "textarea - The area id is in the result array");
$test->expect( $result[$id] == $value, "textarea - the area value was correctly found");

$_REQUEST[$id] = $value;

$result2 = $textarea->fromForm();

$test->expect( array_key_exists($id, $result2), "textarea - From area form without args - id in the result array");

$test->expect( $result2[$id] == $value, "textarea - From area form without args value was correctly found");

$test->expect(strpos($rendered,'rows="'.$numberOfRows.'"' ),"textarea - Correct number of rows");
$test->expect(strpos($rendered,'cols="'.$numberOfColumns.'"' ),"textarea - Correct number of columns");

$id2 = "test_second";

$textarea->setId($id2);

$rendered = $textarea->render();

$test->expect( strpos( $rendered, 'id="'.$id2.'"' ), "textarea - after setId() the id is rendered into the area form");
$test->expect( strpos( $rendered, 'name="'.$id2.'"' ), "textarea - after setId() the name is rendered into the area form");