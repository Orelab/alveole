<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller
{

	static public function init()
	{
		return array(
			"definition" => array(
				"title" => "Web search widget",
				"version" => "1.0",
				"description" => "A simple widget to add a search box in your dashboard for your favorite search engines.",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			),

			"widget" => array(
				array(
					"title" => "Recherche",
					"url" => "search/widget_search",
					"order" => "00"
				)
			)
		);
	}




	public function widget_search()
	{
		$this->load->view('search');
	}
	
}

