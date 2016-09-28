<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Payment_model extends MY_Model
{
	public $table = 'payment'; // nécessaire pour utiliser MY_Model->clean_array()



	public function getPayments( $idProject=null, $from=null, $to=null )
	{
		if( $idProject )
		{
			$this->db->where('bill.fk_project', $idProject );
		}

		if( $from )
		{
			$this->db->where('bill.date >=', $from );
		}

		if( $to )
		{
			$this->db->where('bill.date <', $to );
		}

		$recordset = $this->db->select(array(
							'payment.id',
							'payment.fk_bill',
							'payment.fk_date',
							'payment.date',
							'payment.amount'
						))
						->from('payment')
						->join('bill', 'payment.fk_bill = bill.id', 'left')
						->order_by('payment.fk_date')
						->get()
						->result();

		return $recordset;
	}



	
	public function save( $id )
	{
		$data = $this->input->post();
		

		$data['date'] = strtotime( $data['date'] );
		
		$tmp = explode('|', $data['fk_bill']);
		$data['fk_bill'] = $tmp[0];
		$data['fk_date'] = $tmp[1];


		//-- removing unnecessary vars
		 
		$this->clean_array( $data );

		if( $id>0 )
		{
			if( $this->db->update('payment', $data, 'id='.$id) )
				return $id;
				else
				return $this->db->_error_message();
		}		
		else
		{
			if( $this->db->insert('payment', $data) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
	}

}
