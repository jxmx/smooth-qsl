<?php

function is_module_installed($module){
    if(extension_loaded($module)){
        return "Module {$module} is installed";
    } else {
        return "Module {$module} is NOT installed. This is a required module!";
    }
}

function check_install(){
    print("<h4>Testing PHP Modules</h4>");
    print("<ul>");
    $reqd_modules = [ "PDO" , "pdo_mysql" , "imagick",
        "pcre", "date" ];
    foreach($reqd_modules as $module){
        printf("<li>%s</li>", is_module_installed($module));
    }
    print("</ul><hr>");

     print("<h4>Testing Configuration</h4>");
    print("<ul>");
    $config_file = file_get_contents(__DIR__ . "/config.php");
    try{
        token_get_all($config_file);
        print("<li>Syntax of config.php is OK</li>");
    } catch(ParseError $e){
        print("Syntax of config.php is NOT OKAY:<br>" . $e->getMessage());
    }

    print("<li>Try load and bootstrap config. Errors after this " .
        "line indicate an error in config.php that prevents the app from starting " .
        "and the app will not continue until fixed.</li>");
    require_once(__DIR__ . "/lib/include.php");
    print("<li>Config loaded successfully</li>");

    print("<li>Testing database connectivity. If there is an error after this line the database is not setup properly.</li>");
    try{
        $qry = "SELECT COUNT(qsoid) FROM qsos";
        $stmt = $db->pdo()->prepare($qry);
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e){
        print($e->getMessage());
        return;
    }

    $files = [ $qsl_template, $qsl_template_multi ];
    foreach($files as $f){
        if (!file_exists($f)) {
            echo "<li>Missing QSL card template file $f... stopping.</li>";
            return;
        } elseif (!is_readable($qsl_template)) {
            echo "<li>Unreadable QSL card template file $f (check file perms)... stopping.</li>";
            return;
        } else {
            echo "<li>QSL card template file $f is good</li>";
        }
    }

    $test = __DIR__ . '/cards/._write_test_'.uniqid();
    if (@file_put_contents($test, 'ok') !== false) {
        unlink($test);
        echo "<li>Directory ./cards is writable</li>";
    } else {
        echo "<li>Directory ./cards is NOT writable... stopping.</li>";
        return;
    }
    print("</ul><hr>");

    print("<h4>Fonts Available</h4>");

    $imagick = new Imagick();
    $fonts = $imagick->queryFonts();

    // Sort for nicer output
    sort($fonts);

    // Split into 3 roughly equal chunks
    $chunks = array_chunk($fonts, ceil(count($fonts) / 3));
    ?>
    <div class="row">
        <?php foreach ($chunks as $chunk): ?>
            <div class="col-md-4">
                <ul class="list-unstyled">
                    <?php foreach ($chunk as $font): ?>
                        <li><?php echo htmlspecialchars($font); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Install Test | Firefly QSL</title>
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
<main>
	<div class="container">
		<div class="row mt-5">
			<div class="col-12 shadow rounded-3">
                <h2>Installation Check</h2>
                <hr>
                <?php check_install(); ?>
            </div>
        </div>
    </div>
</main>

<footer class="footer mt-auto py-1 ff-footer">
	<div class="container">
		<div class="row d-flex ff-footerbar p-2">
			<div class="col-6">
				<i>Powered by Firefly QSL (formerly SmoothQSL)<br>by Jason McCormick N8EI -
				<a href="https://github.com/jxmx/smooth-qsl" tabindex="-1">GitHub</a>
				</i>
			</div>
			<div class="col-6 text-end">
                &nbsp;
			</div>
		</div>
	</div>
</footer>

</div> <!-- closes ff-wrapper -->
</body>
</html>