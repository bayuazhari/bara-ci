<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\SubDistrictModel;

class Sub_district extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new SubDistrictModel();
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
			echo view('sub_district/view_sub_district', $data);
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
				0 => 'sdistrict_id',
				1 => 'sdistrict_code',
				2 => 'sdistrict_name',
				3 => 'district_name',
				4 => 'city_name',
				5 => 'state_name',
				6 => 'sdistrict_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getSubDistrictCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$sub_district = $this->model->getSubDistrict($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$sub_district =  $this->model->searchSubDistrict($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchSubDistrictCount($search);
			}

			$data = array();
			if(@$sub_district){
				foreach($sub_district as $row){
					$start++;
					if($row->sdistrict_status == 1){
						$sdistrict_status = '<span class="text-success">Active</span>';
					}elseif($row->sdistrict_status == 0){
						$sdistrict_status = '<span class="text-danger">Inactive</span>';
					}else{
						$sdistrict_status = '';
					}
					if(@$checkLevel->update == 1){
						$action_edit = '<a href="'.base_url('sub_district/edit/'.$row->sdistrict_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
					}
					/*if(@$this->model->getSubDistrictRelatedTable('sub_district', $row->sdistrict_id)){ 
						$delete_disabled = 'disabled';
					}*/
					if(@$checkLevel->delete == 1){
						$action_delete = '<a href="javascript:;" class="dropdown-item '.@$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('sub_district/delete/'.$row->sdistrict_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
					}
					$nestedData['number'] = $start;
					$nestedData['sdistrict_code'] = $row->sdistrict_code;
					$nestedData['sdistrict_name'] = $row->sdistrict_name;
					$nestedData['district_name'] = $row->district_name;
					$nestedData['city_name'] = $row->city_name;
					$nestedData['state_name'] = $row->state_name;
					$nestedData['sdistrict_status'] = $sdistrict_status;
					$nestedData['action'] = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right">'.@$action_edit.@$action_delete.'</div></div>';
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
		$fields = array('sdistrict_code', 'sdistrict_name', 'district_name', 'city_name', 'state_name', 'sdistrict_status');
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

	public function get_district()
	{
		$district = $this->model->getDistrict($this->request->getPost('city'));
		$district_list = '<option></option>';
		if(@$district){
			foreach ($district as $row) {
				$district_list .= '<option value="'.$row->district_id.'">'.$row->district_name.'</option>';
			}
		}
		$callback = array(
			'district_list' => $district_list
		);
		echo json_encode($callback);
	}

	public function add()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
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
			if(@$this->request->getPost('city')){
				$district = $this->model->getDistrict($this->request->getPost('city'));
			}else{
				$district = NULL;
			}
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry(),
				'state' => $state,
				'city' => $city,
				'district' => $district
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city' => ['label' => 'City', 'rules' => 'required'],
				'district' => ['label' => 'District', 'rules' => 'required'],
				'sdistrict_code' => ['label' => 'Code', 'rules' => 'required|numeric|min_length[10]|max_length[10]|is_unique[sub_district.sdistrict_code]'],
				'sdistrict_name' => ['label' => 'Name', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('sub_district/form_add_sub_district', $data);
				echo view('layout/footer');
			}else{
				$subDistrictData = array(
					'sdistrict_id' => $this->model->getSubDistrictId(),
					'district_id' => $this->request->getPost('district'),
					'sdistrict_code' => $this->request->getPost('sdistrict_code'),
					'sdistrict_name' => $this->request->getPost('sdistrict_name'),
					'sdistrict_status' => 1
				);
				$this->model->insertSubDistrict($subDistrictData);
				session()->setFlashdata('success', 'Sub district has been added successfully. (SysCode: <a href="'.base_url('sub_district?id='.$subDistrictData['sdistrict_id']).'" class="alert-link">'.$subDistrictData['sdistrict_id'].'</a>)');
				return redirect()->to(base_url('sub_district'));
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
				'sub_district_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[sub_district_csv]|ext_in[sub_district_csv,csv]|max_size[sub_district_csv,2048]']
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
				echo view('sub_district/form_bulk_upload_sub_district');
				echo view('layout/footer');
			}else{
				$sub_district_csv = $this->request->getFile('sub_district_csv')->getTempName();
				$file = file_get_contents($sub_district_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['sub_district'] = array();
				foreach ($lines as $line) {
					$data['sub_district'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('sub_district/form_bulk_upload_sub_district', $data);
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
				'sub_district.*.district' => ['label' => 'District', 'rules' => 'required'],
				'sub_district.*.sdistrict_code' => ['label' => 'Code', 'rules' => 'required|numeric|min_length[10]|max_length[10]|is_unique[sub_district.sdistrict_code]'],
				'sub_district.*.sdistrict_name' => ['label' => 'Name', 'rules' => 'required']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('sub_district/bulk_upload'));
			}else{
				foreach ($this->request->getPost('sub_district') as $row) {
					$subDistrictData = array(
						'sdistrict_id' => $this->model->getSubDistrictId(),
						'district_id' => $row['district'],
						'sdistrict_code' => $row['sdistrict_code'],
						'sdistrict_name' => $row['sdistrict_name'],
						'sdistrict_status' => 1
					);
					$this->model->insertSubDistrict($subDistrictData);
				}
				session()->setFlashdata('success', 'Sub districts has been added successfully.');
				return redirect()->to(base_url('sub_district'));
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
			$sub_district = $this->model->getSubDistrictById($id);
			if(@$this->request->getPost('country')){
				$state = $this->model->getState($this->request->getPost('country'));
			}elseif(@$sub_district->country_id){
				$state = $this->model->getState($sub_district->country_id);
			}else{
				$state = NULL;
			}
			if(@$this->request->getPost('state')){
				$city = $this->model->getCity($this->request->getPost('state'));
			}elseif(@$sub_district->state_id){
				$city = $this->model->getCity($sub_district->state_id);
			}else{
				$city = NULL;
			}
			if(@$this->request->getPost('city')){
				$district = $this->model->getDistrict($this->request->getPost('city'));
			}elseif(@$sub_district->city_id){
				$district = $this->model->getDistrict($sub_district->city_id);
			}else{
				$district = NULL;
			}
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry(),
				'state' => $state,
				'city' => $city,
				'district' => $district,
				'sub_district' => $sub_district
			);
			if($data['sub_district']->sdistrict_code == $this->request->getPost('sdistrict_code')){
				$sub_district_code_rules = 'required|numeric|min_length[10]|max_length[10]';
			}else{
				$sub_district_code_rules = 'required|numeric|min_length[10]|max_length[10]|is_unique[sub_district.sdistrict_code]';
			}
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city' => ['label' => 'City', 'rules' => 'required'],
				'district' => ['label' => 'District', 'rules' => 'required'],
				'sdistrict_code' => ['label' => 'Code', 'rules' => $sub_district_code_rules],
				'sdistrict_name' => ['label' => 'Name', 'rules' => 'required'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('sub_district/form_edit_sub_district', $data);
				echo view('layout/footer');
			}else{
				$subDistrictData = array(
					'district_id' => $this->request->getPost('district'),
					'sdistrict_code' => $this->request->getPost('sdistrict_code'),
					'sdistrict_name' => $this->request->getPost('sdistrict_name'),
					'sdistrict_status' => $this->request->getPost('status')
				);
				$this->model->updateSubDistrict($id, $subDistrictData);
				session()->setFlashdata('success', 'Sub district has been updated successfully. (SysCode: <a href="'.base_url('sub_district?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('sub_district'));
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
			$subDistrictData = $this->model->getSubDistrictById($id);
			$this->model->deleteSubDistrict($id);
			session()->setFlashdata('warning', 'Sub district has been removed successfully. <a href="'.base_url('sub_district/undo?data='.json_encode($subDistrictData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('sub_district'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$sub_district = json_decode($this->request->getGet('data'));
		$checkSubDistrict = $this->model->getSubDistrictById(@$sub_district->sdistrict_id);
		if(@$checkSubDistrict){
			$sdistrict_id = $this->model->getSubDistrictId();
		}else{
			$sdistrict_id = @$sub_district->sdistrict_id;
		}
		$subDistrictData = array(
			'sdistrict_id' => $sdistrict_id,
			'district_id' => @$sub_district->district_id,
			'sdistrict_code' => @$sub_district->sdistrict_code,
			'sdistrict_name' => @$sub_district->sdistrict_name,
			'sdistrict_status' => @$sub_district->sdistrict_status
		);
		$this->model->insertSubDistrict($subDistrictData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('sub_district?id='.$sdistrict_id).'" class="alert-link">'.$sdistrict_id.'</a>)');
		return redirect()->to(base_url('sub_district'));
	}
}
