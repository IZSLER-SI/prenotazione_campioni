<?php  class prenotazione_campioni_service_methods_design{				public $id;		public $design;        public $service_methods_id;		public $table_name="ct_service_methods_design";        public $conn;		/* to get design type to show in front end */        public function get_service_methods_design($id){            $this->service_methods_id=$id;            $query="select `design` from `".$this->table_name."` where `service_methods_id`='".$this->service_methods_id."'";            $result=mysqli_query($this->conn,$query);            $value=mysqli_fetch_row($result);            return $value[0];        }}?>