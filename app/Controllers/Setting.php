<?php namespace App\Controllers;

use App\Models\SettingModel;

class Setting extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
	}

	public function index()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' =>  @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount('0'),
				'notification' => $this->setting->getNotif('0'),
				'breadcrumb' => @$checkMenu->mgroup_name
			);
			echo view('layout/header', $data);
			echo view('view_setting');
			echo view('layout/footer');
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function edit()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			$validation = $this->validate([
				'pk' => ['label' => 'Primary Key', 'rules' => 'required'],
				'value' => ['label' => 'Setting Value', 'rules' => 'required']
			]);
			if(!$validation){
				session()->setFlashdata('warning', $this->validator->listErrors());
				return redirect()->to(base_url('setting'));
			}else{
				$settingData = array(
					'setting_value' => $this->request->getPost('value')
				);
				$this->setting->updateSetting($this->request->getPost('pk'), $settingData);
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function upload_image()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			$validation = $this->validate([
				'pk' => ['label' => 'Primary Key', 'rules' => 'required'],
				'image' => ['label' => 'Image', 'rules' => 'uploaded[image]|ext_in[image,ico,png,jpg,gif]|max_size[image,2048]']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('setting'));
			}else{
				$image = $this->request->getFile('image');
				$newName = $image->getRandomName();
				$image->move('../public/assets/img/logo', $newName);
				$settingData = array(
					'setting_value' => $newName
				);
				$this->setting->updateSetting($this->request->getPost('pk'), $settingData);
				session()->setFlashdata('success', 'Image has been updated successfully');
				return redirect()->to(base_url('setting'));
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}
}
