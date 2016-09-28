<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Bill_model extends MY_Model
{
	/*
		This one is needed if we have to use MY_Model->clean_array()
		That method allow to clean dataset before to save them in
		the database.
	*/
	public $table = 'bill';



	/*
		Get a list of bills. The list depends of the next parameters, 
		and who ask (if the current user is a customer, he will only 
		get his onw bills).
		
		@param $idProject	get bills from one particular project
		@param $unpaid the bills which haven't been fully paid yet
		@param $passedTerm the bills which payment limit is passed
		@param $withVirtual don't get virtual bills (the ones generated 
				from recurrent bills)
		@param $start : beginning of desired period
		@param $end : end of desired period
		@return an active record dataset
	
	*/
	public function getBills( $idProject=null, $unpaid=false, $passedTerm=false, $withVirtual=true, $start=null, $end=null )
	{
		if( is_group('client') )
		{
			$this->load->model('user_model');
			$user = $this->user_model->currentUser();

			$this->db->where('role.fk_user', $user->id )
						->where('role.on', '1')
						->join('role', 'project.id = role.fk_project', 'left');
		}
		
		if( $idProject )
		{
			$this->db->where('bill.fk_project', $idProject );
		}
		
		if( $unpaid )
		{
			$this->db->having('bill.amount > `paidbycustomer`');
			$this->db->or_having('`paidbycustomer` IS NULL');
		}
		
		if( $passedTerm )
		{
			$this->db->where('bill.date + (bill.term*24*60*60) <', time() );
		}
	
		if( $start )
		{
			$this->db->where('bill.date >', $start );
		}
		
		if( $end )
		{
			$this->db->where('bill.date <', $end );
		}

		$recordset = $this->db->select(array(
							'bill.id',
							'bill.fk_project',
							'bill.fk_step',
							'bill.recurrence',
							'bill.amount',
							'bill.repartition',
							'bill.date',
							'bill.term',
							
							'bill.fk_document',
							'document.name as document',
							'document.file_name as file',
							'document.file_type as type',

							'project.name',
							'project.url',
							'project.description',
							'project.active',
							
							'SUM(payment.amount) as paidbycustomer'
						))
						->from('bill')
						->join('project', 'bill.fk_project = project.id', 'left')
						->join('document', 'bill.fk_document = document.id', 'left')
						->join('payment', 'bill.id = payment.fk_bill AND bill.date = payment.fk_date', 'left')
						->group_by('bill.id')
						->get()
						->result();

		//die( $this->db->last_query() );


		//-- append virtual bills (for recurrent ones)

		if( $withVirtual )
		{
			$this->appendVirtualEvents( $recordset, $idProject, $unpaid, $passedTerm, $start, $end );
		}


		return $recordset;
	}




	/*
		The bills can be shown as events in the calendar. In this way,
		it is practical to get bills directly in even format. That's
		what is done here.
		
		As bills can be virtuals (aka don't exists in the database),
		we need to get the date of the bill. The function will first
		get the full list of bill corresponding to the id. It will
		then parse the bill to find the one which correspond to the
		right date.
		
		@param $idBill : the id of the original bill 
		@param $date : the billing date
		@return an array of billing data (or false if not found) 
	*/
	public function getBillAsEvent( $idBill, $date )
	{
		$recordset = $this->db
				->select(array(
							'bill.id',
							'bill.fk_project',
							'bill.fk_step',
							'bill.recurrence',
							'bill.amount',
							'bill.repartition',
							'bill.date',
							'bill.term',
							
							'bill.fk_document',

							'project.name',
							'project.url',
							'project.description',
							'project.active',
						))
				->from('bill')
				->where('bill.id', $idBill)
				->join('project', 'bill.fk_project = project.id', 'left')
				->get()
				->result();

		$this->appendVirtualEvents( $recordset, null, false, false, $date, $date );

		foreach( $recordset as $r )
		{
			if( $r->date == $date )
			{
				return array(
					'id'				=> 'bill|' . $r->id . '|' . $r->date,
					'title'			=> $r->name . ' ' . $r->amount . '€',
					'description'	=> $r->description,
					'start'			=> $r->date,
					'end'				=> $r->date,
					'recurrence'	=> $r->recurrence,
					'interval'		=> 1,
					'recend'			=> '',
					'fk_step'		=> 19,	// facture
					'fk_user'		=> '',
					'allDay'			=> 1,
					'readonly'		=> 1		// only bills are read only
				);
			}
		}
		return false;
	}






	private function appendVirtualEvents( &$recordset, $idProject, $unpaid=false, $passedTerm=false, $start, $end )
	{
		$date = new DateTime();
		$virtuals = array();

		if( ! $end )
		{
			$end = time() + (60 * 60 * 24 * 365);
		}

		if( $idProject )
		{
			$this->db->where('bill.fk_project', $idProject );
		}

		/*
			recordset2 contains all the project's bills which are recurrent,
			whatever they started during the period, or before (but not after).
		*/
		$recordset2 = $this->db
				->select(array(
							'bill.id',
							'bill.fk_project',
							'bill.fk_step',
							'bill.recurrence',
							'bill.amount',
							'bill.repartition',
							'bill.date',
							'bill.term',
							
							'bill.fk_document',
							'document.name as document',
							'document.file_name as file',
							'document.file_type as type',

							'project.name',
							'project.url',
							'project.description',
							'project.active',
							
							'SUM(payment.amount) as paidbycustomer'
						))
				->from('bill')
				->where('bill.recurrence <>', 'none')
				/*
					ajouter contrainte sur 'recend' ici
				*/
				->where('bill.date <=', $end)
				->join('project', 'bill.fk_project = project.id', 'left')

				->join('document', 'bill.fk_document = document.id', 'left')
				->join('payment', 'bill.id = payment.fk_bill AND bill.date = payment.fk_date', 'left')
				->group_by('bill.id')

				->get()
				->result();

		//die( $this->db->last_query() );


		foreach( $recordset2 as $r )
		{
			$next = clone $r;

			while(true)
			{
				switch( $r->recurrence )
				{
					case 'daily':					
						$date->setTimestamp( $next->date );
						$date->modify('+1 day');
						$next->date = $date->getTimestamp();
						break;
	
					case 'weekly':
						$date->setTimestamp( $next->date );
						$date->modify('+1 week');
						$next->date = $date->getTimestamp();
						break;
	
					case 'monthly':
						$date->setTimestamp( $next->date );
						$date->modify('+1 month');
						$next->date = $date->getTimestamp();
						break;
	
					case 'yearly':
						$date->setTimestamp( $next->date );
						$date->modify('+1 year');
						$next->date = $date->getTimestamp();
						break;
					
					default:
						/*
							http://php.net/manual/fr/control-structures.break.php
						*/
						break 2;
				}
				
				if( $next->date >= $end )
				{
					break;
				}

				if( $unpaid )
				{
					if( $next->amount > $next->paidbycustomer || is_null($next->paidbycustomer) )
					{
						break;
					}
				}

				if( $passedTerm )
				{
					if( $next->date + ($next->term*24*60*60) < time() )
					{
						break;
					}
				}

				if( $next->date >= $start )
				{
					$virtuals[] = clone $next;
				}
			}
		}

		$recordset = array_merge( $recordset, $virtuals );

		if ( ! function_exists('bill_sort') )
		{
			function bill_sort($a, $b)
			{
			    return strcmp($a->date, $b->date);
			}
		}
		usort( $recordset, 'bill_sort' );


		return $recordset;
	}




	/*
		This method append to the given recordset all the virtual events
		
		@param $recordset : a recordset returned by CI active records
		@return nothing (the recordset is passed by reference)
	*/
	private function genVirtualEvents( &$recordset )
	{
		$date = new DateTime();
		$virtuals = array();

		foreach( $recordset as $r )
		{
			/*
				We'll generate virtual bills until 1 year in the future,
				or maximum 5 virtual bills per recurrent bill (this will
				prevent ergonomic problems on the IHM).
			*/
			$timelimit = $r->date + 60*60*24*370;
			$numberlimit = 5;

			$next = clone $r;

			while(true)
			{
				switch( $r->recurrence )
				{
					case 'daily':					
						$date->setTimestamp( $next->date );
						$date->modify('+1 day');
						$next->date = $date->getTimestamp();
						break;

					case 'weekly':
						$date->setTimestamp( $next->date );
						$date->modify('+1 week');
						$next->date = $date->getTimestamp();
						break;

					case 'monthly':
						$date->setTimestamp( $next->date );
						$date->modify('+1 month');
						$next->date = $date->getTimestamp();
						break;

					case 'yearly':
						$date->setTimestamp( $next->date );
						$date->modify('+1 year');
						$next->date = $date->getTimestamp();
						break;

					default:
						/*
							http://php.net/manual/fr/control-structures.break.php
						*/
						break 2;
				}

				if( $next->date >= $timelimit )
				{
					break;
				}

				if( $numberlimit-- < 0)
				{
					break;
				}
				
				$virtuals[] = clone $next;
			}
		}

		$recordset = array_merge( $recordset, $virtuals );

		if ( ! function_exists('bill_sort') )
		{
			function bill_sort($a, $b)
			{
			    return strcmp($a->date, $b->date);
			}
		}
		usort( $recordset, 'bill_sort' );


		return $recordset;
	}




	
	public function save( $id )
	{
		$data = $this->input->post();
		//$id = $this->input->post('id');


		//-- document backup

		$_POST['ressource'] = 'bill';
		$_POST['fk_ressource'] = $id;
		
		$this->load->model('document/document_model' );
		$docid = $this->document_model->save();

		if( is_int($docid) )
		{
			$data['fk_document'] = $docid;
		}
		unset( $data['file'] );
		unset( $_POST['ressource'] );
		unset( $_POST['fk_ressource'] );


		//-- log backup

		$data['date'] = strtotime( $data['date'] );
		$data['term'] = intval( $data['term'] );
		

		//-- removing unnecessary vars
		 
		$this->clean_array( $data );


		if( $id>0 )
		{
			if( $this->db->update('bill', $data, 'id='.$id) )
				return $id;
				else
				return $this->db->_error_message();
		}		
		else
		{
			/*
				We prevent a new bill from being just between two dates,
				at precisely 00:00:00, as it can be complicated to manage
				for later, in statistics and calendars...
			*/
			$data['date'] = $data['date'] + 10;

			if( $this->db->insert('bill', $data) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
	}

}
