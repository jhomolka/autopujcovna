<?php 

class auta extends db
{
    /*
        Konstruktor
    */
	public function auta($connection)
	{
		$this->connection = $connection;	
	}
    
    /*
        Funkce na vypis vsech automobilu.
        Nebere se ohled na to, jestli jsou 
        automobily zapujcene a nebo ne.
    */
    public function vypisVsechVozu()
	{
		$where_array = array();
		$order_by_array = array();
		$auta = $this->DBSelectAll("auta", "*", $where_array, "", $order_by_array);
		return $auta;
	}
	
    /*
        Funkce na vypis pouze vozu,
        ktery ma ve sloupci pujceno v tabulce
        auta nastavenou hodnotu NE.
    */
    public function vypisVozu()
	{
        $wheres = array(array("column"=>"pujceno", "value"=>"NE", "symbol"=>"="));
		$order_by_array = array();
		$auta = $this->DBSelectAll("auta", "*", $wheres, "", $order_by_array);
		return $auta;
	}
    
    /*
        Funkce na vypsani informaci
        o vozu podle konkretniho id_auta.
    */
    public function vypisVozuID($id)
	{    
        $wheres = array(array('column'=>'id_auta', 'value'=>$id, 'symbol'=>'='));
        return $this->DBSelectOne('auta', "*", $wheres, '');
	}
    
    /*
        Funkce na pridani vozu.
    */
    public function pridatAuto($znacka, $model, $typ, $spz, $barva, $km)
    {
        $item = array(array('column'=>'znacka', 'value'=>$znacka), 
                        array('column'=>'model', 'value'=>$model),
                        array('column'=>'typ', 'value'=>$typ),
                        array('column'=>'spz', 'value'=>$spz),
                        array('column'=>'barva', 'value'=>$barva),
                        array('column'=>'stav_tachometru', 'value'=>$km));
        $this->DBInsertExpanded('auta', $item);
    }
    
    /*
        Funkce na odebrani auta podle id_auta.
            Nejprve se vyberou vsechny servisy, ktere
            auto zadane vstupnim parametrem ma. Tyto servisy
            se ulozi do pole. Přes foreach se odstrani vsechny
            servisy z tabulky servis, ktere zadane auto ma.
            Pote az dojde k odstraneni konkretniho auta.
    */
    public function odebratAuto($id_auta)
    {
        
        $item1 = array(array('column'=>'id_auta', 'value'=>$id_auta, 'symbol'=>'='));
        $pom = $this->DBSelectAll("auta_has_servis", "id_servis", $item1, "", "");
        
            foreach ($pom as $key) 
            {
                $item2 = array(array('column'=>'id_servis', 'value'=>$key['id_servis'], 'symbol'=>'='));
                $this->DBDelete('servis', $item2);
            }
        
        $item3 = array(array('column'=>'id_auta', 'value'=>$id_auta, 'symbol'=>'='));
        $this->DBDelete('auta', $item3);
        
    }
    
    /*
        Funkce, ktera slouzi nasledne k vypsani
        historie vsech zapujcenych vozu.
    */
    public function historie()
	{
		$table_name = "zakaznik z inner join auta_has_zakaznik r on z.id_zakaznika = r.id_zakaznika inner join auta a on a.id_auta = r.id_auta";
		$where_array = array();
		$order_by_array = array();
		$servis = $this->DBSelectAll($table_name, "*", $where_array, "", $order_by_array);
		return $servis;
	}
}

?>