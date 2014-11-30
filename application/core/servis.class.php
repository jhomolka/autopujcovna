<?php 

class servis extends db
{
	/*
        Konstruktor
    */ 
	public function servis($connection)
	{
		$this->connection = $connection;	
	}
	
    /*
        Funkce na vypis vsech servisu
    */
	public function vypisServisu()
	{
		$table_name = "servis s inner join auta_has_servis r on s.id_servis = r.id_servis inner join auta a on a.id_auta = r.id_auta";
		$where_array = array();
		$order_by_array = array();
		$servis = $this->DBSelectAll($table_name, "*", $where_array, "", $order_by_array);
		return $servis;
	}
    
    /*
        Funkce na pridani servisu
    */
    public function pridatServis($datum, $komentar, $id_auta)
    {
        $item = array(array('column'=>'datum_kontroly', 'value'=>$datum), 
                        array('column'=>'stav_komentar', 'value'=>$komentar));
        $p = $this->DBInsertExpanded('servis', $item);
        
        $pom = array(array('column'=>'id_auta', 'value'=>$id_auta), 
                        array('column'=>'id_servis', 'value'=>$p));  
        $this->DBInsertExpanded('auta_has_servis', $pom); 
    }
    
}


?>