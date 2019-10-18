<?php 

class Response{
	
	public $result  	= null;
	public $status  	= false;
	public $message		= '';
	public $error		= '';

	public function send($result, $status, $message = '', $error = ''){
		$this->result = isset($result) ? $result:null;
		$this->status = isset($status) ? $status:false;
		$this->message = isset($message) ? $message: 'Ocurrio un Error';
		//$this->error = isset($error) ? $error:[]; 
		$this->error = isset($error) ? $error:'';
		return $this;
	}

	public function timeVerbal($v_date,$differenceFormat = '%a') {
		$date1 = date_create(date("Y-m-d"));
		$date2 = date_create($v_date);
		$interval = $v_date;
		$month = Array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SET','OCT','NOV','DIC');
		if ( $date1 == $date2 )
			return 'Hoy';
		if( $date1 < $date2 ) {
			$interval = date_diff($date1, $date2)->format($differenceFormat);
			if($interval == 1)
				return 'Mañana';
		}
		$year = substr($v_date,0,4);
		if( $year == date("Y") )
			$year = '0';
		$interval = $year.'-'.$month[(int)substr($v_date,5,7)-1].substr($v_date,7,10);

		return $interval;
	}

	public function convertDate($v_date) {
		$month = Array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SET','OCT','NOV','DIC');
		return substr($v_date,0,5).$month[(int)substr($v_date,5,7)-1].substr($v_date,7,10);
	}
}

?>