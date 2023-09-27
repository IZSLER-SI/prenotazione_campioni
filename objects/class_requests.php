<?php     
class prenotazione_campioni_requests{
 public $id;
 public $email;
 public $nome;
 public $cognome;
 public $telefono;
 public $codice_fiscale;
 public $lab;
 public $role;
 public $record_attivo = 1;

 public function readone($id){
		$query="select * from izsler_utenti_richieste where record_attivo = 1 and id = $id ";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	public function update_added($id){	
		$query = "update izsler_utenti_richieste set record_attivo	= 2 where id = $id";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function update_removed($id){	
		$query = "update izsler_utenti_richieste set record_attivo	= 3 where id = $id";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}

