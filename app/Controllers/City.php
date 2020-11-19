<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\CityModel;

class City extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new CityModel();
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
			echo view('city/view_city');
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
				0 => 'city_id',
				1 => 'city_code',
				2 => 'city_name',
				3 => 'capital_city_code',
				4 => 'capital_city_name',
				5 => 'state_name',
				6 => 'country_name',
				7 => 'city_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getCityCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$city = $this->model->getCity($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$city =  $this->model->searchCity($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchCityCount($search);
			}

			$data = array();
			if(@$city){
				foreach($city as $row){
					$start++;
					if($row->city_status == 1){
						$city_status = '<span class="text-success">Active</span>';
					}elseif($row->city_status == 0){
						$city_status = '<span class="text-danger">Inactive</span>';
					}else{
						$city_status = '';
					}
					if(@$checkLevel->update == 1 OR @$checkLevel->delete == 1){
						if(@$checkLevel->update == 1){
							$action_edit = '<a href="'.base_url('city/edit/'.$row->city_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
						}
						if(@$this->model->getCityRelatedTable('district', $row->city_id)){
							$delete_disabled = ' disabled';
						}else{
							$delete_disabled = '';
						}
						if(@$checkLevel->delete == 1){
							$action_delete = '<a href="javascript:;" class="dropdown-item'.$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('city/delete/'.$row->city_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
						}
						$actions = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right">'.@$action_edit.@$action_delete.'</div></div>';
					}else{
						$actions = 'No action';
					}
					$nestedData['number'] = $start;
					$nestedData['city_code'] = $row->city_code;
					$nestedData['city_name'] = $row->city_name;
					$nestedData['capital_city_code'] = $row->capital_city_code;
					$nestedData['capital_city_name'] = $row->capital_city_name;
					$nestedData['state_name'] = $row->state_name;
					$nestedData['country_name'] = $row->country_name;
					$nestedData['city_status'] = $city_status;
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
		$fields = array('city_code', 'city_name', 'capital_city_code', 'capital_city_name', 'state_name', 'country_name', 'city_status');
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

	public function get_state()
	{
		$state = $this->model->getState($this->request->getPost('country'));
		$state_list = '<option></option>';
		if(@$state){
			foreach ($state as $row) {
				$state_list .= '<option value="'.$row->state_id.'">'.$row->state_name.'</option>';
			}
		}
		$callback = array(
			'state_list' => $state_list,
			'city_list' => '<option></option>',
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
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry(),
				'state' => $state
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city_code' => ['label' => 'Code', 'rules' => 'required|numeric|min_length[4]|max_length[4]|is_unique[city.city_code]'],
				'city_name' => ['label' => 'Name', 'rules' => 'required'],
				'capital_city_code' => ['label' => 'Capital Code', 'rules' => 'permit_empty|alpha|min_length[3]|max_length[3]|is_unique[city.capital_city_code]'],
				'capital_city_name' => ['label' => 'Capital Name', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('city/form_add_city', $data);
				echo view('layout/footer');
			}else{
				$cityData = array(
					'city_id' => $this->model->getCityId(),
					'state_id' => $this->request->getPost('state'),
					'city_code' => $this->request->getPost('city_code'),
					'city_name' => $this->request->getPost('city_name'),
					'capital_city_code' => $this->request->getPost('capital_city_code'),
					'capital_city_name' => $this->request->getPost('capital_city_name'),
					'city_status' => 1
				);
				$this->model->insertCity($cityData);
				session()->setFlashdata('success', 'City has been added successfully. (SysCode: <a href="'.base_url('city?id='.$cityData['city_id']).'" class="alert-link">'.$cityData['city_id'].'</a>)');
				return redirect()->to(base_url('city'));
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
				'city_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[city_csv]|ext_in[city_csv,csv]|max_size[city_csv,2048]']
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
				echo view('city/form_bulk_upload_city');
				echo view('layout/footer');
			}else{
				$city_csv = $this->request->getFile('city_csv')->getTempName();
				$file = file_get_contents($city_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['city'] = array();
				foreach ($lines as $line) {
					$data['city'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('city/form_bulk_upload_city', $data);
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
				'city.*.state' => ['label' => 'State', 'rules' => 'required'],
				'city.*.city_code' => ['label' => 'Code', 'rules' => 'required|numeric|min_length[4]|max_length[4]|is_unique[city.city_code]'],
				'city.*.city_name' => ['label' => 'Name', 'rules' => 'required'],
				'city.*.capital_city_code' => ['label' => 'Capital Code', 'rules' => 'permit_empty|alpha|min_length[3]|max_length[3]|is_unique[city.capital_city_code]'],
				'city.*.capital_city_name' => ['label' => 'Capital Name', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('city/bulk_upload'));
			}else{
				foreach ($this->request->getPost('city') as $row) {
					$cityData = array(
						'city_id' => $this->model->getCityId(),
						'state_id' => $row['state'],
						'city_code' => $row['city_code'],
						'city_name' => $row['city_name'],
						'capital_city_code' => $row['capital_city_code'],
						'capital_city_name' => $row['capital_city_name'],
						'city_status' => 1
					);
					$this->model->insertCity($cityData);
				}
				session()->setFlashdata('success', 'Cities has been added successfully.');
				return redirect()->to(base_url('city'));
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
			$city = $this->model->getCityById($id);
			if(@$this->request->getPost('country')){
				$state = $this->model->getState($this->request->getPost('country'));
			}elseif(@$city->country_id){
				$state = $this->model->getState($city->country_id);
			}else{
				$state = NULL;
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
			if($data['city']->city_code == $this->request->getPost('city_code')){
				$city_code_rules = 'required|numeric|min_length[4]|max_length[4]';
			}else{
				$city_code_rules = 'required|numeric|min_length[4]|max_length[4]|is_unique[city.city_code]';
			}
			if($data['city']->capital_city_code == $this->request->getPost('capital_city_code')){
				$capital_city_code_rules = 'permit_empty|alpha|min_length[3]|max_length[3]';
			}else{
				$capital_city_code_rules = 'permit_empty|alpha|min_length[3]|max_length[3]|is_unique[city.capital_city_code]';
			}
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city_code' => ['label' => 'Code', 'rules' => $city_code_rules],
				'city_name' => ['label' => 'Name', 'rules' => 'required'],
				'capital_city_code' => ['label' => 'Capital Code', 'rules' => $capital_city_code_rules],
				'capital_city_name' => ['label' => 'Capital Name', 'rules' => 'permit_empty'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('city/form_edit_city', $data);
				echo view('layout/footer');
			}else{
				$cityData = array(
					'state_id' => $this->request->getPost('state'),
					'city_code' => $this->request->getPost('city_code'),
					'city_name' => $this->request->getPost('city_name'),
					'capital_city_code' => $this->request->getPost('capital_city_code'),
					'capital_city_name' => $this->request->getPost('capital_city_name'),
					'city_status' => $this->request->getPost('status')
				);
				$this->model->updateCity($id, $cityData);
				session()->setFlashdata('success', 'City has been updated successfully. (SysCode: <a href="'.base_url('city?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('city'));
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
			$cityData = $this->model->getCityById($id);
			$this->model->deleteCity($id);
			session()->setFlashdata('warning', 'City has been removed successfully. <a href="'.base_url('city/undo?data='.json_encode($cityData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('city'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$city = json_decode($this->request->getGet('data'));
		$checkCity = $this->model->getCityById(@$city->city_id);
		if(@$checkCity){
			$city_id = $this->model->getCityId();
		}else{
			$city_id = @$city->city_id;
		}
		$cityData = array(
			'city_id' => $city_id,
			'state_id' => @$city->state_id,
			'city_code' => @$city->city_code,
			'city_name' => @$city->city_name,
			'capital_city_code' => @$city->capital_city_code,
			'capital_city_name' => @$city->capital_city_name,
			'city_status' => @$city->city_status
		);
		$this->model->insertCity($cityData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('city?id='.$city_id).'" class="alert-link">'.$city_id.'</a>)');
		return redirect()->to(base_url('city'));
	}
}
