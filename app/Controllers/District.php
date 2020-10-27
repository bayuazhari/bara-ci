<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\DistrictModel;

class District extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new DistrictModel();
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
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('district/view_district');
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
				0 => 'district_id',
				1 => 'district_code',
				2 => 'district_name',
				3 => 'city_name',
				4 => 'state_name',
				5 => 'country_name',
				6 => 'district_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getDistrictCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$district = $this->model->getDistrict($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$district =  $this->model->searchDistrict($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchDistrictCount($search);
			}

			$data = array();
			if(@$district){
				foreach($district as $row){
					$start++;
					if($row->district_status == 1){
						$district_status = '<span class="text-success">Active</span>';
					}elseif($row->district_status == 0){
						$district_status = '<span class="text-danger">Inactive</span>';
					}else{
						$district_status = '';
					}
					if(@$checkLevel->update == 1 OR @$checkLevel->delete == 1){
						if(@$checkLevel->update == 1){
							$action_edit = '<a href="'.base_url('district/edit/'.$row->district_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
						}
						if(@$this->model->getDistrictRelatedTable('sub_district', $row->district_id)){
							$delete_disabled = 'disabled';
						}
						if(@$checkLevel->delete == 1){
							$action_delete = '<a href="javascript:;" class="dropdown-item '.@$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('district/delete/'.$row->district_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
						}
						$actions = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right">'.@$action_edit.@$action_delete.'</div></div>';
					}else{
						$actions = 'No action';
					}
					$nestedData['number'] = $start;
					$nestedData['district_code'] = $row->district_code;
					$nestedData['district_name'] = $row->district_name;
					$nestedData['city_name'] = $row->city_name;
					$nestedData['state_name'] = $row->state_name;
					$nestedData['country_name'] = $row->country_name;
					$nestedData['district_status'] = $district_status;
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
		$fields = array('district_code', 'district_name', 'city_name', 'state_name', 'country_name', 'district_status');
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

	public function get_city()
	{
		$city = $this->model->getCity($this->request->getPost('state'));
		$city_list = '<option></option>';
		if(@$city){
			foreach ($city as $row) {
				$city_list .= '<option value="'.$row->city_id.'">'.$row->city_name.'</option>';
			}
		}
		$callback = array(
			'city_list' => $city_list,
			'district_list' => '<option></option>',
			'sub_district_list' => '<option></option>',
			'zip_code' => ''
		);
		echo json_encode($callback);
	}

	public function add()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			if(@$this->request->getPost('country')){
				$state = $this->model->getState($this->request->getPost('country'));
			}else{
				$state = NULL;
			}
			if(@$this->request->getPost('state')){
				$city = $this->model->getCity($this->request->getPost('state'));
			}else{
				$city = NULL;
			}
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry(),
				'state' => $state,
				'city' => $city
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city' => ['label' => 'City', 'rules' => 'required'],
				'district_code' => ['label' => 'Code', 'rules' => 'required|numeric|min_length[6]|max_length[6]|is_unique[district.district_code]'],
				'district_name' => ['label' => 'Name', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('district/form_add_district', $data);
				echo view('layout/footer');
			}else{
				$districtData = array(
					'district_id' => $this->model->getDistrictId(),
					'city_id' => $this->request->getPost('city'),
					'district_code' => $this->request->getPost('district_code'),
					'district_name' => $this->request->getPost('district_name'),
					'district_status' => 1
				);
				$this->model->insertDistrict($districtData);
				session()->setFlashdata('success', 'District has been added successfully. (SysCode: <a href="'.base_url('district?id='.$districtData['district_id']).'" class="alert-link">'.$districtData['district_id'].'</a>)');
				return redirect()->to(base_url('district'));
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
				'district_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[district_csv]|ext_in[district_csv,csv]|max_size[district_csv,2048]']
			]);
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'validation' => $this->validator
			);
			if(!$validation){
				echo view('layout/header', $data);
				echo view('district/form_bulk_upload_district');
				echo view('layout/footer');
			}else{
				$district_csv = $this->request->getFile('district_csv')->getTempName();
				$file = file_get_contents($district_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['district'] = array();
				foreach ($lines as $line) {
					$data['district'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('district/form_bulk_upload_district', $data);
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
				'district.*.city' => ['label' => 'City', 'rules' => 'required'],
				'district.*.district_code' => ['label' => 'Code', 'rules' => 'required|numeric|min_length[6]|max_length[6]|is_unique[district.district_code]'],
				'district.*.district_name' => ['label' => 'Name', 'rules' => 'required']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('district/bulk_upload'));
			}else{
				foreach ($this->request->getPost('district') as $row) {
					$districtData = array(
						'district_id' => $this->model->getDistrictId(),
						'city_id' => $row['city'],
						'district_code' => $row['district_code'],
						'district_name' => $row['district_name'],
						'district_status' => 1
					);
					$this->model->insertDistrict($districtData);
				}
				session()->setFlashdata('success', 'Districts has been added successfully.');
				return redirect()->to(base_url('district'));
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
			$district = $this->model->getDistrictById($id);
			if(@$this->request->getPost('country')){
				$state = $this->model->getState($this->request->getPost('country'));
			}elseif(@$district->country_id){
				$state = $this->model->getState($district->country_id);
			}else{
				$state = NULL;
			}
			if(@$this->request->getPost('state')){
				$city = $this->model->getCity($this->request->getPost('state'));
			}elseif(@$district->state_id){
				$city = $this->model->getCity($district->state_id);
			}else{
				$city = NULL;
			}
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry(),
				'state' => $state,
				'city' => $city,
				'district' => $district
			);
			if($data['district']->district_code == $this->request->getPost('district_code')){
				$district_code_rules = 'required|numeric|min_length[6]|max_length[6]';
			}else{
				$district_code_rules = 'required|numeric|min_length[6]|max_length[6]|is_unique[district.district_code]';
			}
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city' => ['label' => 'City', 'rules' => 'required'],
				'district_code' => ['label' => 'Code', 'rules' => $district_code_rules],
				'district_name' => ['label' => 'Name', 'rules' => 'required'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('district/form_edit_district', $data);
				echo view('layout/footer');
			}else{
				$districtData = array(
					'city_id' => $this->request->getPost('city'),
					'district_code' => $this->request->getPost('district_code'),
					'district_name' => $this->request->getPost('district_name'),
					'district_status' => $this->request->getPost('status')
				);
				$this->model->updateDistrict($id, $districtData);
				session()->setFlashdata('success', 'District has been updated successfully. (SysCode: <a href="'.base_url('district?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('district'));
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
			$districtData = $this->model->getDistrictById($id);
			$this->model->deleteDistrict($id);
			session()->setFlashdata('warning', 'District has been removed successfully. <a href="'.base_url('district/undo?data='.json_encode($districtData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('district'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$district = json_decode($this->request->getGet('data'));
		$checkDistrict = $this->model->getDistrictById(@$district->district_id);
		if(@$checkDistrict){
			$district_id = $this->model->getDistrictId();
		}else{
			$district_id = @$district->district_id;
		}
		$districtData = array(
			'district_id' => $district_id,
			'city_id' => @$district->city_id,
			'district_code' => @$district->district_code,
			'district_name' => @$district->district_name,
			'district_status' => @$district->district_status
		);
		$this->model->insertDistrict($districtData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('district?id='.$district_id).'" class="alert-link">'.$district_id.'</a>)');
		return redirect()->to(base_url('district'));
	}
}
