<?php

$id="test_info";
$opt_key = "info_html";

$title_key = "title_html";
$title_value = "title";

$desc_key = "description_html";
$desc_value = "description of thingy";

$content_key = "content_html";
$content_value = "<div>
                    <h2>Content Title</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
                            </p></div>";

$value="foo bar";
$info = new FloraForm_Info(array("id"=>$id,$opt_key=>$value,$title_key=>$title_value,$desc_key=>$desc_value,$content_key=>$content_value));

$rendered = $info->render();
$test->expect( strpos( $rendered, 'id="'.$id.'_container"' ), "info - container id is rendered");

$test->expect($info->hasHtmlOption($opt_key),"info - has html option");

$test->expect( strpos($rendered, '<h id="test_info_title"'),"info - title tag is rendered");
$test->expect( strpos($rendered, '<div id="test_info_description"'),"info - description tag is rendered");



$test->expect(strpos($rendered,$content_value),"info - html content rendered");
/*
$id2="test_text";
$value2="foo bacon foo bar";
$text = $info->add("TEXT",array("id"=>$id));
$rendered = $info->render($defaults2);
var_dump($rendered);*/