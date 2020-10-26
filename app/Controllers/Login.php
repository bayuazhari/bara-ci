<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\UserModel;

class Login extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new UserModel();
		
		$this->email = \Config\Services::email();
	}

	public function index()
	{
		$validation = $this->validate([
			'user_email' => ['label' => 'Email Address', 'rules' => 'required|valid_email'],
			'user_password' => ['label' => 'Password', 'rules' => 'required|min_length[6]']
		]);
		$data = array(
			'setting' => $this->setting,
			'title' =>  'Login',
			'request' => $this->request,
			'validation' => $this->validator
		);
		if(!$validation){
			echo view('frontend/form_login', $data);
		}else{
			$user_email = $this->request->getPost('user_email');
			$user_password = hash('sha256', $this->request->getPost('user_password'));
			$valid_user = $this->model->login($user_email, $user_password);

			if(@$this->request->getGet('redirect')){
				$login_redirect = '?redirect='.$this->request->getGet('redirect');
				$first_page = $this->request->getGet('redirect');
			}elseif(@$valid_user->menu_url){
				$first_page = $valid_user->menu_url;
			}else{
				$first_page = '';
			}

			if(!$valid_user){
				session()->setFlashdata('warning', 'Invalid username or password.');
				return redirect()->to(base_url('login'.@$login_redirect));
			}else{
				if($valid_user->user_status == 0){
					session()->setFlashdata('warning', 'Your account has been blocked.');
					return redirect()->to(base_url('login'.@$login_redirect));
				}elseif($valid_user->email_verification == 0){
					session()->setFlashdata('warning', 'Email address is not verified.');
					return redirect()->to(base_url('login'.@$login_redirect));
				}else{
					$session_data = array(
						'appid' => @$this->setting->getSettingById(0)->setting_value,
						'user_id' => $valid_user->user_id,
						'level_id' => $valid_user->level_id,
						'full_name' => $valid_user->first_name.' '.$valid_user->last_name,
						'level_name' => $valid_user->level_name,
						'user_photo' => $valid_user->user_photo_name,
						'tz_name' => $valid_user->tz_name
					);
					session()->set($session_data);

					return redirect()->to(base_url($first_page));
				}
			}
		}
	}

	public function logout()
	{
		session()->destroy();
		return redirect()->to(base_url('login'));
	}
}
