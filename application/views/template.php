<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="<?php echo base_url ('asset/img/favicon.png'); ?>" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/lib/bootstrap/css/bootstrap.min.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/lib/font-awesome/css/font-awesome.min.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/lib/bootstrap/css/bootstrap.min.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/lib/ionicons/css/ionicons.min.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/lib/bootstrap/css/bootstrap.min.css');?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/lib/adminlte/css/skins/skin-green-light.min.css');?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/lib/adminlte/css/AdminLTE.min.css');?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url ('asset/css/style.css');?>" />

	<script type="text/javascript" href="<?php echo base_url ('asset/js/calender.js');?>"> </script>
	<title> Home </title>
</head>
<body class="skin-green-light sidebar-mini">
	<div class="wrapper">

		<header class="main-header">
			<!-- Logo -->
			<a href="http://localhost/skripsiSPK/index.php/home" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"><b>SPK</b></span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg"><b>SPK</b>Peminatan</span>
			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
					</ul>
				</div>
			</nav>
		</header>
		<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- Sidebar user panel -->
				<div class="user-panel">
					<div class="image">
						<img src="<?php echo base_url('asset/img/logo.png'); ?>" alt="Logo">
					</div>
					
				</div>
				<!-- search form -->
				<form action="#" method="get" class="sidebar-form">
					<div class="input-group">
						<input type="text" name="q" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</form>
				<!-- /.search form -->
				<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu">
					<li class="header">MAIN NAVIGATION</li>
					<li class="active treeview">
						<a href="http://localhost/skripsiSPK/index.php/home">
							<i class="fa fa-dashboard"></i> <span>Dashboard</span>
						</a>
						
					</li>
					<li class="treeview">
						<a href="http://localhost/skripsiSPK/index.php/master/Akun">
							<i class="glyphicon glyphicon-user"></i>
							<span>Akun</span>
							
						</a>
						
					</li>
					<li>
						<a href="http://localhost/skripsiSPK/index.php/master/Siswa">
							<i class="glyphicon glyphicon-folder-close"></i> <span>Data Siswa</span> 
						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-tasks"></i>
							<span>Alternatif</span>
						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-list-alt"></i>
							<span>Kriteria</span>
						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="fa fa-calculator"></i> <span>Perhitungan</span>

						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="fa fa-download"></i> <span>Hasil</span>
							
						</a>
					</li>
					<li>
						<a href="http://localhost/skripsiSPK/index.php/logout" onclick="return confirm('Anda yakin akan logout?')">
							<i class="glyphicon glyphicon-off"></i> <span>Logout</span>							
						</a>
					</li>
				</ul>
			</section>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Dashboard
					
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="#"><?php echo $content;?></a></li>	
				</ol>
			</section>

			
			<!-- Main content -->
			<section class="content">
				
				<?php $this->load->view($content); ?>
			</section><!-- /.content -->
		</div><!-- /.content-wrapper -->
		<footer class="main-footer ">
			<strong><?php $this->load->view('footer'); ?></strong> 
		</footer>


	</div><!-- ./wrapper -->

	<!-- jQuery 2.1.4 -->
	<script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<!-- Bootstrap 3.3.2 JS -->
	<script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- Morris.js charts -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="../../plugins/morris/morris.min.js" type="text/javascript"></script>
	<!-- FastClick -->
	<script src="../../plugins/fastclick/fastclick.min.js"></script>
	<!-- AdminLTE App -->
	<script src="../../dist/js/app.min.js" type="text/javascript"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="../../dist/js/demo.js" type="text/javascript"></script>
	<!-- page script -->
	<script type="text/javascript">
		$(function () {
			"use strict";

        // AREA CHART
        var area = new Morris.Area({
        	element: 'revenue-chart',
        	resize: true,
        	data: [
        	{y: '2011 Q1', item1: 2666, item2: 2666},
        	{y: '2011 Q2', item1: 2778, item2: 2294},
        	{y: '2011 Q3', item1: 4912, item2: 1969},
        	{y: '2011 Q4', item1: 3767, item2: 3597},
        	{y: '2012 Q1', item1: 6810, item2: 1914},
        	{y: '2012 Q2', item1: 5670, item2: 4293},
        	{y: '2012 Q3', item1: 4820, item2: 3795},
        	{y: '2012 Q4', item1: 15073, item2: 5967},
        	{y: '2013 Q1', item1: 10687, item2: 4460},
        	{y: '2013 Q2', item1: 8432, item2: 5713}
        	],
        	xkey: 'y',
        	ykeys: ['item1', 'item2'],
        	labels: ['Item 1', 'Item 2'],
        	lineColors: ['#a0d0e0', '#3c8dbc'],
        	hideHover: 'auto'
        });

        // LINE CHART
        var line = new Morris.Line({
        	element: 'line-chart',
        	resize: true,
        	data: [
        	{y: '2011 Q1', item1: 2666},
        	{y: '2011 Q2', item1: 2778},
        	{y: '2011 Q3', item1: 4912},
        	{y: '2011 Q4', item1: 3767},
        	{y: '2012 Q1', item1: 6810},
        	{y: '2012 Q2', item1: 5670},
        	{y: '2012 Q3', item1: 4820},
        	{y: '2012 Q4', item1: 15073},
        	{y: '2013 Q1', item1: 10687},
        	{y: '2013 Q2', item1: 8432}
        	],
        	xkey: 'y',
        	ykeys: ['item1'],
        	labels: ['Item 1'],
        	lineColors: ['#3c8dbc'],
        	hideHover: 'auto'
        });

        //DONUT CHART
        var donut = new Morris.Donut({
        	element: 'sales-chart',
        	resize: true,
        	colors: ["#3c8dbc", "#f56954", "#00a65a"],
        	data: [
        	{label: "Download Sales", value: 12},
        	{label: "In-Store Sales", value: 30},
        	{label: "Mail-Order Sales", value: 20}
        	],
        	hideHover: 'auto'
        });
        //BAR CHART
        var bar = new Morris.Bar({
        	element: 'bar-chart',
        	resize: true,
        	data: [
        	{y: '2006', a: 100, b: 90},
        	{y: '2007', a: 75, b: 65},
        	{y: '2008', a: 50, b: 40},
        	{y: '2009', a: 75, b: 65},
        	{y: '2010', a: 50, b: 40},
        	{y: '2011', a: 75, b: 65},
        	{y: '2012', a: 100, b: 90}
        	],
        	barColors: ['#00a65a', '#f56954'],
        	xkey: 'y',
        	ykeys: ['a', 'b'],
        	labels: ['CPU', 'DISK'],
        	hideHover: 'auto'
        });
    });
</script>



</body>
<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url ('asset/lib/jquery/jQuery-2.1.4.min.js'); ?>"></script>
<!-- jQuery UI 1.11.2 -->
<script src="<?php echo base_url ('asset/lib/jqueryui/jquery-ui-1.10.3.min.js');?>"></script>
<script src="<?php echo base_url ('asset/lib/bootstrap/js/bootstrap.min.js');?>"></script>

<!-- Slimscroll -->
<script src="<?php echo base_url ('asset/lib/slimScroll/jquery.slimscroll.min.js');?>"></script>

<!-- FastClick -->
<script src="<?php echo base_url ('asset/lib/fastclick/fastclick.min.js');?>"></script>

<!-- AdminLTE App -->
<script src="<?php echo base_url ('asset/lib/adminlte/js/app.js');?>"></script>

</html>