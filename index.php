<?php
    
/***************************************************************************************
    Zalozeni session pro uchovani jmena prihlaseneho uzivatele 
*/
        session_start();

/***************************************************************************************
    Pripojeni ostatnich PHP souboru
*/
        require 'config/config.inc.php';
        require 'config/functions.inc.php';
        require 'application/core/app.class.php';			
        require 'application/core/db.class.php';
        require 'application/core/twig.class.php';
        require 'application/core/auta.class.php';
        require 'application/core/zakaznik.class.php';
        require 'application/core/servis.class.php';
        require_once 'view/twig-master/lib/Twig/Autoloader.php';

/***************************************************************************************
    Vytvoreni instanci
*/
        $twig = new twig();
        $app = new app();
        $app->Connect();
        $db_connection = $app->GetConnection();
        $auta = new auta($db_connection);
        $zakaznik = new zakaznik($db_connection);
        $servis = new servis($db_connection);
        $template_params = array();
    



if(isset($_GET["page"])) {
        $tempPom = "error.htm";
  
        if(isset($_SESSION['username']) && $_SESSION['username'] == "Administrator")
        {
             /***************************************************************************************
            Sablona pro ADMIN AUTA
        */
        if($_GET["page"] == "admin_auta.htm")
        {
            $data = $auta->vypisVsechVozu();
            $template_params["data"] = $data; 
            $tempPom = "admin_auta.htm";
        }
        
        /***************************************************************************************
            Sablona pro ADMIN HISTORIE
        */
        if($_GET["page"] == "admin_historie.htm")
        {
            $data = $auta->historie();
            $template_params["data"] = $data; 
            $tempPom = "admin_historie.htm";
        }
    
        /***************************************************************************************
            Sablona pro ADMIN SERVIS
        */
        if($_GET["page"] == "admin_servisy.htm")
        {
            $data = $servis->vypisServisu();
            $template_params["data"] = $data;
            $tempPom = "admin_servisy.htm";
        }
        
        /***************************************************************************************
            Sablona pro PRIDANI AUTA
        */
        if($_GET["page"] == "pridat_auto.htm")
        {
            $tempPom = "pridat_auto.htm";
        }
    
            
            
                 }
        /***************************************************************************************
            Sablona pro PROFIL
        */
        
        if($_GET["page"] == "profil.htm")
        {
            $template_params["username"] = $_SESSION["username"];
            $info = $zakaznik->info_user($_SESSION['username']);
            $template_params["jmeno"] = $info["jmeno"];
            $template_params["prijmeni"] = $info["prijmeni"];
            $template_params["bydliste"] = $info["bydliste"];
            
            $data = $zakaznik->zapujcenaAuta($_SESSION["username"]);
            $template_params["data"] = $data;
            $tempPom = "profil.htm";
        }
        
        /***************************************************************************************
            Sablona pro PROHLIZENI
        */
        if($_GET["page"] == "prohlizeni.htm")
        {
            $data = $auta->vypisVozu();
            $template_params["data"] = $data;
            $tempPom = "prohlizeni.htm";
        }
    
        /***************************************************************************************
            Sablona pro ZAPUJCENI
        */
        if($_GET["page"] == "zapujceni.htm")
        {
            $data = $auta->vypisVozu();
            $template_params["data"] = $data;
            $template_params["username"] = $_SESSION["username"];
            $tempPom = "zapujceni.htm";
        }
        
       
        /***************************************************************************************
            Sablona pro REGISTRACE
        */
        if($_GET["page"] == "registrace.htm")
        {
            $tempPom = "registrace.htm";
        }
    
        /***************************************************************************************
            Sablona pro PRIDANI AUTA
        */
        if($_GET["page"] == "login.htm")
        {
            $tempPom = "login.htm";
            
        }
    
        /***************************************************************************************
            Sablona pro KONTAKT - PRIHLASENEHO UZIVATELE
        */
        if($_GET["page"] == "kontakt_prihlaseny.htm")
        {
            $tempPom = "kontakt_prihlaseny.htm";
            $template_params["username"] = $_SESSION["username"];
        }
    
        /***************************************************************************************
            Sablona pro KONTAKT - NEPRIHLASENEHO UZIVATELE
        */
        if($_GET["page"] == "kontakt_neprihlaseny.htm")
        {
            $tempPom = "kontakt_neprihlaseny.htm";
        }
        
        /***************************************************************************************
            Kliknutim na odhlasit se prejde na sablonu prihlaseni
        */
        if($_GET["page"] == "odhlasit")
        {
            $_SESSION["username"] = "";
            unset($_SESSION["username"]);
            header("Location: index.php");
        }    
    }
else{
    
    /***************************************************************************************
        Kdyz je nastavena session - je prihlaseny nejaky uzivatel
    */
    if(isset($_SESSION['username']))
    {
        /***************************************************************************************
            Kdyz uzivatel je Administrator
        */
        if($_SESSION['username'] == "Administrator")
        {
            $tempPom = "admin_auta.htm";
            $template_params["username"] = $_SESSION['username'];
            $data = $auta->vypisVsechVozu();
            $template_params["data"] = $data;     
        }
        /***************************************************************************************
            Kdyz uzivatel je zakaznik
        */
        else{
            $tempPom = "zapujceni.htm";
            $data = $auta->vypisVozu();
            $template_params["data"] = $data;
            $template_params["username"] = $_SESSION["username"];
            
        }
    }
    /***************************************************************************************
        Kdyz je není nastavena session - není prihlaseny uzivatel
    */
    else{
        $tempPom = "login.htm";
    }
}

/***************************************************************************************
    Posluchac tlacitka na prihlaseni
*/
if(isset($_POST['login'])) {
    if(isset($_POST['user'], $_POST['pass'])){
            
            $prihlaseny_zakaznik = $zakaznik->login($_POST['user'], $_POST['pass']);
            
            if($prihlaseny_zakaznik['login_name'] == null)
            {
                    $tempPom = "login.htm";
                    $template_params["errorLogin"] = "Spatne jmeno nebo heslo!";            
            }
            else{
                    $_SESSION['username'] = $prihlaseny_zakaznik['login_name'];
            
                    if($_SESSION['username'] == "Administrator")
                    {
                        $tempPom = "admin_auta.htm";
                        $template_params["username"] = $_SESSION['username'];
                        $data = $auta->vypisVsechVozu();
                        $template_params["data"] = $data;
                    }
                    else{
                        $tempPom = "zapujceni.htm";
                        $template_params["data"] = $auta->vypisVozu();
                        $template_params["username"] = $_SESSION['username'];
                    }       
            } 
    }
}    

/***************************************************************************************
    Posluchac tlacitka na registraci uzivatele
*/
if(isset($_POST["registrace"]))
{
    if(isset($_POST['user'], $_POST['pass'])){        
            $zakaznik->registrace($_POST['jmeno'], $_POST['prijmeni'], $_POST['bydliste'], $_POST['user'], $_POST['pass']);
            $tempPom = "login.htm";
            $template_params["errorLogin"] = "Registrace proběhla úspěšně!";    
    } 
    else{
            $tempPom = "login.htm";
            $template_params["errorLogin"] = "Registrace se nepovedla!";   
        }
}

/***************************************************************************************
    Posluchac tlacitka na zapujceni automobilu
*/
if(isset($_POST['zapujcit_button']))
{
    
    $user = $_SESSION['username'];
    $info = $zakaznik->info_user($user);
    $template_params["jmeno"] = $info["jmeno"];
    $template_params["prijmeni"] = $info["prijmeni"];
    $template_params["bydliste"] = $info["bydliste"];
    $template_params["username"] = $user;
    $a = $auta->vypisVozuID($_POST['id_auta']);
    $template_params["znacka"] = $a["znacka"];
    $template_params["model"] = $a["model"];
    $template_params["spz"] = $a["spz"];    
    $zakaznik->zapujceni($_POST['id_auta'], $user);
    $tempPom = "objednavka.htm";
    
}

/***************************************************************************************
    Posluchac tlacitka na zobrazeni sablony, ve ktere je
    formular na vyplneni udaju o voze.
*/
if(isset($_POST['pridat_auto']))
{
    $tempPom = "pridat_auto.htm";
}

/***************************************************************************************
    Posluchac tlacitka na pridani vozidla do databaze
*/
if(isset($_POST['pridat_auto_btn']))
{
    $auta->pridatAuto($_POST['znacka'], $_POST['model'], $_POST['typ'], $_POST['spz'], $_POST['barva'], $_POST['stav_tachometru']);
    $data = $auta->vypisVsechVozu();
    $template_params["data"] = $data; 
    $tempPom = "admin_auta.htm";
}

/***************************************************************************************
    Posluchac tlacitka na odebrani automobilu z databaze
*/
if(isset($_POST['odebrat_auto']))
{
    $auta->odebratAuto($_POST['id_auta']);
    $data = $auta->vypisVsechVozu();
    $template_params["data"] = $data; 
    $tempPom = "admin_auta.htm";
}

/***************************************************************************************
    Posluchac tlacitka na vraceni vozu od zakaznika
*/
if(isset($_POST['vratit_auto']))
{
    $zakaznik->vraceni($_POST['id_auta']);
    $data = $auta->vypisVsechVozu();
    $template_params["data"] = $data; 
    $tempPom = "admin_auta.htm";
}

/***************************************************************************************
    Posluchac tlacitka na pridani servisu k vozu
*/
if(isset($_POST['pridat_servis_btn']))
{
    $servis->pridatServis($_POST['datum'], $_POST['komentar'], $_POST['id_auta']);
   
    $data = $servis->vypisServisu();
    $template_params["data"] = $data;              
    $tempPom = "admin_servisy.htm";
}

/***************************************************************************************
    Posluchac tlacitka na zobrazeni sablony, ve ktere 
    je formular na pridani servisu
*/
if(isset($_POST['pridat_servis']))
{
    $data = $auta->vypisVozuID($_POST['id_auta']);
    $template_params["id_auta"] = $data['id_auta'];
    $template_params["znacka"] = $data['znacka'];
    $template_params["model"] = $data['model'];
    $tempPom = "pridat_servis.htm";
}

/***************************************************************************************
    Posluchac tlacitka na odebrani uzivatele z databaze
*/
if(isset($_POST['delete_user']))
{
    $zakaznik->delete_user($_SESSION["username"]);
    $tempPom = "login.htm";
    $template_params["errorLogin"] = "Uživatel odstraněn!";
}

/***************************************************************************************
    Zde se vola funkce view, do ktere vstupuje pole paramatru, ktere se 
    maji vypsat v sablone, ktera je v promenne tempPom
*/
$twig->view($template_params, $tempPom);


?>