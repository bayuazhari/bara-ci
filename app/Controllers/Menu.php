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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' =>  @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'checkLevel' => $checkLevel,
				'menu_group' => $this->model->getMenuGroup()
			);
			echo view('layout/header', $data);
			echo view('menu/view_menu', $data);
			echo view('layout/footer');
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function getData()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$columns = array(
				0 => 'menu_id',
				1 => 'menu_class',
				2 => 'menu_name',
				3 => 'menu_url',
				4 => 'mparent_id',
				5 => 'mgroup_name',
				6 => 'menu_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getMenuCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$menu = $this->model->getMenu($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$menu =  $this->model->searchMenu($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchMenuCount($search);
			}

			$data = array();
			if(@$menu){
				foreach($menu as $row){
					$start++;
					if(@$row->menu_label){
						$menu_label = ' <span class="label label-theme">'.$row->menu_label.'</span>';
					}else{
						$menu_label = '';
					}

					if(@$row->menu_url){
						$menu_url = '<a href="'.base_url($row->menu_url).'">'.base_url($row->menu_url).'</a>';
					}else{
						$menu_url  = '';
					}

					$mparent_name = @$this->model->getMenuById($row->mparent_id)->menu_name;

					if($row->menu_status == 1){
						$menu_status = '<span class="text-success">Active</span>';
					}elseif($row->menu_status == 0){
						$menu_status = '<span class="text-danger">Inactive</span>';
					}else{
						$menu_status = '';
					}

					if(@$checkLevel->update == 1 OR @$checkLevel->delete == 1){
						if(@$checkLevel->update == 1){
							$action_edit = '<a href="'.base_url('menu/edit/'.$row->menu_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
						}
						if(@$this->model->getMenuRelatedTable('level', $row->menu_id)){
							$delete_disabled = ' disabled';
						}else{
							$delete_disabled = '';
						}
						if(@$checkLevel->delete == 1){
							$action_delete = '<a href="javascript:;" class="dropdown-item'.$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('menu/delete/'.$row->menu_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
						}
						$actions = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right">'.@$action_edit.@$action_delete.'</div></div>';
					}else{
						$actions = 'No action';
					}
					$nestedData['number'] = $start;
					$nestedData['menu_class'] = '<i class="'.$row->menu_class.'"></i>';
					$nestedData['menu_name'] = $row->menu_name.$menu_label;
					$nestedData['menu_url'] = $menu_url;
					$nestedData['mparent_name'] = $mparent_name;
					$nestedData['mgroup_name'] = $row->mgroup_name;
					$nestedData['menu_status'] = $menu_status;
					$nestedData['action'] = $actions;
					$data[] = $nestedData;
				}
			}

			$json_data = array(
				'draw' => intval($this->request->getPost('draw')),
				'recordsTotal' => intval($totalData),
				'recordsFiltered' => intval($totalFiltered),
				'data' => $data
			);
			echo json_encode($json_data);
		}else{
			echo json_encode(array());
		}
	}

	public function getColumns()
	{
		$fields = array('menu_name', 'menu_url', 'mparent_name', 'mgroup_name', 'menu_status');
		$columns[]['data'] = 'number';
		$columns[] = array(
			'data' => 'menu_class',
			'className' => 'text-center'
		);
		foreach ($fields as $field) {
			$columns[] = array(
				'data' => $field
			);
		}
		$columns[] = array(
			'data' => 'action',
			'className' => 'text-center'
		);
		echo json_encode($columns); 
	}

	public function view_tree()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' =>  @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'menu_group' => $this->model->getMenuGroup()
			);
			echo view('layout/header', $data);
			echo view('menu/view_menu_tree');
			echo view('layout/footer');
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function getTree()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			if(@$this->request->getGet('group')){
				$mgroup_id = $this->request->getGet('group');
			}else{
				$mgroup_id = 'MG2000001';
			}
			$menu = $this->model->getMenuByGroup($mgroup_id);
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
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

			if($this->request->getPost('parent') == $this->request->getPost('old_parent')){
				if($this->request->getPost('position') > $this->request->getPost('old_position')){
					$checkMenu1 = $this->model->getMenuPosition($this->request->getGet('group'), $mparent_id, $this->request->getPost('old_position'), $this->request->getPost('position'));
					if(@$checkMenu1){
						foreach ($checkMenu1 as $menu1) {
							if($menu1->menu_id != $this->request->getPost('id')){
								$menuData1 = array(
									'menu_position' => ($menu1->menu_position - 1)
								);
								$this->model->updateMenu($menu1->menu_id, $menuData1);
							}
						}
					}
				}else{
					$checkMenu1 = $this->model->getMenuPosition($this->request->getGet('group'), $mparent_id, $this->request->getPost('position'), $this->request->getPost('old_position'));
					if(@$checkMenu1){
						foreach ($checkMenu1 as $menu1) {
							if($menu1->menu_id != $this->request->getPost('id')){
								$menuData1 = array(
									'menu_position' => ($menu1->menu_position + 1)
								);
								$this->model->updateMenu($menu1->menu_id, $menuData1);
							}
						}
					}
				}
			}else{
				$menuLastPosition = $this->model->getMenuLastPosition($this->request->getGet('group'), $mparent_id);
				$checkMenu1 = $this->model->getMenuPosition($this->request->getGet('group'), $mparent_id, $this->request->getPost('position'), $menuLastPosition);
				if(@$checkMenu1){
					foreach ($checkMenu1 as $menu1) {
						if($menu1->menu_id != $this->request->getPost('id')){
							$menuData1 = array(
								'menu_position' => ($menu1->menu_position + 1)
							);
							$this->model->updateMenu($menu1->menu_id, $menuData1);
						}
					}
				}

				if($this->request->getPost('old_parent') != '#'){
					$old_parent = $this->request->getPost('old_parent');
				}else{
					$old_parent = NULL;
				}
				$checkMenu2 = $this->model->getMenuPosition2($this->request->getGet('group'), $old_parent);
				if(@$checkMenu2){
					$menu_position = 0;
					foreach ($checkMenu2 as $menu2) {
						$menuData2 = array(
							'menu_position' => $menu_position
						);
						$this->model->updateMenu($menu2->menu_id, $menuData2);
						$menu_position++;
					}
				}
			}
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function delete($id)
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
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
