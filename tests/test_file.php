<?php 
$id="test_file";

$file= new FloraForm_Field_File(array("id"=>$id,"title" => $id ));
$test_value = 'example/example.jpg';
$rendered = $file->render(array("accept"=>'.jpg',"value"=>$test_value));

$test->expect( strpos( $rendered, 'id="'.$id.'"' ), "file - id is rendered into the form");
$test->expect( strpos( $rendered, 'id="'.$id.'_container"' ), "file - id is rendered into the container form");
$test->expect( strpos( $rendered, 'name="'.$id.'"' ), "file - name is rendered into the form");
$test->expect( strpos( $rendered, 'ff_input_file' ), "file - class is rendered");
$test->expect( strpos( $rendered, 'type="file"' ), "file - type is rendered");

$test->expect( strpos( $rendered, '<label ' ), "file - field title label tag is rendered");
$test->expect( strpos( $rendered, '>'.$id.'<' ), "file - field title is rendered");

var_dump($rendered);


//$_POST[$id] = $test_value;
//$result = array();
//$file->fromForm($result, $_POST);

//$test->expect( array_key_exists($id, $result), "file - The id is in the result array");

//$test->expect( $result[$id] == $test_value, "file - the value was correctly found");

/*$_REQUEST[$id] = $value;

$result2 = $hidden->fromForm();

$test->expect( array_key_exists($id, $result2), "hidden - From form without args - id in the result array");

$test->expect( $result2[$id] == $value, "hidden - From form without args value was correctly found");


$test->expect(strpos(),"file - ");*/