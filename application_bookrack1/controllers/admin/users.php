<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user');
		$this->load->helper(array('url','form'));
		$this->load->library(array('form_validation','common_functions'));
	}
	public function index()
	{
		$this->redirect_user();

		$this->load->library('pagination');
		$data['title'] = "Admin - User";
		$count=$this->user->count()->offsetGet(0);
		$config['base_url']=site_url('admin/users');
		$config['total_rows']=$count['total'];
		$config["per_page"]=10;
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$data['results']=$this->user->fetch($config["per_page"], $page);
		$data['links']=$this->pagination->create_links();
		$this->load->view('admin/templates/header.php',$data);
		$this->load->view('admin/user/index.php',$data);
		$this->load->view('admin/templates/footer.php');
	}
	public function insert()
	{	
		$this->redirect_user();

		$data['title'] = "Insert - User";
		$data['buttonText']="Insert";
		$data['post_url']="/admin/users/insert";
		
		$this->set_validation();

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('admin/templates/header.php',$data);
			$this->load->view('admin/user/insert.php');
			$this->load->view('admin/user/form.php',$data);
			$this->load->view('admin/templates/footer.php');
		}
		else
		{
			$this->user->set_user();
			echo 'success';
		}
	}
	public function update($id)
	{
		$this->redirect_user();

		$data['title'] = "Update - User";
		$data['buttonText']="Save";
		$data['post_url']="/admin/users/update";
		$user=$this->user->get($id);
		$user_data=array();
		foreach ($user->getProperties() as $key => $value) {
			$user_data[$key]=$value;
		}
		$user_data['id']=$user->getId();
		$this->set_validation();

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('admin/templates/header.php',$data);
			$this->load->view('admin/user/form.php',$user_data);
			$this->load->view('admin/templates/footer.php');
		}
		else
		{
			$this->user->update_user();
			echo "success";
			$this->load->view('admin/templates/header.php',$data);
			$this->load->view('admin/user/form.php');
			$this->load->view('admin/templates/footer.php');
		}
	}
	public function view($id)
	{
		$this->redirect_user();

		$data['title'] = "View - User";
		$user=$this->user->get($id);
		foreach ($user->getProperties() as $key => $value) {
			$user_data[$key]=$value;
		}
		$user_data['id']=$user->getId();
		$this->load->view('admin/templates/header.php',$data);
		$this->load->view('admin/user/view.php',$user_data);
		$this->load->view('admin/templates/footer.php');
	}
	public function delete()
	{
		$this->redirect_user();

		if(isset($_POST)){
			$id=intval($_POST['node_id']);
			$this->user->delete($id);
			$data['msg']="successfully deleted";
			echo json_encode($data);
		}
		//$data['title'] = "Delete - User";
		//$this->load->view('admin/templates/header.php',$data);
		
		//$this->load->view('admin/templates/footer.php');
	}
	private function set_validation()
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|xss_clean');
		$this->form_validation->set_rules('about', 'About', 'trim|xss_clean');
		$this->form_validation->set_rules('location', 'Location', 'trim|xss_clean');
		$this->form_validation->set_rules('profile_url', 'Profile url', 'trim|xss_clean');
		$this->form_validation->set_rules('skype', 'Skype', 'trim|xss_clean');
		$this->form_validation->set_rules('facebook', 'Facebook link', 'trim|xss_clean');
		$this->form_validation->set_rules('twitter', 'Twitter link', 'trim|xss_clean');
		$this->form_validation->set_rules('googlePlus', 'Google+ link', 'trim|xss_clean');
		$this->form_validation->set_rules('verified_email', 'Verified email', 'trim|xss_clean');
		$this->form_validation->set_rules('verified_account', 'Verified account', 'trim|xss_clean');
		$this->form_validation->set_rules('active', 'Active bit', 'trim|xss_clean');
		$this->form_validation->set_rules('last_login', 'Verified account', 'trim|xss_clean');
	}
	public function redirect_user()
	{
		if(!$this->common_functions->is_admin()==1)
			redirect(site_url());
	}

}