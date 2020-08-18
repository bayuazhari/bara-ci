<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\MenuGroupModel;

class Menu_group extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new MenuGroupModel();
	}

	public function index()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$data = array(
				'title' =>  @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'model' => $this->model,
				'menu_group' => $this->model->getMenuGroup(),
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('menu_group/view_menu_group', $data);
			echo view('layout/footer');
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function add()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request
			);
			$validation = $this->validate([
				'mgroup_name' => ['label' => 'Name', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('menu_group/form_add_menu_group', $data);
				echo view('layout/footer');
			}else{
				$menuGroupData = array(
					'mgroup_id' => $this->model->getMenuGroupId(),
					'mgroup_name' => $this->request->getPost('mgroup_name'),
					'mgroup_status' => 1
				);
				$this->model->insertMenuGroup($menuGroupData);
				session()->setFlashdata('success', 'Menu group has been added successfully. (SysCode: <a href="'.base_url('menu_group?id='.$menuGroupData['mgroup_id']).'" class="alert-link">'.$menuGroupData['mgroup_id'].'</a>)');
				return redirect()->to(base_url('menu_group'));
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function bulk_upload()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$validation = $this->validate([
				'menu_group_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[menu_group_csv]|ext_in[menu_group_csv,csv]|max_size[menu_group_csv,2048]']
			]);
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'validation' => $this->validator
			);
			if(!$validation){
				echo view('layout/header', $data);
				echo view('menu_group/form_bulk_upload_menu_group');
				echo view('layout/footer');
			}else{
				$menu_group_csv = $this->request->getFile('menu_group_csv')->getTempName();
				$file = file_get_contents($menu_group_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['menu_group'] = array();
				foreach ($lines as $line) {
					$data['menu_group'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('menu_group/form_bulk_upload_menu_group', $data);
				echo view('layout/footer');
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function bulk_save()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$validation = $this->validate([
				'menu_group.*.mgroup_name' => ['label' => 'Name', 'rules' => 'required']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('menu_group/bulk_upload'));
			}else{
				foreach ($this->request->getPost('menu_group') as $row) {
					$menuGroupData = array(
						'mgroup_id' => $this->model->getMenuGroupId(),
						'mgroup_name' => $row['mgroup_name'],
						'mgroup_status' => 1
					);
					$this->model->insertMenuGroup($menuGroupData);
				}
				session()->setFlashdata('success', 'Menu groups has been added successfully.');
				return redirect()->to(base_url('menu_group'));
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function edit($id)
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'menu_group' => $this->model->getMenuGroupById($id)
			);
			$validation = $this->validate([
				'mgroup_name' => ['label' => 'Name', 'rules' => 'required'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('menu_group/form_edit_menu_group', $data);
				echo view('layout/footer');
			}else{
				$menuGroupData = array(
					'mgroup_name' => $this->request->getPost('mgroup_name'),
					'mgroup_status' => $this->request->getPost('status')
				);
				$this->model->updateMenuGroup($id, $menuGroupData);
				session()->setFlashdata('success', 'Menu group has been updated successfully. (SysCode: <a href="'.base_url('menu_group?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('menu_group'));
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function delete($id)
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->delete == 1){
			$menuGroupData = $this->model->getMenuGroupById($id);
			$this->model->deleteMenuGroup($id);
			session()->setFlashdata('warning', 'Menu group has been removed successfully. <a href="'.base_url('menu_group/undo?data='.json_encode($menuGroupData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('menu_group'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$menu_group = json_decode($this->request->getGet('data'));
		$checkMenuGroup = $this->model->getMenuGroupById(@$menu_group->mgroup_id);
		if(@$checkMenuGroup){
			$mgroup_id = $this->model->getMenuGroupId();
		}else{
			$mgroup_id = @$menu_group->mgroup_id;
		}
		$menuGroupData = array(
			'mgroup_id' => $mgroup_id,
			'mgroup_name' => @$menu_group->mgroup_name,
			'mgroup_status' => @$menu_group->mgroup_status
		);
		$this->model->insertMenuGroup($menuGroupData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('menu_group?id='.$mgroup_id).'" class="alert-link">'.$mgroup_id.'</a>)');
		return redirect()->to(base_url('menu_group'));
	}
}
