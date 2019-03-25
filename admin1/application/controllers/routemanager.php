<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Routemanager extends CI_Controller{
	
	function __construct()
	{
		parent::__construct();
	}
	
    public function create_routes_file(){
        $res = $this->db->get(TABLE_PAGES);
        $output = '<' . '?' . 'php ' . 'if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');' ."\n". "\n";
        foreach($res->result_array() as $rs){
            $output .= '$' . 'route[\'' . $rs['URL_SEOTOOL'] . '\'] = "' .$rs['url_route']. '";'."\n";
        }
		 $output .="\n";
		 $this->db->group_by('URL_SEOTOOL');
		 $res2 = $this->db->get(TABLE_ALLPOST);
		 foreach($res2->result_array() as $rs2){
            $output .= '$' . 'route[\'' . $rs2['URL_SEOTOOL'] . '\'] = "' .$this->router->routes['default_controller'].'/allPost'. '";'. "\n";
        }
        // unsure the file won't generate errors
        $route = array();
        eval('?> '.$output);
        // if error detected, the script will stop here (and won't bug the entire CI app). Otherwize it will generate the cache/route.php file
        $this->load->helper('file');
        write_file(APPPATH . 'cache/routes.php', $output);
    }
}
/* End of file routemanager.php */
/* Location: ./application/controllers/routemanager.php */