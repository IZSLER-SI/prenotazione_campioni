<?php  

class prenotazione_campioni_general {
	public $conn;
	public $table_name="ct_settings";
	
	function ct_price_format($cal_amount,$symbol_position,$decimal) {
		$return_price = '';
		$amount = str_replace(' ','',$cal_amount);
		$query = "select `option_value` from `ct_settings` where `option_name` = 'ct_currency_symbol'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		$currency_symbol = $value[0];
		if($amount != ''){
			if($symbol_position=='$100') { 		
				$pos = strpos($amount, '-');
				if($pos === false){
					$return_price = $currency_symbol.number_format($amount, $decimal, '.', '');	
				}else{
					$final_amount = str_replace('-','',$amount);
					$return_price = '-'.$currency_symbol.number_format($final_amount, $decimal, '.', '');
				}
			}else{
				$return_price = number_format($amount, $decimal, '.', '').$currency_symbol; 
			}
			
			return $return_price;	
		}
	}
	function get_lab_selected(){
		if(isset($_SESSION['lab_selected'])){
			$lab_id = $_SESSION['lab_selected'];
			$query = "SELECT descrizione FROM izler_laboratori where id = ".$lab_id;
			$result=mysqli_query($this->conn,$query);
			$lab=mysqli_fetch_row($result);
			if(empty($lab[0])){
				return 0;
			}else{
				return $lab[0];
			}
		}
	}
	
	function ct_price_format_total($cal_amount,$symbol_position,$decimal) {
		$return_price = '';
		$amount = str_replace(' ','',$cal_amount);
		$query = "select `option_value` from `ct_settings` where `option_name` = 'ct_currency_symbol'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		$currency_symbol = $value[0];
		if($amount != ''){
			if($symbol_position=='$100') { 		
				$pos = strpos($amount, '-');
				if($pos === false){
					$return_price = "<span class=ct_total_small>".$currency_symbol."</span>".number_format($amount, $decimal, '.', '');	
				}else{
					$final_amount = str_replace('-','',$amount);
					$return_price = '-'."<span class=ct_total_small>".$currency_symbol."</span>".number_format($final_amount, $decimal, '.', '');
				}
			}else{
				$return_price = number_format($amount, $decimal, '.', '')."<span class=ct_total_small>".$currency_symbol."</span>"; 
			}
			
			return $return_price;	
		}
	}
	
	function ct_price_format_for_pdf($cal_amount,$symbol_position,$decimal) {	
		$return_price = '';
		$amount = str_replace(' ','',$cal_amount);
		$query = "select `option_value` from `ct_settings` where `option_name` = 'ct_currency_symbol'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		$currency_symbol = $value[0];
		if($amount != ''){
			if($symbol_position=='$100') { 	
				/* $return_price .= iconv('UTF-8', 'windows-1252', $currency_symbol); */
				$return_price .= $currency_symbol;
				$return_price .= number_format($amount, $decimal, '.', '');
			}else{
				$return_price .= number_format($amount, $decimal, '.', '');
				$return_price .= iconv('UTF-8', 'windows-1252', $currency_symbol);
			}
			return $return_price;	
		}	
	
	}		
	
	
	
	function ct_price_format_without_symbol($cal_amount,$decimal) {
		$return_price = '';
		$amount = str_replace(' ','',$cal_amount);
		if($amount != ''){
			$return_price = number_format($amount, $decimal, '.', ''); 
			return $return_price;	
		}
	}
}
?>