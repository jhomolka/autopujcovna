<?php 

class zakaznik extends db
{
	// Konstruktor
	public function zakaznik($connection)
	{
		$this->connection = $connection;	
	}
	
    /*  Funkce pro odstraneni uzivatele.
            Pokud se uzivatel zadany vstupnim
            parametrem rovna hodnote ve sloupci
            login_name v tabulce zakaznik, tak
            dojde k jeho odstraneni.
    */      
    public function delete_user($user)
    {
        $wheres = array(array('column'=>'login_name', 'value'=>$user, 'symbol'=>'=')); 
        $this->DBDelete('zakaznik', $wheres);
    }
    
    /*  Funkce pro prihlaseni.
            Pokud se rovna heslo a uzivatel
            zadany vstupnim parametrem s 
            hodnotama ve sloupci login_name
            a login_passw, tak dojde k vyberu
            jedne polozky z tabulky zakaznik.
    */
	public function login($user, $pass)
	{    
        $wheres = array(array('column'=>'login_name', 'value'=>$user, 'symbol'=>'='),array('column'=>'login_passw', 'value'=>$pass,      'symbol'=>'='));

        return $this->DBSelectOne('zakaznik', 'login_name, login_passw', $wheres, 'limit 1');
	}
    
    /*   Funkce pro zjisteni informaci o 
         prihlasenem uzivateli.
    */
    public function info_user($user)
	{    
        $wheres = array(array('column'=>'login_name', 'value'=>$user, 'symbol'=>'='));
        return $this->DBSelectOne('zakaznik', "jmeno, prijmeni, bydliste", $wheres, '');
	}
    
    /*  Funkce, ktera slouzi k pridani 
        zaznamu do tabulky zakaznik.
        Dojde k pridani radku s hodnotama
        zadanyma vstupnim parametrem. Po 
        vytvoreni uzivatele dojde k pridani
        pristupoveho prava na HOST.
    */     
    public function registrace($jmeno, $prijmeni, $bydliste, $user, $pass)
    {
        $item = array(array('column'=>'jmeno', 'value'=>$jmeno), 
                        array('column'=>'prijmeni', 'value'=>$prijmeni),
                        array('column'=>'bydliste', 'value'=>$bydliste),
                        array('column'=>'login_name', 'value'=>$user),
                        array('column'=>'login_passw', 'value'=>$pass));
        $id = $this->DBInsertExpanded('zakaznik', $item);
        
        $item1 = array(array('column'=>'id_zakaznik', 'value'=>$id),
                       array('column'=>'pravo', 'value'=>"host"));
        $this->DBInsertExpanded('pristupova_prava', $item1); 
    }
    
    /*
        Funkce zapujceni slouzi k zapujceni
        automobilu s id_auta, ktere se zada
        jako vstupni parametr prihlasenemu 
        uzivateli. Nejprve se zmeni hodnota
        ve sloupci pujceno v tabulce auta na
        ANO, pote se vybere id_zakaznika, ktery
        je prihlaseny a nasledne se prida do
        tabulky auta_has_zakaznik id_auta do
        sloupce id_auta, id_zakaznika do sloupce
        id_zakaznika a aktualni datum do sloupce
        datum_zapujceni.
    */
    public function zapujceni($id_auta, $user)
    {
        $pujceno = "ANO";
        $this->updatePujceno($pujceno, $id_auta);
        $wheres = array(array('column'=>'login_name', 'value'=>$user, 'symbol'=>'='));
        $p = $this->DBSelectOne('zakaznik', "id_zakaznika", $wheres, '');
        $datum = Date("Y/m/j", Time());
        $pom = array(array('column'=>'id_auta', 'value'=>$id_auta), 
                     array('column'=>'id_zakaznika', 'value'=>$p['id_zakaznika']),
                     array('column'=>'datum_zapujceni', 'value'=>$datum));  
        $this->DBInsertExpanded('auta_has_zakaznik', $pom); 
    }
    
    /*
        Funkce na vraceni automobilu.
        Tuto moznost ma pouze administrator.
        Funkce pouze nastavi na zadany automobil
        do sloupce pujceno hodnotu NE.
    */
    public function vraceni($id_auta)
    {
        $pujceno = "NE";
        $this->updatePujceno($pujceno, $id_auta);
    }
    
    /*
        Funkce na vypis zapujcenych automobilu
        pro konkretniho uzivatele, jehoz id je 
        zadane do funkce.
    */
    public function zapujcenaAuta($user)
    {
        $wheres = array(array('column'=>'login_name', 'value'=>$user, 'symbol'=>'='));
        $auta = $this->DBSelectAll('auta a inner join auta_has_zakaznik r on a.id_auta = r.id_auta inner join zakaznik z on z.id_zakaznika = r.id_zakaznika', 'znacka, model, datum_zapujceni, spz', $wheres, '');
        return $auta;   
    }
        

}


?>