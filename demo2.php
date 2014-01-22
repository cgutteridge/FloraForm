<!DOCTYPE html>
<html>
<head>
	<title>FloraForm Demo</title>
	<script type="text/javascript" src="http://www.tinymce.com/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="html_assets/floraform/ff.js"></script>
	<link rel="stylesheet" href="html_assets/floraform/ff.css" />
</head>
<?php
class Demo {
	private $MAIN_TEXT_FIELDS = array( 
		"topics", "learning_outcomes" ,"introduction", "provisional_notes", "assessment_notes", "timetable_notes"
	);

	private $REFERRAL_OPTIONS = array(
		"100EXAM"=> "by examination.",
		"EXAM"=>"by examination, with the original coursework mark being carried forward.",
		"EXAMCWORK"=> "by examination and a new coursework assignment.",
		"CWORK"=>"by set coursework assignment(s).",
		"LAB"=> "by means of a special one-day laboratory session.",
		"REWRITE"=>"by re-write of the project report and re-viva (the original progress report mark will be carried forward).",
		"NONE"=>"There is no referral opportunity for this syllabus in same academic year",
		"NOTES"=>"See notes below" 
	);

	private $SA_TYPES = array( 
		""=>"",
		"lecture"=>"Lecture",
		"examples"=>"Examples Class",
		"tutorial"=>"Tutorial",
		"computer_lab"=>"Computer Lab",
		"specialist_lab"=>"Specialist Lab",
		"field_trip"=>"Field Trip" 
	);

	private $SA_DURATIONS = array( 
		""=>"",
		"1"=>"1 hour", "2"=>"2 hours", "3"=>"3 hours", "4"=>"4 hours", "5"=>"5 hours",
		"6"=>"6 hours", "7"=>"7 hours", "8"=>"8 hours", "9"=>"9 hours", "10"=>"10 hours",
		"11"=>"11 hours", "12"=>"12 hours", "13"=>"13 hours", "14"=>"14 hours", "15"=>"15 hours",
		"16"=>"16 hours", "17"=>"17 hours", "18"=>"18 hours", "19"=>"19 hours", "20"=>"20 hours" 
	);

	private $SA_FREQUENCIES = array( 
		""=>"",
		"1/week" => "Once per week",
		"2/week" => "Twice per week",
		"3/week" => "Three times per week",
		"4/week" => "Four times per week",
		"1/2week" => "Once per fortnight",
		"once" => "Once in syllabus",
	);

	private $ASSESSMENT_TYPES = array(
		"exam" => "Exam",
		"other" => "Other",
		"labs" => "Labs",
		"cwork" => "Coursework",
	);

	private $RESOURCE_TYPES = array(
		''=>'',
		'core'=>"Core textbook",
		'background'=>"Background textbook",
		'otherlib'=>"Other library support required",
		'staff'=>"Staff requirements (including teaching assistants and demonstrators)",
		'teachingspace'=>"Teaching space, layout and equipment required",
		'labspace'=>"Laboratory space and equipment required",
		'computer'=>"Computer requirements",
		'software'=>"Software requirements",
		'online'=>"On-line resources",
		'other'=>"Other resource requirements" 
	);

	private $CHANGE_SCALE = array(
		"cosmetic"=>"Purely Cosmetic",
		"minor"=>"Minor",
		"major"=>"Major", 
	);




	public function getForm( $flags = array() )
	{
		$params =  array( "heading"=>1, "resourcesURL"=>"html_assets/floraform", ); 
		$params["action"] = "";
		$form = new FloraForm($params);


		# Section 1.
		$config = array( 
			array( "SECTION" => 
				array( 
					"title" => "Provisional Module Description",
					"fields" => 
						array(
							array( "INFO" => 
								array( 
									"content_html" => "<p>This syllabus description has not yet been formally linked with a code and session.</p>" 
								)
							),
							array( "TEXT" => 
								array( 
									"id"=>"provisionaltitle",
									"title"=>"Provisional Module Title",
									"layout"=>"vertical"
								)	
							),
							array( "TEXT" => 
								array( 
									"id"=>"provisionalcode",
									"title"=>"Provisional Module Code",
									"layout"=>"vertical"
								)
							),
							array( "CHOICE" => 
								array( 
									"id"=>"provisionalsession",
									"title"=>"Provisional Session",
									"mode"=>"pull-down",
									"choices"=>
										array( 
											"" => "Select...",
											"1213" => "2012-2013",
											"1314" => "2013-2014",
											"1415" => "2014-2015",
											"1516" => "2015-2016",
											"1617" => "2016-2017", 
										)
								)
							),
							array( "TEXT" =>
								array( 
									"id"=>"provisionalsemester",
									"title"=>"Provisional Semester",
									"layout"=>"vertical"
								)
							),
							array( "HTML" => 
								array( 
									"id"=>"provisionalnotes",
									"title"=>"Provisional Notes",
								)
							)
						)
				)
			),
			array( "SECTION" => 
				array(
					"title" => "1. Description",
					"fields" => 
						array(
							array( "HTML" => 
								array( 
									"id" => "introduction", # TODO change field name
									"title" => "1.1 Introduction",
									"rows" => 10,
									"description" => "This section should be used to give a summary of the syllabus, its aims, and (for core / compulsory syllabuss) how it fits in with the programme as a whole or (for optional syllabuss) why students might choose to take it. You can also give a general indication of pre-requisite knowledge and skills which are assumed."
								)
							),
							array( "HTML" => 
								array( 
									"id" => "learningoutcomes", # TODO change field name
									"title" => "1.2 Learning Outcomes",
									"rows" => 10,
									"description" => "This section should be used to list the intended learning outcomes of the syllabus. You can refer to <a href='http://www.opendatacompetition.soton.ac.uk/newsite/'>guidance in the quality handbook</a> for advice on these. For a standard 15 credit syllabus, 5 to 8 outcomes should be sufficient. Please do not repeat the list of topics for the syllabus, which are given in the following section.",
								)
							),
							array( "HTML" => 
								array( 
									"id" => "topics", # TODO change field name
									"rows" => 10,
									"title" => "1.3 Topics",
									"description" => "A summary of contents covered, perhaps 10 to 20 bullet points." 

								)
							),
							array( "FILE" => 
								array(
									"id"=>"suplementalfile",
									"title"=> "Supplemental Information"
								)
							)
						)
				)
			),
			array( "SECTION" => 
				array(
					"title" => "2. Basic Information",
					"fields" =>
						array(
							array( "LIST" => 
								array( 
									"id" => "regularteaching",
									"layout" => "section",
									"title" => "2.1 Scheduled Teaching Activities",
									"description" => "This section allows you to provide data for timetabling purposes and key information sets. For each scheduled activity, please indicate the nature of the activity, its duration, and its frequency (ie the number of sessions).  If the class divides into groups for this activity, please give the (maximum) group size; in this case, the frequency should be given from the student perspective. Otherwise, leave this field blank. ",
									"fields"=>
										array(
											array( "COMBO"=> 
												array(
													"fields"=>
														array(
															array( "CHOICE" => 
																array(
																	"id" => "activitytype",
																	"title" => "Type",
																	"choices" => $this->SA_TYPES,
																	"mode" => "pull-down" 
																) 
															),
															array( "CHOICE" => 
																array(
																	"id" => "duration",
																	"title" => "Duration",
																	"choices" => $this->SA_DURATIONS,
																	"mode" => "pull-down" 
																) 
															),
															array( "CHOICE" => 
																array(
																	"id" => "frequency",
																	"title" => "Frequency",
																	"choices" => $this->SA_FREQUENCIES,
																	"mode" => "pull-down" 
																) 
															),
															array( "TEXT" => 
																array( 
																	"id" => "groupsize",
																	"size" => 2,
																	"title" => "Group Size" 
																)
															)
														)
													)
											)
										)
								) 
							),
							array( "LIST" => 
								array( 
									"id" => "assessment",
									"layout" => "section",
									"title" => "2.2 Summative Assessment",
									"description" => "This section allows you to provide data for student workload monitoring, and key information sets. Note that the total percentages across all assessment activities should add up to 100. For an exam, give the planned duration. For other assessment activities, please indicate the week or weeks of the semester when they are planned to occur, where week 1 is the start of teaching, and week 12 is the last week before exams, which is typically reserved for revision. Note that assignment deadlines should therefore not occur during weeks 12 to 15.  Finally, indicate when and how you will provide feedback on assignments -- for example, you might state that <i>after 2 weeks individual feedback sheets will be returned, and a generic feedback will be provided in-class.</i>",
									"fields" => 
										array(
											array( "COMBO" =>
												array( 
													"fields" => 
														array(
															array( "TEXT" => 
																array(
																	"id" => "description",
																	"layout" => "vertical",
																	"title" => "Description" 
																)
															),
															array( "TEXT" => 
																array(
																	"id" => "frequency",
																	"size" => 3,
																	"layout" => "vertical2up",
																	"title" => "Frequency" 
																)
															),
															array( "TEXT" =>
																array(
																	"id" => "percent",
																	"layout" => "vertical2up",
																	"size" => 3,
																	"suffix" => "%",
																	"title" => "Total Percentage" 
																)
															),
															array( "CHOICE" => 
																array(
																	"id" => "type",
																	"layout" => "vertical",
																	"title" => "Type",
																	"choices" => $this->ASSESSMENT_TYPES,
																	"lots-of-class" => true,
																	"mode" => "radio" 
																) 
															),
															array( "INFO" => 
																array(
																	"layout" => "vertical2up",
																	"content_html" => "<div style='text-align:center'>If exam, give duration</div>" 
																)
															),
															array( "INFO" => 
																array(
																	"layout" => "vertical2up",
																	"content_html" => "<div style='text-align:center'>Otherwise give week No(s)</div>" 
																)
															),
															array( "TEXT" => 
																array(
																	"id" => "examduration",
																	"layout" => "vertical2up",
																	"size" => 3,
																	"suffix"=>" hours",
																	"title" => "Exam Duration" 
																)
															),
															array( "TEXT" => 
																array(
																	"id" => "weeknos",
																	"layout" => "vertical2up",
																	"title" => "Week no(s)" 
																)
															),
															array( "TEXTAREA" => 
																array(
																	"id" => "feedback",
																	"rows" => "3",
																	"layout" => "block",
																	"title" => "Feedback" 
																)
															)
														)
												)
											)
										)
								) 
							)
						)
				)
			)
		);

		$form->processConfig($config);
		return $form;
	}
/*

		$s1->add( "CHOICE", array( 
			"id" => "referral",
			"layout" => "section",
			"title" => "2.3 Referral Policy",
			"description" => "
	Each syllabus must have a defined referral policy, which must apply to all students who refer.
	University policy requires that failure should be redeemable, so students need an opportunity to correct any failure (typically during the summer break, but possibly also within the academic year itself, or if neither of these is possible, then the following year).
	",
			"prefix" => "On referral, this unit will be assessed ",
			"choices" => $this->REFERRAL_OPTIONS,
			"mode" => "pull-down" ) );
	##		$s1->add( "TEXT", array( 
	#			"id" => "referral_notes",
	#			"title" => "Referral Notes",
	#		));
		$s1->add( "HTML", array( 
			"id" => "assessmentnotes",
			"title" => "2.4 Assessment Notes",
			"description" => "
	If there are special aspects related to assessment, please state them here.  As one possible example, <i>where there are multiple worksheets, the best 8 out of 10 marks will be taken; or if a minimum attendance of 8 out of 10 laboratory sessions is required before a mark can be returned.</i>  Finally, if there is a field trip, please state the arrangements and cost implications.
	",
			"layout" => "section",
		));
		$s1->add( "HTML", array( 
			"id" => "timetablenotes",
			"title" => "2.5 Timetabling Requirements",
			"description" => "
	If there are special timetabling requirements, for example, a specific venue or specialist facilities are needed, please indicate these in this field.  This information is provided to the Central Timetabling Unit, and is not visible to students.
	",
			"layout" => "section",
		));




		$s3 = $form->add( "SECTION", array(
			"title" => "3. Resources"));
			
		$res_combo = $s3->add( "LIST", array(
			"id" => "resources",
			"layout" => "section",
			))->setListType( "COMBO" );
		$res_combo->add( "CHOICE", array( 
			"id" => "type",
			"title" => "Type",
			"choices" => $this->RESOURCE_TYPES,
			"mode" => "pull-down" ) );
		$res_combo->add( "TEXT", array(
			"id" => "isbn",
			"title" => "ISBN" ) );	
		$res_combo->add( "HTML", array(
			"id" => "details",
			"rows" => 2,
			"layout" => "block",
			"title" => "Details" ) );	



		$s4 = $form->add( "SECTION", array(
			"title" => "4. Changes",
			));
		$s4->add( "HTML", array(
			"id" => "changessummary",
			"title" => "4.1 Recent Changes",
			"description" => "Please use this section to summarise recent changes to the syllabus, and why they were made. If this was in response to student comments, please quote some of them, or link to the questionnaire data.",
			"layout"=>"section" ));
		$s4->add( "SECTION", array(
			"title" => "4.2 Nature of Edit",
			"description_html" => "
	Changes which are significant &lt;b&gt;need&lt;/b&gt; to be reviewed by the <b>director of programmes and/or FPC</b>. In addition, each syllabus should be peer reviewed once every five years (at least).   If you have made a significant change to syllabus content, please set the approval flag to No.  If you have changed the list of textbooks, please set the library flag to No.
	",
			));


		# The restriction of options is the only restriction. A malicious user could currently 
		# hack their response to approve a course, but that would be a very odd thing to do.

		$options = array( "0"=>"No", "1"=>"Yes" );
		$s4->add( "CHOICE", array( 
			"id" => "librarychecked",
			"title" => "There is a copy of each textbook in the University library",
			"choices" => $options,
			"layout" => "vertical",
			"mode" => "radio" ) );

		$options = array( "0"=>"No", "1"=>"Yes" );
		$fpcchecked = $s4->add( "CHOICE", array( 
			"id" => "directorchecked",
			"title" => "The content of this syllabus has been approved by Director of Programmes and/or FPC",
			"choices" => $options,
			"layout" => "vertical",
			"mode" => "radio" ) );
		$change_summary = new FloraForm_Field_HTML( array(
			"id" => "changessummarytest",
			"title" => "4.1 Recent Changes",
			"description" => "Please use this section to summarise recent changes to the syllabus, and why they were made. If this was in response to student comments, please quote some of them, or link to the questionnaire data.",
			"layout"=>"section" ));

		$options = array( ""=>"","0"=>"No", "1"=>"Yes" );
		$field = $s4->add( "CONDITIONAL", array(
			"conditions"=> array(
				array("^0$", $fpcchecked),
				array("^1$", $change_summary)
			) ) );
		$field->add("CHOICE", array( 
			"id" => "reviewchecked",
			"title" => "The content of this syllabus has been subject to quinquennial review",
			"choices" => $options,
			"layout" => "vertical",
			"mode" => "pull-down" ) );

		$form->add( "HIDDEN", array( 
			"id" => "syllabusid",
		));
		$form->add( "SUBMIT", array( 
			"text" => "Save Changes",
		));


		$form->processConfig($config);
		return $form;
	}
	*/

	public function renderForm($flags=array())
	{
		$form = $this->getForm($flags);
		return $form->render();
	}

	public function fromForm($flags=array())
	{
		$data = array();
		$this->getForm($flags)->fromForm( $data, $_POST );
		return $data;
	}

	public function issues()
	{
		$issues = array();
		return $issues;
	}
}
?>
<body>
<?php 
$f3=require(__DIR__.'/lib/base.php');
#$f3=require('/tmp/fatfree/lib/base.php');

$f3->set('DEBUG',3);
$f3->set('UI',__DIR__.'/templates/');
$f3->set('CACHE',FALSE);
require_once(__DIR__.'/FloraForm.php');
ini_set('display_errors',1);
error_reporting(E_ALL);



$demo = new Demo();
if(empty($_POST)){
	echo $demo->renderForm();
}else{
	$data = $demo->fromForm( ); 
	echo "<pre>".print_r($data, true)."<pre>";	
}
?>
</body>
</html>
