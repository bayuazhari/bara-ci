<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\LevelModel;

class Level extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new LevelModel();
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
			echo view('level/view_level');
			echo view('layout/footer');
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function getData()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$columns = array(
				0 => 'level_id',
				1 => 'level_name',
				2 => 'menu_name',
				3 => 'level_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getLevelCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$level = $this->model->getLevel($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$level =  $this->model->searchLevel($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchLevelCount($search);
			}

			$data = array();
			if(@$level){
				foreach($level as $row){
					$start++;
					if($row->level_status == 1){
						$level_status = '<span class="text-success">Active</span>';
					}elseif($row->level_status == 0){
						$level_status = '<span class="text-danger">Inactive</span>';
					}else{
						$level_status = '';
					}
					if(@$checkLevel->update == 1){
						$action_edit = '<a href="'.base_url('level/edit/'.$row->level_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
					}
					if(@$this->model->getLevelRelatedTable('user', $row->level_id)){
						$delete_disabled = 'disabled';
					}
					if(@$checkLevel->delete == 1){
						$action_delete = '<a href="javascript:;" class="dropdown-item '.@$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('level/delete/'.$row->level_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
					}
					$nestedData['number'] = $start;
					$nestedData['level_name'] = $row->level_name;
					$nestedData['menu_name'] = $row->menu_name;
					$nestedData['level_status'] = $level_status;
					$nestedData['action'] = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right"><a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#modal-detail" data-id="'.$row->level_id.'" data-href="'.base_url('level/detail/').'"><i class="fa fa-info-circle"></i> Detail</a>'.@$action_edit.@$action_delete.'</div></div>';
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
		$fields = array('level_name', 'menu_name', 'level_status');
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

	public function detail()
	{
		$id = $this->request->getPost('id');
		$data = array(
			'setting' => $this->setting,
			'level' => $this->model->getLevelDetail($id),
			'menu' => $this->model->getMenu()
		);
		echo view('level/view_level_detail', $data);
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
				'menu' => $this->model->getMenu()
			);
			$validation = $this->validate([
				'level_name' => ['label' => 'Name', 'rules' => 'required'],
				'level_role.*.create' => ['label' => 'Create', 'rules' => 'required'],
				'level_role.*.read' => ['label' => 'Read', 'rules' => 'required'],
				'level_role.*.update' => ['label' => 'Update', 'rules' => 'required'],
				'level_role.*.delete' => ['label' => 'Delete', 'rules' => 'required'],
				'menu' => ['label' => 'Menu', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('level/form_add_level', $data);
				echo view('layout/footer');
			}else{
				$levelData = array(
					'level_id' => $this->model->getLevelId(),
					'level_name' => $this->request->getPost('level_name'),
					'level_role' => json_encode($this->request->getPost('level_role')),
					'menu_id' => $this->request->getPost('menu'),
					'level_status' => 1
				);
				$this->model->insertLevel($levelData);
				session()->setFlashdata('success', 'Level has been added successfully. (SysCode: <a href="'.base_url('level?id='.$levelData['level_id']).'" class="alert-link">'.$levelData['level_id'].'</a>)');
				return redirect()->to(base_url('level'));
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
				'menu' => $this->model->getMenu(),
				'level' => $this->model->getLevelById($id)
			);
			$validation = $this->validate([
				'level_name' => ['label' => 'Name', 'rules' => 'required'],
				'level_role.*.create' => ['label' => 'Create', 'rules' => 'required'],
				'level_role.*.read' => ['label' => 'Read', 'rules' => 'required'],
				'level_role.*.update' => ['label' => 'Update', 'rules' => 'required'],
				'level_role.*.delete' => ['label' => 'Delete', 'rules' => 'required'],
				'menu' => ['label' => 'Menu', 'rules' => 'required'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('level/form_edit_level', $data);
				echo view('layout/footer');
			}else{
				$levelData = array(
					'level_name' => $this->request->getPost('level_name'),
					'level_role' => json_encode($this->request->getPost('level_role')),
					'menu_id' => $this->request->getPost('menu'),
					'level_status' => $this->request->getPost('status')
				);
				$this->model->updateLevel($id, $levelData);
				session()->setFlashdata('success', 'Level has been updated successfully. (SysCode: <a href="'.base_url('level?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('level'));
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
			$levelData = $this->model->getLevelById($id);
			$this->model->deleteLevel($id);
			session()->setFlashdata('warning', 'Level has been removed successfully. <a href="'.base_url('level/undo?data='.json_encode($levelData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('level'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$level = json_decode($this->request->getGet('data'));
		$checkLevel = $this->model->getLevelById(@$level->level_id);
		if(@$checkLevel){
			$level_id = $this->model->getLevelId();
		}else{
			$level_id = @$level->level_id;
		}
		$levelData = array(
			'level_id' => $level_id,
			'level_name' => @$level->level_name,
			'level_role' => @$level->level_role,
			'menu_id' => @$level->menu_id,
			'level_status' => @$level->level_status
		);
		$this->model->insertLevel($levelData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('level?id='.$level_id).'" class="alert-link">'.$level_id.'</a>)');
		return redirect()->to(base_url('level'));
	}
}
