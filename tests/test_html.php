<?php

$id="test_html";

$cols = 40;
$rows = 3;

$content_key = "content_html";
$content_text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum';
$content_title = 'Content Title';
$content_value = "<div>
                    <h2>".$content_title."</h2>
                        <p>".$content_text."</p></div>";


$htmlarea = new FloraForm_Field_HTML(array("id"=>$id,"cols"=>$cols,"rows"=>$rows));

$rendered = $htmlarea->render();
$test->expect(strpos($rendered,'<textarea'),"html - textarea tag rendered");
$test->expect(strpos($rendered,'cols="'.$cols.'"'),"html - correct number of columns rendered");
$test->expect(strpos($rendered,'rows="'.$rows.'"'),"html - correct number of rows rendered");
$test->expect(strpos($rendered,'style="width:100%"'),"html - style rendered for textarea");
$test->expect(strpos($rendered,'ff_addWysiwyg( \''.$id.'\' )'),'html - js functions rendered');

$rendered = $htmlarea->render(array($id=>$content_value));
$test->expect(strpos($rendered,$content_text),"html - html content text rendered");
$test->expect(strpos($rendered,$content_title),"html - html content title rendered");

$id2 = "test_html2";
$cols = 20;
$rows = 3;

$content_key = "content_html";
$content_value2 = 'one\r\ntwo\r\nthree'; 
$htmlarea = new FloraForm_Field_HTML(array("id"=>$id2,"cols"=>$cols,"rows"=>$rows));
$rendered = $htmlarea->render();

$_POST[$id2] = $content_value2;
$result = array();
$htmlarea->fromForm($result, $_POST);


$test->expect( array_key_exists($id2, $result), "html - The id is in the result array");
$test->expect( $result[$id2] == $content_value2, "html - the selected value was correctly found");

$_REQUEST[$id2] = $content_value2;
$result2 = $htmlarea->fromForm();

$test->expect( array_key_exists($id2, $result2), "html - From form without args - id in the result array");
$test->expect( $result2[$id2] == $content_value2, "html - From form without args value was correctly found");

$content_value3 = 'one
two
three
four
five';
$_POST[$id2] = $content_value3;
$result = array();
$htmlarea->fromForm($result, $_POST);

$test->expect( array_key_exists($id2, $result), "html - The id is in the result array");
$test->expect( $result[$id2] != $content_value3, "html - invalid value is not returned");

$cols = 5;
$htmlarea = new FloraForm_Field_HTML(array("id"=>$id2,"cols"=>$cols,"rows"=>$rows));
$rendered = $htmlarea->render();

$content_value4 = '1234567';
$_REQUEST[$id2] = $content_value2;
$result2 = $htmlarea->fromForm();

$test->expect( array_key_exists($id2, $result2), "html - From form without args - id in the result array");
$test->expect( $result2[$id2] == $content_value4, "html - From form without args invalid value was not returned");

