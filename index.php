<?php
// Awesome Facebook Application
//
require_once(LIBRARY_PATH . "/facebook-php-sdk/src/facebook.php");
require_once(LIBRARY_PATH . "/functions.php");
require_once(INCLUDES_PATH . "/Ranking.php");
require_once(INCLUDES_PATH . "/LiveShoutManager.php");

// Create our Application instance.
$facebook = new Facebook(array(
  'appId' => '112594605483802',
  'secret' => '76b71bb5b88e54c54b9b6dd942f0b2c3',
  'cookie' => true,
  ));

$SControl = SessionControl::getInstance();

$pid = SessionControl::getFromGET("pid");
$pagetitle = "";

switch ($pid) {
	case "account":
		if ($SControl->is_session_valid())
		{
			$pagetitle = "Configurações da Conta";
			$pagetitle .= " | ";
		}
		else
		{
			$pagetitle = "Faça o Login";
			$pagetitle .= " | ";
		}
		break;
	case "party":
		$partyid = SessionControl::getFromGET("partyid");
		if (!$partyid || !is_numeric($partyid)) {
			$pagetitle = "Festa não encontrada";
			$pagetitle .= " | ";
		}
		else {
			$party = new Party($partyid);
			if ($party->getInvalid() || !$party->getDate())
				$pagetitle = "Festa não encontrada";
			else
				$pagetitle = $party->getPlace()->getName() . " - " . $party->getDate();
			$pagetitle .= " | ";
		}
		break;
	case "place":
		$placeid = SessionControl::getFromGET("placeid");
		if (!$placeid)
		{
			$pagetitle = "Lugar não encontrado";
			$pagetitle .= " | ";
		} else
		{
			$place = new Place($placeid);
			if ($place->getName())
				$pagetitle = $place->getName();
			else
				$pagetitle = "Lugar não encontrado";
			$pagetitle .= " | ";
		}
		break;
	case "contact":
		$pagetitle = "Contato";
		$pagetitle .= " | ";
		break;
	case "privacy":
		$pagetitle = "Política de Privacidade";
		$pagetitle .= " | ";
		break;
	case "about":
		$pagetitle = "Sobre";
		$pagetitle .= " | ";
		break;
	case "register":
		if (!$SControl->is_session_valid()) {
			$pagetitle = "Registre-se";
			$pagetitle .= " | ";
			break;
		}
	case "login":
		if (!$SControl->is_session_valid()) {
			$pagetitle = "Faça o Login";
			$pagetitle .= " | ";
			break;
		}
	case "rank":
	default:
		break;
}
$pagetitle .= "nightshout";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $pagetitle; ?></title>
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="stylesheet" type="text/css" href="css/ranking.css" />
<link rel="stylesheet" type="text/css" href="css/party.css" />
<link rel="stylesheet" type="text/css" href="css/place.css" />
<link rel="stylesheet" type="text/css" href="css/globalStyles.css" />
<link rel="stylesheet" type="text/css" href="css/profile.css" />

<link rel="shortcut icon" href="img/favicon.png">

<link href="css/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/jsDatePick_ltr.css" media="screen" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/lib/jquery.js"></script>
<script type="text/javascript" src="js/lib/facebox.js"></script>
<script type="text/javascript" src="js/lib/jsDatePick.jquery.js"></script>

<script type="text/javascript">
   jQuery(document).ready(function($) {
     $('a[rel*=facebox]').facebox({
       loadingImage : 'img/loading.gif',
       closeImage   : 'img/closelabel.png'
     })
   })

	$(document).ready(function() {
//	jQuery.facebox("Onde você vai hoje?");
	});

</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body>


<?php include(TEMPLATES_PATH . "/header.php");?>


<div id="container">
<div id="content">

	<div id="error_panel" style="display: block">
		<p class="error_msg">
		<?php
		if (isset($_SESSION[SessionControl::ERROR])) {
				echo $_SESSION[SessionControl::ERROR];
				unset($_SESSION[SessionControl::ERROR]);
		}
		?>
		</p>
		<p class="msg">
		<?php
		if (isset($_SESSION[SessionControl::MSG])) {
				echo $_SESSION[SessionControl::MSG];
				unset($_SESSION[SessionControl::MSG]);
		}
		?>
		</p>
	</div>

<script type="text/javascript">
	var hide_error_panel = true;
	if ($('.error_msg').html().trim() != "") {
		hide_error_panel = false;
	}
	if ($('.msg').html().trim() != "") {
		hide_error_panel = false;
	}

	if (hide_error_panel) {
		var error_panel = document.getElementById("error_panel");
		error_panel.style.display = "none";
	}
</script>

<!-- center -->
    <div id="center">

    <?php

	// Get pageid
	$pid = SessionControl::getFromGET("pid");

	switch ($pid) {
		case "account":
			if ($SControl->is_session_valid())
				include(VIEWS_PATH . "/account.php");
			else
				include(VIEWS_PATH . "/signed_out.php");
			break;
		case "party":
			include(VIEWS_PATH . "/party.php");
			break;
		case "place":
			include(VIEWS_PATH . "/place.php");
			break;
		case "about":
			include(VIEWS_PATH . "/about.php");
			break;
		case "contact":
			include(VIEWS_PATH . "/contact.php");
			break;
		case "privacy":
			include(VIEWS_PATH . "/privacy.php");
			break;
		case "profile":
			include(VIEWS_PATH . "/profile.php");
			break;
		case "user/recover":
			/* user recovery must NOT be logged in */
			if (!$SControl->is_session_valid()) {
				include(VIEWS_PATH . "/user_recover.php");
				break;
			}
		case "user/auth":
			/* needs to be logged in to be able to change password */
			if ($SControl->is_session_valid()) {
				include(VIEWS_PATH . "/user_auth.php");
				break;
			}
			else {
				echo "Necessário estar identificado para acessar esta seção.";
				break;
			}
		case "user/confirm":
			/* to confirm user e-mail */
			include(VIEWS_PATH . "/user_confirm.php");
			break;

		/* administrative stuff */
		case "admin/pictures":
			if ($SControl->is_session_valid() && $SControl->user->hasAdminLvl()) {
				include(VIEWS_PATH . "/admin_pictures.php");
				break;
			}
		case "admin/audit":
			if ($SControl->is_session_valid() && $SControl->user->hasAdminLvl()) {
				include(VIEWS_PATH . "/admin_audit.php");
				break;
			}
		/* search box */
		case "search":
			include(VIEWS_PATH . "/search.php");
			break;
		/* these cases may fallthrought to 'default', so beware: */
		case "register":
			if (!$SControl->is_session_valid()) {
				include(VIEWS_PATH . "/register.php");
				break;
			}
		case "login":
			if (!$SControl->is_session_valid()) {
				include(VIEWS_PATH . "/login.php");
				break;
			}
		case "rank":
		default:
			include(VIEWS_PATH . "/rank.php");
		break;
	}
    ?>

    </div>

    <div style="clear:both"></div>

</div>
</div>
<div style="clear:both"></div>

<?php include(TEMPLATES_PATH . "/footer.php"); ?>


</body>
</html>

