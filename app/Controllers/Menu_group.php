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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' =>  @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('menu_group/view_menu_group');
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
				0 => 'mgroup_id',
				1 => 'mgroup_name',
				2 => 'mgroup_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getMenuGroupCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$menu_group = $this->model->getMenuGroup($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$menu_group =  $this->model->searchMenuGroup($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchMenuGroupCount($search);
			}

			$data = array();
			if(@$menu_group){
				foreach($menu_group as $row){
					$start++;
					if($row->mgroup_status == 1){
						$mgroup_status = '<span class="text-success">Active</span>';
					}elseif($row->mgroup_status == 0){
						$mgroup_status = '<span class="text-danger">Inactive</span>';
					}else{
						$mgroup_status = '';
					}
					if(@$checkLevel->update == 1 OR @$checkLevel->delete == 1){
						if(@$checkLevel->update == 1){
							$action_edit = '<a href="'.base_url('menu_group/edit/'.$row->mgroup_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
						}
						if(@$this->model->getMenuGroupRelatedTable('menu', $row->mgroup_id)){
							$delete_disabled = 'disabled';
						}
						if(@$checkLevel->delete == 1){
							$action_delete = '<a href="javascript:;" class="dropdown-item '.@$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('menu_group/delete/'.$row->mgroup_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
						}
						$actions = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right">'.@$action_edit.@$action_delete.'</div></div>';
					}else{
						$actions = 'No action';
					}
					$nestedData['number'] = $start;
					$nestedData['mgroup_name'] = $row->mgroup_name;
					$nestedData['mgroup_status'] = $mgroup_status;
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
		$fields = array('mgroup_name', 'mgroup_status');
		$columns[]['data'] = 'number';
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

	public function add()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$validation = $this->validate([
				'menu_group_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[menu_group_csv]|ext_in[menu_group_csv,csv]|max_size[menu_group_csv,2048]']
			]);
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
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
