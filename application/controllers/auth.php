<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Example Auth controller using Authme
 *
 * @package Authentication
 * @category Libraries
 * @author Gilbert Pellegrom
 * @link http://dev7studios.com
 * @version 1.0
 */

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('authme');
		$this->load->helper('authme');
		$this->config->load('authme');
		
		$this->load->helper('url');
	}
	
	public function index()
	{
		if(!logged_in()) redirect('auth/login');
		 
		// Redirect to your logged in landing page here
		redirect('auth/dash');
	}
	
	/**
	 * Login page
	 */
	public function login()
	{
		// Redirect to your logged in landing page here
		if(logged_in()) redirect('auth/dash');
		 
		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['error'] = false;
		 
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if($this->form_validation->run()){
			if($this->authme->login(set_value('email'), set_value('password'))){
				// Redirect to your logged in landing page here
				redirect('auth/dash');
			} else {
				$data['error'] = 'Your email address and/or password is incorrect.';
			}
		}
		
		$this->load->view('auth/login', $data);
	}
	
	/**
	 * Signup page
	 */
	public function signup()
	{
		// Redirect to your logged in landing page here
		if(logged_in()) redirect('auth/dash');
		 
		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['error'] = '';
		 
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique['. $this->config->item('authme_users_table') .'.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length['. $this->config->item('authme_password_min_length') .']');
		$this->form_validation->set_rules('password_conf', 'Confirm Password', 'required|matches[password]');
		
		if($this->form_validation->run()){
			if($this->authme->signup(set_value('email'), set_value('password'))){
				$this->authme->login(set_value('email'), set_value('password'));
				
				// Do some post signup stuff like send a welcome email...
				
				// Redirect to your logged in landing page here
				redirect('auth/dash');
			} else {
				$data['error'] = 'Failed to sign up with the given email address and password.';
			}
		}
		
		$this->load->view('auth/signup', $data);
	}
	
	/**
	 * Logout page
	 */
	public function logout()
	{
		if(!logged_in()) redirect('auth/login');

		// Redirect to your logged out landing page here
		$this->authme->logout('/');
	}
	
	/**
	 * Example dashboard page
	 */
	public function dash()
	{
		if(!logged_in()) redirect('auth/login');
		
		echo 'Hi, '. user('email') .'. You have successfully  logged in. <a href="'. site_url('auth/logout') .'">Logout</a>';
	}
	
	/**
	 * Forgot password page
	 */
	public function forgot()
	{
		// Redirect to your logged in landing page here
		if(logged_in()) redirect('auth/dash');
		 
		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['success'] = false;
		 
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_exists');
		
		if($this->form_validation->run()){
			$email = $this->input->post('email');
			$this->load->model('Authme_model');
			$user = $this->Authme_model->get_user_by_email($email);
			$slug = md5($user->id . $user->email . date('Ymd'));

			$this->load->library('email');
			$this->email->from('noreply@example.com', 'Example App'); // Change these details
			$this->email->to($email); 
			$this->email->subject('Reset your Password');
			$this->email->message('To reset your password please click the link below and follow the instructions:
      
'. site_url('auth/reset/'. $user->id .'/'. $slug) .'

If you did not request to reset your password then please just ignore this email and no changes will occur.

Note: This reset code will expire after '. date('j M Y') .'.');	
			$this->email->send();
			
			$data['success'] = true;
		}
		
		$this->load->view('auth/forgot_password', $data);
	}
	
	/**
	 * CI Form Validation callback that checks a given email exists in the db
	 *
	 * @param string $email the submitted email
	 * @return boolean returns false on error
	 */
	public function email_exists($email)
	{
		$this->load->model('Authme_model');
		 
		if($this->Authme_model->get_user_by_email($email)){
			return true;
		} else {
			$this->form_validation->set_message('email_exists', 'We couldn\'t find that email address in our system.');
			return false;
		}
	}
	
	/**
	 * Reset password page
	 */
	public function reset()
	{
		// Redirect to your logged in landing page here
		if(logged_in()) redirect('auth/dash');
		 
		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['success'] = false;
		 
		$user_id = $this->uri->segment(3);
		if(!$user_id) show_error('Invalid reset code.');
		$hash = $this->uri->segment(4);
		if(!$hash) show_error('Invalid reset code.');
		
		$this->load->model('Authme_model');
		$user = $this->Authme_model->get_user($user_id);
		if(!$user) show_error('Invalid reset code.');
		$slug = md5($user->id . $user->email . date('Ymd'));
		if($hash != $slug) show_error('Invalid reset code.');
	 
		$this->form_validation->set_rules('password', 'Password', 'required|min_length['. $this->config->item('authme_password_min_length') .']');
		$this->form_validation->set_rules('password_conf', 'Confirm Password', 'required|matches[password]');
		
		if($this->form_validation->run()){
			$this->authme->reset_password($user->id, $this->input->post('password'));
			$data['success'] = true;
		}
		
		$this->load->view('auth/reset_password', $data);
	}
	
}