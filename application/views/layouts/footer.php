		<footer>
			<div class="container">Copyright &copy <?php echo date("Y", time()); ?>, iQuTech </div>
		</footer>
	</div>
  </body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>