<? session_start(); ?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Keyhole Caving Club - St.Albans</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/datepicker.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/nav.css">
		
		<script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/date.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/datepicker.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/sprinkle.js" type="text/javascript" charset="utf-8"></script>
	    <script src="js/modernizr.js"></script>
	</head>
	<body>
		<? include("includes/functions.php");
		   initiateLastID(); // added 25th of January
		?>

		<!-- Navigation menu -->
		<script>
			(function($){
				
				//cache nav
				var nav = $("#topNav");
				
				//add indicator and hovers to submenu parents
				nav.find("li").each(function() {
					if ($(this).find("ul").length > 0) {
						$("<span>").text("^").appendTo($(this).children(":first"));

						//show subnav on hover
						$(this).mouseenter(function() {
							$(this).find("ul").stop(true, true).slideDown();
						});
						
						//hide submenus on exit
						$(this).mouseleave(function() {
							$(this).find("ul").stop(true, true).slideUp();
						});
					}
				});
			})(jQuery);
		</script>

		<!-- iframe containing subpage -->
		<script language="JavaScript">
			function changeIframe(htmlElement) {
		  		document.getElementById('wrapper').innerHTML = "<iframe src='" + htmlElement.href + "' class='restricted'></iframe>";
			}
		</script>
	
		<header class="top-rounded">
			Keyhole Caving Club - St.Albans<!-- The title of our app -->
		</header>

		<nav id="topNav">
        	<ul>
            	<li><a title="Club" href="club.html" onclick="changeIframe(this); return false;">Club</a></li>
            	<li><a title="Blog" href="blog.php" onclick="changeIframe(this); return false;">Blog</a></li>
            	<li class="last"><a title="Contact" href="contact.html" onclick="changeIframe(this); return false;">Contact</a></li>
          	</ul>
        </nav>

		<div id="wrapper" height="100%">
			<iframe src='club.html' width='100%' height='100%' scrolling='auto' class='restricted'></iframe>
		</div>
		
		<footer>
			<? if(!isset($_SESSION['loggedin'])) { ?><a rel="login" name="modal">Admin login</a> <? } else { ?>You are logged in. You can <A  href="#addnews" rel="addnews" name="modal">add news</a> or <a href="logout.php" target="_self">logout</a><? } ?>
		</footer>
		
		<!-- used for javascript UI, modals etc-->
		
		<article class="no-hover hidden" id="newdata">
			<h1>New articles in the system. Click here to view them</h1>
		</article>
		
		<div id="overlay-mask">
			&nbsp;
		</div>
		<div id="overlay-content" class="all-rounded">
			<div>				
			</div>
			<a class="close bottom-rounded">Click to close</a>
		</div>
		
		<div class="login hidden">
			<form action="login.php" method="post" accept-charset="utf-8" id="form-overlay">
				<fieldset>
					<label for="name">Username : </label>
					<input type="text" name="username" value="" id="username" class="all-rounded"/>
					<label for="password">Password :</label>
					<input type="password" name="password" value="" id="password" class="all-rounded">
					<input type="submit" name="submit" value="Log me now" id="submit" class="submit all-rounded" />
				</fieldset>
			</form>
		</div>
		
		<div class="addnews hidden">
			<form action="addnews.php" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="form-overlay">
				<fieldset>
					<label for="title">Title : </label>
					<input type="text" name="title" value="" id="title" class="all-rounded"/>
					<label for="formcategory">Category :</label>
					<select name="formcategory" id="formcategory" size="1">
						<? list_categories("select-options") ?>
					</select>
					<label for="body">Body text :</label>
					<textarea name="body" rows="8" cols="40" class="all-rounded"></textarea>
					<input name="MAX_FILE_SIZE" value="1048576" type="hidden">
					<input name="image" accept="image/jpeg" type="file">
					<input type="submit" name="submit" value="Add news" id="submit" class="submit all-rounded" />
				</fieldset>
			</form>
		</div>
		
		<div class="editform hidden">
			<form action="editnews.php" method="post" accept-charset="utf-8" id="form-overlay" class="insertcontent">
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
					<input type="submit" name="submit" value="Edit news" id="submit" class="submit all-rounded" />
				</fieldset>
			</form>
		</div>

		<div class="datefilter hidden">
			<form>
				<fieldset>
					<label for="start_date">Start date: </label>
					<input type="text" name="start_date" value="<?=$_SESSION['start']?>" id="start_date" class="date-pick">
					<label for="end_date">End date: </label>
					<input type="text" name="end_date" value="<?=$_SESSION['end']?>" id="end_date" class="date-pick">
				</fieldset>
			</form>
		</div>
		
	</body>
</html>
