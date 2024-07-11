<?php
// ob_start();
session_start();
if (!isset($_SESSION['name'])) {
	header("location: login.php");
	exit;
}
?>

<!DOCTYPE html>
<html>

<head>
	<?php
	ini_set('display_errors', false);
	error_reporting(E_ALL);
	require_once("class_loader.php");
	require_once("include/lx.pdodb.php");
	require_once("db_config.php");
	require_once("include/func.php");
	// echo "<pre>";

	$cl = new ClassLoader();
	require_once './include/class/htmlobject.php';
	require_once './include/class/button.php';
	require_once './include/class/pager.php';
	require_once './include/class/pager_advsearch.php';
	?>

	<title>Sample Crud</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Employee Management</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

	<script type="text/javascript" src="./lib/jquery/1.10.4/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="./lib/jquery/1.10.4/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script type="text/javascript" src="./javascripts/regex.js"></script>
	<script type="text/javascript" src="./javascripts/regex-loader.js"></script>
	<link type="text/css" rel="stylesheet" href="./lib/jquery/1.10.4/css/blue_style/jquery-ui-1.10.4.custom.min.css">

	<script type="text/javascript" src="lib/alertify/alertify.js"></script>
	<script type="text/javascript" src="lib/alertify/alertify.min.js"></script>
	<!-- CSS -->
	<link rel="stylesheet" href="./lib/alertify/css/alertify.min.css" />
	<link rel='stylesheet' type='text/css' media='screen' href='./css/layout_body_lst.css' />
	<link rel='stylesheet' type='text/css' media='screen' href='./css/pager.css' />
	<link href="include/style.css" rel="stylesheet" type="text/css">
	<!-- Default theme -->
	<link rel="stylesheet" href="./lib/alertify/css/themes/default.min.css" />
	<!-- Semantic UI theme -->
	<link rel="stylesheet" href="./lib/alertify/css/themes/semantic.min.css" />
	<!-- Bootstrap theme -->
	<link rel="stylesheet" href="./lib/alertify/css/themes/bootstrap.min.css" />

	<!-- lstv_freeze_table -->
	<link rel="stylesheet" href="./javascripts/lst_freeze_table/lst_freeze_table.css" />
	<script src="./javascripts/lst_freeze_table/lst_freeze_table.js"></script>

	<!-- searchable -->
	<script src="./javascripts/searchmodal/searchable.js" type="text/javascript"></script>
	<link rel="stylesheet" href="./javascripts/searchmodal/searchable.css" type="text/css" media="screen" />

	<!-- mask -->
	<script src="./lib/Mask/dist/jquery.mask.js" type="text/javascript"></script>

	<!-- font-awesome -->
	<link rel="stylesheet" type="text/css" href="./lib/font-awesome-4.6.2/css/font-awesome.min.css" />

	<style>
		#pager_modal table td:first-child {
			padding: 10px;
			white-space: nowrap;
		}

		.pager_navigator input[readonly] {
			border: 1px solid transparent;
			background: #8e8e8e;
			transition: all 0.3s;
		}

		.pager_navigator input[readonly]:hover {
			background: #333;
		}

		.pager_no_rec_tr td {
			text-align: center !important;
		}
	</style>
</head>

<body>
	<nav class="navbar navbar-expand-lg bg-success p-2 bg-opacity-75">
		<div class="container-fluid">

			<a class="navbar-brand" href="index.php">Employee Management</a>
			<!-- <li><a href="trn_simple_crud.php">SIMPLE CRUD</a></li> -->
			<a class="navbar-brand" href="trn_pager_employee.php">PAGER</a>
			<a class="navbar-brand" href="trn_searchable.php">SEARCHABLE</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<?php echo $_SESSION['name']; ?>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="logout.php">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<form method="POST" action="" id="myform" name="myform" autocomplete="off">
<!-- 
		<div class="mt-5 d-flex justify-content-center">
			<button class="btn btn-dark ms-2 mb-4 " id="btn_add">Add New</button>

			<button type="button" class="btn btn-dark ms-2 mb-4" id="btn_print" onclick="print_click('pdf')">Print</button>

			<input type="text" name="txt_repoutput" hidden> -->

		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>