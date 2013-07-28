<?
	session_start(); 
	$err = 0;
	if(isset($_REQUEST['function'])) $function = $_REQUEST['function'];
	else $err = 1;
	if(isset($_REQUEST['date'])) $date = $_REQUEST['date'];
	else $err = 1;
	
	if(!$err)
	{ 
		if($function == 'start')
		{ 
			if(isset($_SESSION['start']))
			{ 
				$start = $_SESSION['start'];
				$start = $date;
				$_SESSION['start'] = $start;
			}
			else
			{ 
				// no previous call
				$start = $date;
				$_SESSION['start'] = $start;
			}
		}
		elseif($function  == 'end')
		{
			if(isset($_SESSION['end']))
			{ 
				$_SESSION['end'] = $date;
			}
			else
			{ 
				// no previous call
				$_SESSION['end'] = $date;
			}
		}
		elseif($function == 'clear')
		{
			$start = $_SESSION['start'];
			$start = "";
			$_SESSION['start'] = $start;
			
			$end = $_SESSION['end'];
			$end = "";
			$_SESSION['end'] = $end;
		}
	}
	
?>
