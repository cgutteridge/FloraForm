<?php 
$id="test_textarea";
$value="foo bacon\r\n
	foo eggs\r\n
	foo ham\r\n";
$numberOfRows = 3;
$numberOfColumns = 2;
$textarea = new FloraForm_Field_Textarea(array("id"=>$id,"rows"=>$numberOfRows,"cols"=>$numberOfColumns));

$rendered = $textarea->render(array($id=>$value));

$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "id is rendered into the area form");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "name is rendered into the area form");
$test->expect( strpos( $rendered, '<textarea ' ), "area textarea tag is rendered");

$_POST[$id] = $value;
$result = array();
$textarea->fromForm($result, $_POST);

$test->expect( array_key_exists($id, $result), "The area id is in the result array");
$test->expect( $result[$id] == $value, "the area value was correctly found");

$_REQUEST[$id] = $value;

$result2 = $textarea->fromForm();

$test->expect( array_key_exists($id, $result2), "From area form without args - id in the result array");

$test->expect( $result2[$id] == $value, "From area form without args value was correctly found");

$test->expect(strpos($rendered,'rows="'.$numberOfRows.'"' ),"Correct number of rows");
$test->expect(strpos($rendered,'cols="'.$numberOfColumns.'"' ),"Correct number of columns");

$id2 = "test_second";

$textarea->setId($id2);

$rendered = $textarea->render();

$test->expect( strpos( $rendered, 'id="'.$id2.'"' ), "after setId() the id is rendered into the area form");
$test->expect( strpos( $rendered, 'name="'.$id2.'"' ), "after setId() the name is rendered into the area form");