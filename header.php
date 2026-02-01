<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php print($ff_page_title); ?> | Firefly QSL</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-icons-1.13.1/bootstrap-icons.min.css" rel="stylesheet">
	<?php
		if(isset($ff_additional_css)){
			print($ff_additional_css);
		}
	?>
	<link href="css/qsl.css" rel="stylesheet">
    <link rel="icon" href="img/Firefly_Logo_FavIco_96sq.png">
    <script src="js/header.js"></script>
</head>
<body>
<div class="ff-page-wrapper">
<header class="shadow-sm bg-body pb-2 mb-2">
    <div class="container">
        <div class="row">

            <!-- left box -->
            <div class="col-2 d-flex align-items-center text-start">
                <a href="index.php">
                    <img src="img/Firefly_Logo_150_100.webp" class="header-logo" alt="Firefly Logger">
                </a>
            </div>

            <!-- center main box -->
            <div class="col-8 d-flex align-items-center justify-content-center text-center">
            <?php print($ff_header_content); ?></div>

            <!-- right box -->
            <div class="col-2 d-flex align-items-center justify-content-end">
                <ul class="nav nav-pills f-nav-header">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-list nav-bi-big"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="load.php">Load Log</a></li>
                            <li><a class="dropdown-item" href="qsladifout.php">ADIF Export</a></li>
                            <li><a class="dropdown-item" href="#">Sample Card</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-moon-stars-fill nav-bi-big"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button id="theme-light" class="dropdown-item">
                                <i class="bi bi-sun-fill"></i>&nbsp;Light</button>
                            </li>
                            <li><button id="theme-dark" class="dropdown-item">
                                <i class="bi bi-moon-stars-fill"></i>&nbsp;Dark</button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</header>
