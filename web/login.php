<?
	session_start();
	ob_start();
	include("includes/functions.php");

	if (!isset($_POST['username'])) $er='1';
		else $username = $_POST['username'];
	if (!isset($_POST['password'])) $er='1';
		else $password = $_POST['password'];
	if($er==1)
	{
		header("location: index.php?error=Please input username and password");
	}
	else
	{
		$chars = preg_split("/^[a-zA-Z0123456789 ]+$/", $password, -1, PREG_SPLIT_NO_EMPTY);
		$i = 0;
		$ok = 1;
		while($chars[$i])
		{  
			$char=$chars[$i];
		    $x = checkvaliddata($char);
			if($x==0) $ok = 0;
			$i++;
		}
	
		$chars = preg_split("/^[a-zA-Z0123456789 ]+$/", $username, -1, PREG_SPLIT_NO_EMPTY);
		$i = 0;
		while($chars[$i])
		{
			$char=$chars[$i];
			$x = checkvaliddata($char);
			if($x==0) $ok = 0;
			$i++;
		}
	
		if($ok==1)
		{
			$password = md5($password);
			$sql=mysql_query("SELECT * FROM Users WHERE username='$username' AND password='$password'");
			$login_check=mysql_num_rows($sql);
			if($login_check>0)
			{
				while($row = mysql_fetch_array($sql)) 
				{
	   				foreach( $row AS $key => $val )
						{
	       					$$key = stripslashes( $val );
	    				}
					$_SESSION['loggedin'] = '';
					$_SESSION['username'] = $username;

					ob_end_clean();
					header("location: index.php?msg=Logged in succesfully.");
	    		}
			}
			else 
			{
	        	ob_end_clean();
	        	header("location: index.php?error=Invalid login data.");
			}
		}
		else
		{
	        header("location: index.php?error=Hacking attempt!");
		}
	}
?>
