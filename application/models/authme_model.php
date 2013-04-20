<?php
/**
 * Authme Authentication Library
 *
 * @package Authentication
 * @category Libraries
 * @author Gilbert Pellegrom
 * @link http://dev7studios.com
 * @version 1.0
 */

class Authme_model extends CI_Model {

	public $users_table;
	
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->config->load('authme');

		$this->users_table = $this->config->item('authme_users_table');
		
		if(!$this->db->table_exists($this->users_table)) $this->create_users_table();
	}
	
	public function get_user($user_id)
	{
		$query = $this->db->get_where($this->users_table, array('id' => $user_id));
		if($query->num_rows()) return $query->row();
		return false;
	}
	
	public function get_user_by_email($email)
	{
		$query = $this->db->get_where($this->users_table, array('email' => $email));
		if($query->num_rows()) return $query->row();
		return false;
	}
	
	public function get_users($order_by = 'id', $order = 'asc', $limit = 0, $offset = 0)
	{
	    $this->db->order_by($order_by, $order); 
	    if($limit) $this->db->limit($limit, $offset);
		$query = $this->db->get($this->users_table);
		return $query->result();
	}

	public function get_user_count()
	{
		return $this->db->count_all($this->users_table);
	}
	
	public function create_user($email, $password)
	{
		$data = array(
			'email' => filter_var($email, FILTER_SANITIZE_EMAIL),
			'password' => $password, // Should be hashed
			'created' => date('Y-m-d H:i:s')
		);
		$this->db->insert($this->users_table, $data);
		return $this->db->insert_id();
	}
	
	public function update_user($user_id, $data)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->users_table, $data); 
	}
	
	public function delete_user($user_id)
	{
		$this->db->delete($this->users_table, array('id' => $user_id));
	}
	
	private function create_users_table()
	{
		$this->load->dbforge();
		$this->dbforge->add_field('id INT(11) NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field('email VARCHAR(200) NOT NULL');
		$this->dbforge->add_field('password VARCHAR(200) NOT NULL');
		$this->dbforge->add_field('created DATETIME NOT NULL');
		$this->dbforge->add_field('last_login DATETIME NOT NULL');
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table($this->users_table);
	}
	
}

/* End of file: authme_model.php */
/* Location: application/models/authme_model.php */