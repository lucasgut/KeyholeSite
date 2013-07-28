<!DOCTYPE HTML>
<html>
	<body>
		Hello chaps!
		<?
			session_start();
			include("includes/functions.php");
		?>

		<div class="editform">
			<form action="editnews.php" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="form-overlay" class="insertcontent">
				<fieldset>
					<label for="edittitle">Title : </label>
					<input type="text" name="edittitle" value="" id="edittitle" class="edittitle all-rounded" />
					<label for="editformcategory">Category :</label>
					<select name="editformcategory" id="editformcategory" size="1" class="editcategory">
						<? list_categories("select-options") ?>
					</select>
					<label for="editbody">Body text :</label>
					<textarea name="editbody" rows="8" cols="40" class="editbody all-rounded"></textarea>
					<input type="hidden" name="editid" value="" id="editid" class="editid hidden" />
					<input name="MAX_FILE_SIZE" value="102400" type="hidden">
					<input name="image" accept="image/jpeg" type="file">
					<input type="submit" name="submit" value="Edit news" id="submit" class="submit all-rounded" />
				</fieldset>
			</form>
		</div>
		
	</body>
</html>

