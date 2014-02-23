<!DOCTYPE html>
<html>
<head>
	<title>FloraForm Demo</title>
	<script type="text/javascript" src="http://www.tinymce.com/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="html_assets/floraform/ff.js"></script>
	<link rel="stylesheet" href="html_assets/floraform/ff.css" />
</head>
<body>
<?php 
$f3=require(__DIR__.'/lib/fatfree/lib/base.php');

$f3->set('DEBUG',3);
$f3->set('UI',__DIR__.'/templates/');
$f3->set('CACHE',FALSE);
require_once(__DIR__.'/FloraForm.php');
ini_set('display_errors',1);
error_reporting(E_ALL);

$form = new FloraForm();
$config = array(
	array("LIST"=>array("id"=>"hats", "fields"=>array(
		array("COMBO"=> array("id"=>"", "fields"=> array(
			array("TEXT" => array("id"=>"bar", "title"=>"WIZ")),
			array("SECTION" => array( "fields"=>array(
				array("TEXT" => array( "id"=>"baz", "title"=>"BANG"))
			))),
			array("CONDITIONAL" => array( 
				"fields" => array(
					array("TEXT" => array("id"=>"barbar", "title"=>"WIZ"))
				),
				"conditions"=>array(		
					array("bac", array("SECTION" => array( "fields"=>array(
						array("TEXT" => array( "id"=>"bazbar", "title"=>"BANG"))
					)))),
					array(".*", array("SECTION" => array( "fields"=>array(
						array("TEXT" => array( "id"=>"bazfoo", "title"=>"BONG"))
					))))
				)
			))
		))),
	))),
	array("SUBMIT"=>array("text"=>"Submit it!"))
);
$form->processConfig($config);


if(empty($_POST)){
	echo $form->render(array("hats"=>array(
		array(
			"bar"=>"mug",
			"baz"=>"mug2",
			"barbar"=>"wiizwiiziz",
			"bazfoo"=>"i2222wiizwiiziz",
			
		),
		array(
			"bar"=>"mug",
			"baz"=>"mug2",
			"barbar"=>"wiizwiiziz",
			"bazfoo"=>"i2222wiizwiiziz",
			
		)
	)));
}else{
	$data =array();
	$form->fromForm($data, $_POST ); 
	echo "<pre>".print_r($data, true)."</pre>";	
	echo "<pre>".print_r($_POST, true)."</pre>";	
}
?>
</body>
</html>
