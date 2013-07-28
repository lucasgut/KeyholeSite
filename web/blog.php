<? session_start(); ?>
<!DOCTYPE HTML>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/datepicker.css" type="text/css" media="screen" title="no title" charset="utf-8">
		
		<script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/date.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/datepicker.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/sprinkle.js" type="text/javascript" charset="utf-8"></script>
	</head>
	<body>
		<? include("includes/functions.php");
		   initiateLastID(); // added 25th of January
		?>
		
		<div id="wrapper">
			<section class="btlft-rounded" >
				<h1>
					Selected news:
				</h1>
				<details>
					Filters used: <span class="categories not-selected">categories</span><span class="daterange not-selected">date range</span>
				</details>
				
				<?
				$msg = $error = "";
				$msg=$_REQUEST['msg'];
				$error=$_REQUEST['error'];
				if($msg!="")
				{
					?>
					<article class="no-hover message display">
						<h1><?=$msg?></h1>
					</article>
					<?
				}
				if($error!="")
				{
					?>
					<article class="no-hover error display">
						<h1><?=$error?></h1>
					</article>
					<?
				}
				
				?>
				
				<? list_news(); ?>

			</section>
		
			<aside class="btrght-rounded"><!-- The aside will hold the filters in place: date range and categories -->
				<ul><!-- This UL will hold our categories -->
					<li class="title">Browse by category</li>
					<? list_categories("") ?>
				</ul>
				<div>
					<form>
						<h1>Browse by date range</h1>
						<fieldset>
							<label for="start_date">Start date: </label>
							<input type="text" name="start_date" value="<?=$_SESSION['start']?>" id="start_date" class="date-pick">
							<label for="end_date">End date: </label>
							<input type="text" name="end_date" value="<?=$_SESSION['end']?>" id="end_date" class="date-pick">
						</fieldset>
					</form>
					<span>Clear filter</span>
				</div>
			</aside>
		</div>
		
		<!-- used for javascript UI, modals etc-->
		
		<article class="no-hover hidden" id="newdata">
			<h1>New articles in the system. Click here to view them</h1>
		</article>
		
	</body>
</html>
