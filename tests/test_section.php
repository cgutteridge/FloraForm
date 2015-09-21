<?php
$id = "test_section";
$title = "Section title";
$desc = "<p>Desciption of the section</p>";

$section = new FloraForm_Section(array("id"=>$id,"title"=>$title,"description_html"=>$desc));

$rendered = $section->render();


$test->expect( strpos($rendered,'<div'),"section - section tag rendered");
$test->expect( strpos($rendered,'id="'.$id.'_container"'),"section - html content rendered");
$test->expect( strpos($rendered,$desc),'section - description rendered');
$test->expect( strpos($rendered,'<h2 class=\'ff_title\'>'.$title.'</h2>'),'section - description rendered');

$id2="test_text";
$value2="foo bacon";
$section->add("TEXT",array("id" => $id2,
			"title" => $id2 ));

$rendered = $section->render(array($id=>array($id2=>$value2)));

$test->expect( strpos($rendered,'<input name="'.$id.'_'.$id2.'"'),'section - text input field rendered');
$test->expect( strpos($rendered,'value="'.$value2.'"'),'section - text input field input rendered');

$id3="test_info";
$opt_key = "info_html";

$title_key = "title_html";
$title_value = "title";

$desc_key = "description_html";
$desc_value = "description of thingy";
$value3="bacon bacon";
$content_key = "content_html";
$content_value = "<div>
                    <h2>Content Title</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
                            </p></div>";
$section->add("INFO",array("id"=>$id3,$opt_key=>$value3,$title_key=>$title_value,$desc_key=>$desc_value,$content_key=>$content_value));

$rendered = $section->render();
$test->expect( strpos($rendered,$content_value),'section - info value rendered into section');
$test->expect( strpos($rendered,$desc_value ),'section - info description rendered into section');
