<footer class="footer mt-auto py-1 ff-footer">
	<div class="container">
		<div class="row d-flex ff-footerbar p-2">
			<div class="col-6">
				<i>Powered by Firefly QSL (formerly SmoothQSL)<br>by Jason McCormick N8EI -
				<a href="https://github.com/jxmx/smooth-qsl" tabindex="-1">GitHub</a>
				</i>
			</div>
			<div class="col-6 text-end">
			<p>This page load
<?php
if(random_int(1,4) > 3){
	include("qslmaint.php");
	print("ran");
} else {
	print("did not run");
}
?>
			maintenance.</p>
			</div>
		</div>
	</div>
</footer>

</div> <!-- closes ff-wrapper -->
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/smoothqsl.js"></script>
<?php
	if( isset($ff_additional_scripts) ){
		print($ff_additional_scripts);
	}
?>
</body>
</html>
