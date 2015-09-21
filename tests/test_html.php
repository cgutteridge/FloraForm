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


