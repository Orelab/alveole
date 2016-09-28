<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Calendar_model extends MY_Model
{
	public $table = 'calendar'; // nécessaire pour utiliser MY_Model->clean_array()


	public function getEvent( $idEvent=null, $idUser=null )
	{
		return $this->db->select()
						->from('calendar')
						->where('id', $idEvent)
						->where('fk_user', $idUser)
						->get()
						->row();
	}



	public function getCalendar( $idUser=null, $calendars=null, $start=null, $end=null )
	{
		if( ! $start )
		{
			die( 'Erreur, pas de date de départ.' );
		}
		
		if( ! $end )
		{
			die( 'Erreur, pas de date de fin.' );
		}

		if( is_array($calendars) )
		{
			$this->db->where_in('fk_step', $calendars );
		}
		

		//-- non-recurrent events

		if( $idUser )
		{
			$this->db->where('fk_user', $idUser );
		}

		$nrec = $this->db->select(array(
								'calendar.*',
								'tag.group'
							))
						->from('calendar')
						->where( 'recurrence', 'none' )

						->group_start()

						->group_start()
						->where( 'start >=', $start )
						->where( 'start <=', $end )
						->group_end()

						->or_group_start()
						->where( 'end >=', $start )
						->where( 'end <=', $end )
						->group_end()

						->group_end()

						->join('tag', 'calendar.fk_step = tag.id')
						
						->get()
						->result();

		//die( $this->db->last_query() );	


		//-- recurrent events

		if( $idUser )
		{
			$this->db->where('fk_user', $idUser );
		}

		if( is_array($calendars) )
		{
			$this->db->where_in('fk_step', $calendars );
		}

		$rec = $this->db->select(array(
								'calendar.*',
								'tag.group'
							))
						->from('calendar')
						->where( 'recurrence <>', 'none' )
						->where( 'start <', $end )
						->join('tag', 'calendar.fk_step = tag.id')
						->get()
						->result();

		$recstart = new DateTime();
		$recend = new DateTime();
		$recrecend = new DateTime();

		foreach( $rec as $r )
		{
			$recstart->setTimestamp( $r->start );
			$recend->setTimestamp( $r->end );
			$recrecend->setTimestamp( $r->recend );

			$tsstart = $recstart->getTimestamp();
			$tsend = $recend->getTimestamp();
			$tsrecend = $recrecend->getTimestamp();
			
			$i = 0;

			while( $tsstart < $end and $tsstart < $tsrecend )
			{
				$interval = $r->interval>0 ? $r->interval : 1;
				
				if( $i++ % $interval == 0 )
				{
					if( $tsstart < $end && $tsend > $start )
					{
						$r->start = $tsstart;
						$r->end = $tsend;
	
						$nrec[] = clone $r;
					}
				}

				switch( $r->recurrence )
				{
					case 'day' :
						$recstart->modify('+1 day');
						$recend->modify('+1 day');
						break;
					case 'week' :
						$recstart->modify('+7 day');
						$recend->modify('+7 day');
						break;
					case 'month' :
						$recstart->modify('+1 month');
						$recend->modify('+1 month');
						break;
					case 'year' :
						$recstart->modify('+1 year');
						$recend->modify('+1 year');
						break;
					default: return false;
				}
			
				$tsstart = $recstart->getTimestamp();
				$tsend = $recend->getTimestamp();
			}

		}


		//-- virtual billing events
		
		if( is_array($calendars) )
		{
			$doIt = in_array( 19, $calendars);
		}

		if( is_null($calendars) or $doIt )	//	tag 19 = factures
		{
			$this->load->model('project/bill_model');
			$bills = $this->bill_model->getBills( null, false, false, true, $start, $end );
	
			foreach( $bills as $b )
			{
				$c = new stdClass();
				
				$c->id = 'bill|' . $b->id . '|' . $b->date;
				$c->title = $b->name . ' ' . $b->amount . '€';
				$c->description = $b->description;
				$c->start = $b->date;
				$c->end = $b->date;
				$c->recurrence = $b->recurrence;
				$c->interval = 1;
				$c->recend = '';
				$c->fk_step = 19;	// facture
				$c->fk_user = '';
				$c->allDay = 1;
				$c->readonly = 1;		// only bills are read only

				$nrec[] = $c;
			}
		}

		return $nrec;
	}


	
	public function save()
	{
		$data = $this->input->post();
		$id = $this->input->post('id');

		if( ! isset($data['fk_user']) )
		{
			$this->load->model('user_model');
			$user = $this->user_model->currentUser();
			$data['fk_user'] = $user->id;
		}

		/*
			No need to save meaningless values
		*/
		if( $data['recurrence'] == 'none' )
		{
			$data['interval'] = 0;
			$data['recend'] = 0;
		}
		
		/*
			If end if before start, we invert them
			(note that for monthly view, the end of events are NULL)
		*/
		if( $data['start'] > $data['end'] && $data['start'] && $data['end'] )
		{
			$tempo = $data['end'];
			$data['end'] = $data['start'];
			$data['start'] = $tempo;
		}

		/*
			If recend is before start, we invert them.
			We also have to change the value of end
		*/
		if( $data['recurrence'] != 'none' && $data['start'] > $data['recend'] )
		{
			$length = $data['end'] - $data['start'];
			
			$tempo = $data['recend'];
			$data['recend'] = $data['start'];
			$data['start'] = $tempo;
			
			$data['end'] = $data['start'] + $length;
		}
		
		
		$this->clean_array( $data );

		if( $id>0 )
		{
			if( $this->db->update('calendar', $data, 'id='.$id) )
				return $id;
				else
				return $this->db->_error_message();
		}		
		else
		{
			if( $this->db->insert('calendar', $data) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
	}



	public function delete()
	{
		$id = $this->input->post('id');
		
		if( $this->db->delete('calendar',array('id'=>$id) ) ) 
			return $id;
			else
			return $this->db->_error_message();
	}
}
