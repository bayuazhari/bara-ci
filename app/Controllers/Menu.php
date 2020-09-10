<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\MenuModel;

class Menu extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new MenuModel();
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
				'breadcrumb' => @$checkMenu->mgroup_name,
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('menu/view_menu');
			echo view('layout/footer');
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function getTree()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			if(@$this->request->getGet('group')){
				$mgroup_id = $this->request->getGet('group');
			}else{
				$mgroup_id = 'MG2000001';
			}
			$menu = $this->model->getMenu($mgroup_id);
			if(@$menu){
				foreach($menu as $key => $row){
					if(@$row->mparent_id){
						$mparent_id = $row->mparent_id;
					}else{
						$mparent_id = '#';
					}

					if($row->menu_status == 1){
						$menu_status = '';
					}else{
						$menu_status = array(
							'disabled' => true
						);
					}
					$json_data[] = array(
						'id' => $row->menu_id,
						'parent' => $mparent_id,
						'text' => $row->menu_name,
						'icon' => $row->menu_class.' fa-lg',
						'state' => $menu_status
					);
				}
			}
			echo json_encode($json_data);
		}else{
			echo json_encode(array());
		}
	}

	public function add()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'menu_group' => $this->model->getMenuGroup()
			);
			$validation = $this->validate([
				'menu_group' => ['label' => 'Group', 'rules' => 'required'],
				'menu_name' => ['label' => 'Name', 'rules' => 'required'],
				'menu_class' => ['label' => 'Class', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('menu/form_add_menu', $data);
				echo view('layout/footer');
			}else{
				$menuLastPosition = $this->model->getMenuLastPosition($this->request->getPost('menu_group')) + 1;
				$menuData = array(
					'menu_id' => $this->model->getMenuId(),
					'mgroup_id' => $this->request->getPost('menu_group'),
					'menu_name' => $this->request->getPost('menu_name'),
					'menu_url' => $this->request->getPost('menu_url'),
					'menu_class' => $this->request->getPost('menu_class'),
					'menu_label' => $this->request->getPost('menu_label'),
					'menu_position' => $menuLastPosition,
					'menu_status' => 1
				);
				$this->model->insertMenu($menuData);
				session()->setFlashdata('success', 'Menu has been added successfully. (SysCode: <a href="'.base_url('menu?id='.$menuData['menu_id']).'" class="alert-link">'.$menuData['menu_id'].'</a>)');
				return redirect()->to(base_url('menu'));
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
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'menu_group' => $this->model->getMenuGroup(),
				'menu' => $this->model->getMenuById($id)
			);
			$validation = $this->validate([
				'menu_group' => ['label' => 'Group', 'rules' => 'required'],
				'menu_name' => ['label' => 'Name', 'rules' => 'required'],
				'menu_class' => ['label' => 'Class', 'rules' => 'required'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('menu/form_edit_menu', $data);
				echo view('layout/footer');
			}else{
				$menuData = array(
					'mgroup_id' => $this->request->getPost('menu_group'),
					'menu_name' => $this->request->getPost('menu_name'),
					'menu_url' => $this->request->getPost('menu_url'),
					'menu_class' => $this->request->getPost('menu_class'),
					'menu_label' => $this->request->getPost('menu_label'),
					'menu_status' => $this->request->getPost('status')
				);
				$this->model->updateMenu($id, $menuData);
				session()->setFlashdata('success', 'Menu has been updated successfully. (SysCode: <a href="'.base_url('menu?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('menu'));
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function updatePosition()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			if($this->request->getPost('parent') != '#'){
				$mparent_id = $this->request->getPost('parent');
			}else{
				$mparent_id = NULL;
			}
				
			$menuData = array(
				'mparent_id' => $mparent_id,
				'menu_position' => $this->request->getPost('position')
			);
			$this->model->updateMenu($this->request->getPost('id'), $menuData);
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
			$menuData = $this->model->getMenuById($id);
			$this->model->deleteMenu($id);
			session()->setFlashdata('warning', 'Menu has been removed successfully. <a href="'.base_url('menu/undo?data='.json_encode($menuData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('menu'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$menu = json_decode($this->request->getGet('data'));
		$checkMenu = $this->model->getMenuById(@$menu->menu_id);
		if(@$checkMenu){
			$menu_id = $this->model->getMenuId();
		}else{
			$menu_id = @$menu->menu_id;
		}
		$menuData = array(
			'menu_id' => $menu_id,
			'mgroup_id' => @$menu->mgroup_id,
			'mparent_id' => @$menu->mparent_id,
			'menu_name' => @$menu->menu_name,
			'menu_url' => @$menu->menu_url,
			'menu_class' => @$menu->menu_class,
			'menu_label' => @$menu->menu_label,
			'menu_position' => @$menu->menu_position,
			'menu_status' => @$menu->menu_status
		);
		$this->model->insertMenu($menuData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('menu?id='.$menu_id).'" class="alert-link">'.$menu_id.'</a>)');
		return redirect()->to(base_url('menu_group'));
	}
}
