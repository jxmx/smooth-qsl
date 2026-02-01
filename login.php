<?php
/*
Copyright 2017-2026 Jason D. McCormick

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

include_once(__DIR__ . "/lib/include.php");

$errmsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if( !isset($_POST["callsign"]) || !isset($_POST["password"]) ){
        http_error_response(400,"missing authentication attributes");
    }

    if( $_POST["callsign"] === $club_call && $_POST["password"] === $club_password ){
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    [
                        'expires'  => time() - 42000,
                        'path'     => $params['path'],
                        'domain'   => $params['domain'],
                        'httponly' => $params['httponly'],
                        'samesite' => $params['samesite'] ?? 'Strict'
                    ]
                );
            }
            session_destroy();
        }

        session_start();
        session_regenerate_id(true);
        $_SESSION['callsign'] = $_POST["callsign"];
        $_SESSION['authenticated'] = true;

        $next = $_POST['nextpage'] ?? 'index.php';
        $next = urldecode($next);
        if (!preg_match('#^/[A-Za-z0-9/_\-.?=&]*$#', $next)) {
            $next = 'index.php';
        }
        http_redirect($next);
        exit;
    }

    $errmsg = <<<EOT
        <div class="alert alert-danger text-center">Invalid callsign and password</div>
    EOT;
}

$nextpage = "index.php";
if( isset($_GET['nextpage'])){
    $nextpage = $_GET['nextpage'];
}

// This is the page title in <head>>. It's followed by "| Firefly QSL"
$ff_page_title = $club_call;
$ff_header_content = "<h2>Login</h2>";

$ff_additional_scripts = <<<EOT
<script src="js/jquery-4.0.0.min.js"></script>
<script src="js/jquery.validate-1.22.0.min.js"></script>
<script src="js/login.js"></script>
EOT;

include_once("header.php");
?>
<main>
	<div class="container">
		<div class="row mt-3">
			<div class="col-12 shadow rounded-3 p-3">
                <div class="container">
                    <div class="row m-3 px-3 justify-content-center">
                        <div class="col-md-6">
                            <?php print($errmsg); ?>
                            <form name="loginbox" id="loginbox" method="post"
                                autocomplete="off" novalidate>
                                <input type="hidden" id="nextpage" name="nextpage"
                                    value="<?php echo $nextpage; ?>">
                                <label for="callsign" class="form-label mt-2">Master Callsign</label>
                                <input id="callsign" name="callsign" class="form-control mb-3"
                                    autocomplete="off" type="text">

                                <label for="password" class="form-label mt-2">Password</label>
                                <input id="password" name="password" class="form-control mb-3"
                                    autocomplete="off" type="password">

                                <button role="submit" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</main>
<?php require_once("footer.php"); ?>