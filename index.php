<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Lineage</title>
	<meta name="description" content="">
	<meta name="author" content="Ryan Ludwig">

	<meta name="viewport" content="width=device-width,initial-scale=1">

	<!-- if greater than ie8 or not ie, serve css with mediaqueries -->
	<!--[if gt IE 8]><!-->
	<link rel="stylesheet" media="all" href="assets/css/main.css" />
	<!--<![endif]-->

	<!-- if ie8 or lower serve no mediaqueries -->
	<!--[if lte IE 8]>
	<link rel="stylesheet" media="all" href="assets/css/ie.css" />
	<![endif]-->

	<script src="assets/js/modernizr-2.0.6._development.js"></script>

	<!-- get the device max resolution -->
	<script>var device_width=Math.max(screen.width);</script>


	<!-- don't forget google analytics here -->

</head>
<body>

<div class="container">
	<header>

	</header>
	<div id="main" role="main">




	<?php
	ini_set('display_errors',1); 
	error_reporting(E_ALL);

	$data_source = 'data_files/momsfamilytree.xml';
	parse_the_feed($data_source);

	function parse_the_feed($feedquery) {

		$data=simplexml_load_file("$feedquery");


		
		//echo out each indiviual
		echo '<ul>';
		foreach($data->individuals->i as $individual) :
			$id = $individual['id'];
			$first_name = $individual->f;
			$last_name = $individual->l;
			$display_name = $individual->dn;
			$maiden_name = $individual->id;
			$birth = $individual['b'];
			$death = $individual['d'];
			$gender = $individual['g'];
			$email = $individual->mEmail;
			$has_children = $individual['p'];
			$child_of = $individual['c'];

			echo '<li><a name="id_' . $id . '"></a>' . $display_name;
			
			echo '<ul>';

				if ($gender) {
					echo '<li>';
					if ($gender == "M") {
						echo "Male";
					}else if ($gender == "F") {
						echo "Female";
					}
					echo '</li>';
				}
				if ($birth) {
					echo '<li>Born: ' . format_date($birth) . '</li>';
				}
				if ($death) {
					echo '<li>Died: ' . format_date($death) . '</li>';
				}

				if ($birth && $death) {
					echo '<li>Lived ' . calculate_lifespan($birth, $death) . '</li>';
				}

				if ($email) {
					echo '<li>Email: <a href="mailto:' . $email . '">' . $email . '</a></li>';
				}

				if ($has_children) {
					//echo '<li>Couple id:  ' . $has_children . '</li>';
					echo '<li>Children <ul>';
						echo return_children($data, $has_children);
					echo '</ul></li>';
				}

				if ($child_of) {
					//echo '<li>Child of:  ' . $child_of . '</li>';
					echo '<li>Parents <ul>';
						echo return_parents($data, $child_of);
					echo '</ul></li>';
				}
				echo '</ul>';

			echo '</li>';

		endforeach;
		echo '</ul>';



	}

	function format_date($date) {
		//remove the excess 0;
		$newdate = substr_replace($date ,"",-1);

		$theyear = substr($newdate,0, 4);
		$themonth = substr($newdate, 4,2);		
		$theday = substr($newdate, 6, 2);

		$output = $theyear . '-' . $themonth . '-' . $theday;
		return $output;
	}

	function return_name_from_id($data, $passed_id) {
		foreach($data->individuals->i as $individual) :
			$id = $individual['id'];
			$display_name = $individual->dn;
			if ((string) $passed_id == $id) {
				echo '<a href="#id_' . $passed_id . '">' . $display_name . '</a>';
			}

		endforeach;
	}

	function calculate_lifespan($birth, $death) {
		//remove the excess 0;
		$birth = substr_replace($birth ,"",-1);
		$death = substr_replace($death ,"",-1);

		$lifespan = $death - $birth;
		$days = substr($lifespan, -2);
		$months = substr($lifespan, -3, -2);
		$years = substr($lifespan, -7, -4);
		$output =  $years . ' years';

		return $output;
	}
	
	function return_children($data, $passed_id) {
		foreach($data->families->f as $family) :

			$family_id = $family['id'];
			$children = $family['c'];

			if ((string) $family_id == $passed_id) {
				$children_raw = $children;
				$children_array = explode(",", $children_raw);
				foreach($children_array as $child){
					echo '<li>';
					return_name_from_id($data, $child);
					echo '</li>';
				}
			}
		endforeach;
	}

	function return_parents($data, $passed_id) {
		foreach($data->families->f as $family) :
			$family_id = $family['id'];
			$husband = $family['h'];
			$wife = $family['w'];

			if ((string) $family_id == $passed_id) {
				if ($husband) {
					echo '<li>';
						return_name_from_id($data, $husband);
					echo '</li>';
				}
				if ($wife) {
					echo '<li>';
						//echo $wife;
						return_name_from_id($data, $wife);
					echo '</li>';
				}
			}
		endforeach;
	}



	?>


	</div>

	<footer>

	</footer>



</div>


<!-- CDN jQuery with local fallback -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src='assets/js/jquery-1.7.1.min.js'><\/script>")
</script>


<!-- load in zepto / jquery if you're IE.  http://zeptojs.com/
<script>
document.write('<script src="assets/js/' + ('__proto__' in {} ? 'zepto.0.8.min' : 'jquery-1.7.1.min') + '.js"><\/script>')
</script>
 -->

<script>
	$(function() {

	});
</script>

</body>
</html>