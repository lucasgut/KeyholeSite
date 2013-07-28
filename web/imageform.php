<!DOCTYPE HTML>
<html>
	<body>
		<?
			session_start();
			include("includes/functions.php");

			$news_id = $_GET['news_id'];
		?>

		<div class="imageform">
			<form action="addimage.php" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="form-overlay" class="insertcontent">
				<fieldset>
					<input name="news_id" value="<?php echo $news_id; ?>" type="hidden" />
					<input name="MAX_FILE_SIZE" value="1048576" type="hidden">
					<input name="image" accept="image/jpeg" type="file">
					<input type="submit" name="submit" value="Add" id="submit" class="submit all-rounded" />
				</fieldset>
			</form>
		</div>
		
	</body>
</html>

