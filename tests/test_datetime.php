<?php

$id = 'test_datetime';
$datevalue = "2015-10-10";
$timevalue = "12:00:00";

$datetime = new FloraForm_Field_DateTime(array("id"=>$id));
$dateid = $id.'_date';
$timeid = $id.'_time';
#$rendered = $datetime->render(array($dateid=>"2015-10-10",$timeid=>"12:00 AM"));
$rendered = $datetime->render(array($id=>$datevalue.' '.$timevalue));


$test->expect(strpos($rendered,'<div class="bootstrap-datepicker"'),'datetime - datepicker tag rendered correctly');
$test->expect(strpos($rendered,'<div class="bootstrap-timepicker"'),'datetime - timepicker tag rendered correctly');
$test->expect(strpos($rendered,'$(\'#test_datetime_date\').datepicker'),'datetime - datepicker js configuration rendered');
$test->expect(strpos($rendered,'$(\'#test_datetime_time\').timepicker'),'datetime - timepicker js configuration rendered');

$test->expect(strpos($rendered,'value="'.$datevalue.'"'),'datetime - correct date is rendered');
$test->expect(strpos($rendered,'value="'.$timevalue.'"'),'datetime - correct time is rendered');

$timevalue2 = "12PM";
$datevalue2 = "05-09-2014";
$formateddatevalue2 = "2014-09-05";

$datetime2 = new FloraForm_Field_DateTime(array("id"=>$id));
$rendered = $datetime2->render(array($id=>$timevalue2.' '.$datevalue2));

$test->expect(strpos($rendered,'value="'.$formateddatevalue2.'"'),'datetime - 2nd correct date is rendered');
$test->expect(strpos($rendered,'value="'.$timevalue.'"'),'datetime - 2nd correct time is rendered');

$timevalue3 = "11.30 PM";
$datevalue3 = "2015 1st September";
$formattedtimevalue = "23:30:00";
$formatteddatevale = "2015-09-01";

$datetime3 = new FloraForm_Field_DateTime(array("id"=>$id));
$rendered = $datetime3->render(array($id=>$timevalue3.' '.$datevalue3));

$test->expect(strpos($rendered,'value="'.$formattedtimevalue.'"'),'datetime - 3nd correct date is rendered');
$test->expect(strpos($rendered,'value="'.$formatteddatevale.'"'),'datetime - 3nd correct time is rendered');

$wrongdate = "henry wilkes";
$wrongtime = "name";
$rendered = $datetime3->render(array($id=>$wrongtime.' '.$wrongdate));

$test->expect(strpos($rendered,'value=""'),'datetime - invalid date not rendered');
$test->expect(strpos($rendered,'value=""'),'datetime - invalid time not rendered');

$_POST[$id.'_date'] = $datevalue;
$_POST[$id.'_time'] = $timevalue;

$result = array();
$datetime->fromForm($result, $_POST);
$test->expect($result[$id] == $datevalue.' '.$timevalue,'datetime - POST returns single concatenated value');


$_REQUEST[$id.'_date'] = $datevalue;
$_REQUEST[$id.'_time'] = $timevalue;
$result2 = $datetime->fromForm();
$test->expect($result2[$id] == $datevalue.' '.$timevalue,'datetime - fromForm no args returns single concatenated value');

$wrongdatevalue = 'henry wilkes';
$wrongtimevalue = 'time';

$_POST[$id.'_date'] = $wrongdatevalue;
$_POST[$id.'_time'] = $wrongtimevalue;

$result = array();
$datetime->fromForm($result, $_POST);
$test->expect($result[$id] == null || $result[$id] == "" ,'datetime - POST returns empty string for invalud values');

