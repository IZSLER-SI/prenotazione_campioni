<?php   
include_once(dirname(dirname(__FILE__)).'/header.php');
$filename = '../config.php';
$file = file_exists($filename);
if($file){
	if(filesize($filename) > 0){
	}
	else{
		echo 'error_file';//header('Location:'.SITE_URL.'ct_install.php');
	}
}

include(dirname(dirname(__FILE__)).'/objects/class_connection.php');
include(dirname(dirname(__FILE__))."/objects/class_setting.php");
include(dirname(dirname(__FILE__))."/objects/class_version_update.php");
session_start();
$con = new prenotazione_campioni_db();
$conn = $con->connect();

include(dirname(dirname(__FILE__)).'/utility.php');
$variabili = get_variabili();
$aad = json_decode($variabili['active_directory']['contenuto'],true);
/**
 * 
 * Per poter legare il login tramite active directory al cms, bisogna agganciarsi alle key in sessione che vengono popolate dal login normale, in modo da non dover ricreare tutto il processo da zero
 * 
 */
if(isset($_SESSION['ct_accettazioneid']) || isset($_SESSION['ct_laboratorioid'])){
	return header('Location:'.SITE_URL."admin/calendar.php");
}
elseif(isset($_SESSION['ct_adminid'])){
	return header('Location:'.SITE_URL."admin/services.php");
}
elseif(isset($_SESSION['ct_login_user_id'])){
    return header('Location:'.SITE_URL."admin/user-profile.php");
}
check();
function check(){
    global $aad;
    require_once '../vendor/autoload.php';
    include((dirname(dirname(__FILE__))) . "/objects/class_login_check.php");
    global $conn;
    $provider = new TheNetworg\OAuth2\Client\Provider\Azure([
        'clientId'          => $aad['clientId'],
        'clientSecret'      => $aad['clientSecret'],
        'redirectUri'       => $aad['redirectUri'],
    ]);
    // Just do basic read of /me endpoint 
    $provider->scope            = ['offline_access User.Read'];
    $provider->urlAPI           = "https://graph.microsoft.com/v1.0/";
    $provider->tenant           = $aad['tenant'];
    $provider->authWithResource = false;
    if (!isset($_GET['code'])){
        $authUrl                 = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
        //hack per i redirect izsler
		echo "<script>location.href='".$authUrl."';</script>"; 
       //header('Location: ' . $authUrl);
        die();
    } elseif(empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
        exit("State mismatch, ending auth");
    } else {
        try {
		$token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code'],
            'resource' => 'https://graph.microsoft.com',
        ]);
            $me = $provider->get("me", $token);
            $name = $me['userPrincipalName'];
            $objlogin = new prenotazione_campioni_login_check();
            $objlogin->conn = $conn;
            $login = $objlogin->checkAdd($name); 
            if($login == true){
                log_data('Aad login', $name);
                if(isset($_SESSION['ct_accettazioneid']) || isset($_SESSION['ct_laboratorioid'])){
                    return header('Location:'.SITE_URL."admin/calendar.php");
                }
                elseif(isset($_SESSION['ct_adminid'])){
                    return header('Location:'.SITE_URL."admin/services.php");
                }
                elseif(isset($_SESSION['ct_login_user_id'])){
                    return header('Location:'.SITE_URL."admin/user-profile.php");
                }
            }else{
                echo "Errore, utente non autorizzato. Contattare l'assistenza per abilitare l'accesso <a href='mailto:accettazione@izsler.it'>accettazione@izsler.it</a>.";
            }
            
        } catch (Exception $e) {
            exit('Failed to call the me endpoint of MS Graph.'.$e);
        }
    }
}
?>