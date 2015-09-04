<?php

$f3=require_once(__DIR__.'/../lib/fatfree/lib/base.php');
$f3->set('DEBUG',5);
$f3->set('UI',__DIR__.'/../templates/');
$f3->set('CACHE',FALSE);

require_once(__DIR__."/../FloraForm.php");

$test = new Test;

#include("test_text.php");
#include("test_combo.php");
#include("test_list.php");
#include("test_textarea.php");
#include("test_number.php");
#include("test_hidden.php");
#include("test_info.php");
include("test_choice.php");

foreach ($test->results() as $result) {
    echo $result['text'].': ';
    if ($result['status'])
        echo 'Pass';
    else
        echo 'Fail ('.$result['source'].')';
    echo "\n";
}

