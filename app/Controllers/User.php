<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\UserModel;

class User extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new UserModel();
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
			echo view('user/view_user');
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
				0 => 'user_id',
				1 => 'first_name',
				2 => 'user_email',
				3 => 'country_calling_code',
				4 => 'level_name',
				5 => 'user_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getUserCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$user = $this->model->getUser($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$user =  $this->model->searchUser($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchUserCount($search);
			}

			$data = array();
			if(@$user){
				foreach($user as $row){
					$start++;
					if($row->user_status == 1){
						$user_status = '<span class="text-success">Active</span>';
					}elseif($row->user_status == 0){
						$user_status = '<span class="text-danger">Inactive</span>';
					}else{
						$user_status = '';
					}
					if(@$checkLevel->update == 1){
						$action_edit = '<a href="'.base_url('user/edit/'.$row->user_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
					}
					if(@$this->model->getUserRelatedTable2('notification', 'sender_id', 'recipient_id', $row->user_id)){
						$delete_disabled = 'disabled';
					}
					if(@$checkLevel->delete == 1){
						$action_delete = '<a href="javascript:;" class="dropdown-item '.@$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('user/delete/'.$row->user_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
					}
					$nestedData['number'] = $start;
					$nestedData['full_name'] = $row->first_name.' '.$row->last_name;
					$nestedData['user_email'] = $row->user_email;
					$nestedData['user_phone'] = '+'.$row->country_calling_code.$row->user_phone;
					$nestedData['level_name'] = $row->level_name;
					$nestedData['user_status'] = $user_status;
					$nestedData['action'] = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right"><a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#modal-detail" data-id="'.$row->user_id.'" data-href="'.base_url('user/detail/').'"><i class="fa fa-info-circle"></i> Detail</a>'.@$action_edit.@$action_delete.'</div></div>';
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
		$fields = array('full_name', 'user_email', 'user_phone', 'level_name', 'user_status');
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
			'user' => $this->model->getUserDetail($id)
		);
		echo view('country/view_user_detail', $data);
	}

	public function get_sub_district()
	{
		$sub_district = $this->model->getSubDistrict($this->request->getPost('district'));
		$sub_district_list = '<option></option>';
		if(@$sub_district){
			foreach ($sub_district as $row) {
				$sub_district_list .= '<option value="'.$row->sdistrict_id.'">'.$row->sdistrict_name.'</option>';
			}
		}
		$callback = array(
			'sub_district_list' => $sub_district_list
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
			if(@$this->request->getPost('district')){
				$sub_district = $this->model->getSubDistrict($this->request->getPost('district'));
			}else{
				$sub_district = NULL;
			}
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'level' => $this->model->getLevel(),
				'country_calling_code' => $this->model->getIddCode(),
				'country' => $this->model->getCountry(),
				'state' => $state,
				'city' => $city,
				'district' => $district,
				'sub_district' => $sub_district
			);
			$validation = $this->validate([
				'level' => ['label' => 'Level', 'rules' => 'required'],
				'first_name' => ['label' => 'First Name', 'rules' => 'required'],
				'last_name' => ['label' => 'Last Name', 'rules' => 'required'],
				'user_email' => ['label' => 'Email', 'rules' => 'required|valid_email|is_unique[user.user_email]'],
				'country_calling_code' => ['label' => 'Calling Code', 'rules' => 'required|numeric'],
				'user_phone' => ['label' => 'Phone', 'rules' => 'required|numeric|is_unique[user.user_phone]'],
				'user_password' => ['label' => 'Password', 'rules' => 'required|min_length[6]|matches[user_repassword]'],
				'user_repassword' => ['label' => 'Confirm Password', 'rules' => 'required|min_length[6]'],
				'user_address' => ['label' => 'Address', 'rules' => 'required'],
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city' => ['label' => 'City', 'rules' => 'required'],
				'district' => ['label' => 'District', 'rules' => 'required'],
				'sub_district' => ['label' => 'Sub District', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('user/form_add_user', $data);
				echo view('layout/footer');
			}else{
				$userData = array(
					'user_id' => $this->model->getUserId(),
					'level_id' => $this->request->getPost('level'),
					'first_name' => $this->request->getPost('first_name'),
					'last_name' => $this->request->getPost('last_name'),
					'user_email' => $this->request->getPost('user_email'),
					'email_activation' => 0,
					'country_calling_code' => $this->request->getPost('country_calling_code'),
					'user_phone' => $this->request->getPost('user_phone'),
					'phone_activation' => 0,
					'user_password' => $this->request->getPost('user_password'),
					'user_address' => $this->request->getPost('user_address'),
					'sdistrict_id' => $this->request->getPost('sub_district'),
					'registration_date' => date('Y-m-d H:i:s'),
					'req_reset_pass' => 0,
					'user_status' => 1
				);
				$this->model->insertUser($userData);
				session()->setFlashdata('success', 'User has been added successfully. (SysCode: <a href="'.base_url('user?id='.$userData['user_id']).'" class="alert-link">'.$userData['user_id'].'</a>)');
				return redirect()->to(base_url('user'));
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
				'user_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[user_csv]|ext_in[user_csv,csv]|max_size[user_csv,2048]']
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
				echo view('user/form_bulk_upload_user');
				echo view('layout/footer');
			}else{
				$user_csv = $this->request->getFile('user_csv')->getTempName();
				$file = file_get_contents($user_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['user'] = array();
				foreach ($lines as $line) {
					$data['user'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('user/form_bulk_upload_user', $data);
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
				'user.*.level' => ['label' => 'Level', 'rules' => 'required'],
				'user.*.first_name' => ['label' => 'First Name', 'rules' => 'required'],
				'user.*.last_name' => ['label' => 'Last Name', 'rules' => 'required'],
				'user.*.user_email' => ['label' => 'Email', 'rules' => 'required|valid_email|is_unique[user.user_email]'],
				'user.*.country_calling_code' => ['label' => 'Calling Code', 'rules' => 'required|numeric'],
				'user.*.user_phone' => ['label' => 'Phone', 'rules' => 'required|numeric|is_unique[user.user_phone]'],
				'user.*.user_password' => ['label' => 'Password', 'rules' => 'required|min_length[6]'],
				'user.*.user_address' => ['label' => 'Address', 'rules' => 'required'],
				'user.*.sub_district' => ['label' => 'Sub District', 'rules' => 'required']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('user/bulk_upload'));
			}else{
				foreach ($this->request->getPost('user') as $row) {
					$userData = array(
						'user_id' => $this->model->getUserId(),
						'level_id' => $row['level'],
						'first_name' => $row['first_name'],
						'last_name' => $row['last_name'],
						'user_email' => $row['user_email'],
						'email_activation' => 0,
						'country_calling_code' => $row['country_calling_code'],
						'user_phone' => $row['user_phone'],
						'phone_activation' => 0,
						'user_password' => $row['user_password'],
						'user_address' => $row['user_address'],
						'sdistrict_id' => $row['sub_district'],
						'registration_date' => date('Y-m-d H:i:s'),
						'req_reset_pass' => 0,
						'user_status' => 1
					);
					$this->model->insertUser($userData);
				}
				session()->setFlashdata('success', 'Users has been added successfully.');
				return redirect()->to(base_url('user'));
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
			$user = $this->model->getUserDetail($id);
			if(@$this->request->getPost('country')){
				$state = $this->model->getState($this->request->getPost('country'));
			}elseif(@$user->country_id){
				$state = $this->model->getState($sub_district->country_id);
			}else{
				$state = NULL;
			}
			if(@$this->request->getPost('state')){
				$city = $this->model->getCity($this->request->getPost('state'));
			}elseif(@$user->state_id){
				$city = $this->model->getCity($sub_district->state_id);
			}else{
				$city = NULL;
			}
			if(@$this->request->getPost('city')){
				$district = $this->model->getDistrict($this->request->getPost('city'));
			}elseif(@$user->city_id){
				$district = $this->model->getDistrict($sub_district->city_id);
			}else{
				$district = NULL;
			}
			if(@$this->request->getPost('district')){
				$sub_district = $this->model->getSubDistrict($this->request->getPost('district'));
			}elseif(@$user->district_id){
				$sub_district = $this->model->getSubDistrict($sub_district->district_id);
			}else{
				$sub_district = NULL;
			}
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'level' => $this->model->getLevel(),
				'country_calling_code' => $this->model->getIddCode(),
				'country' => $this->model->getCountry(),
				'state' => $state,
				'city' => $city,
				'district' => $district,
				'sub_district' => $sub_district,
				'user' => $this->model->getUserById($id)
			);
			if($data['user']->user_email == $this->request->getPost('user_email')){
				$user_email_rules = 'required|valid_email';
				$email_activation = $data['user']->email_activation;
			}else{
				$user_email_rules = 'required|valid_email|is_unique[user.user_email]';
				$email_activation = 0;
			}
			if($data['user']->user_phone == $this->request->getPost('user_phone')){
				$user_phone_rules = 'required|numeric';
				$phone_activation = $data['user']->phone_activation;
			}else{
				$user_phone_rules = 'required|numeric|is_unique[user.user_phone]';
				$phone_activation = 0;
			}
			$validation = $this->validate([
				'level' => ['label' => 'Level', 'rules' => 'required'],
				'first_name' => ['label' => 'First Name', 'rules' => 'required'],
				'last_name' => ['label' => 'Last Name', 'rules' => 'required'],
				'user_email' => ['label' => 'Email', 'rules' => $user_email_rules],
				'country_calling_code' => ['label' => 'Calling Code', 'rules' => 'required|numeric'],
				'user_phone' => ['label' => 'Phone', 'rules' => $user_phone_rules],
				'user_address' => ['label' => 'Address', 'rules' => 'required'],
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'state' => ['label' => 'State', 'rules' => 'required'],
				'city' => ['label' => 'City', 'rules' => 'required'],
				'district' => ['label' => 'District', 'rules' => 'required'],
				'sub_district' => ['label' => 'Sub District', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('user/form_edit_user', $data);
				echo view('layout/footer');
			}else{
				$userData = array(
					'level_id' => $this->request->getPost('level'),
					'first_name' => $this->request->getPost('first_name'),
					'last_name' => $this->request->getPost('last_name'),
					'user_email' => $this->request->getPost('user_email'),
					'email_activation' => $email_activation,
					'country_calling_code' => $this->request->getPost('country_calling_code'),
					'user_phone' => $this->request->getPost('user_phone'),
					'phone_activation' => $phone_activation,
					'user_address' => $this->request->getPost('user_address'),
					'sdistrict_id' => $this->request->getPost('sub_district'),
					'user_status' => $this->request->getPost('status')
				);
				$this->model->updateUser($id, $userData);
				session()->setFlashdata('success', 'User has been updated successfully. (SysCode: <a href="'.base_url('user?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('user'));
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
			$userData = $this->model->getUserById($id);
			$this->model->deleteUser($id);
			session()->setFlashdata('warning', 'User has been removed successfully. <a href="'.base_url('user/undo?data='.json_encode($userData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('user'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$user = json_decode($this->request->getGet('data'));
		$checkUser = $this->model->getUserById(@$user->user_id);
		if(@$checkUser){
			$user_id = $this->model->getUserId();
		}else{
			$user_id = @$user->user_id;
		}
		$userData = array(
			'user_id' => $user_id,
			'level_id' => @$user->level_id,
			'first_name' => @$user->first_name,
			'last_name' => @$user->last_name,
			'user_email' => @$user->user_email,
			'email_activation' => @$user->email_activation,
			'country_calling_code' => @$user->country_calling_code,
			'user_phone' => @$user->user_phone,
			'phone_activation' => @$user->phone_activation,
			'user_password' => @$user->user_password,
			'user_address' => @$user->user_address,
			'sdistrict_id' => @$user->sdistrict_id,
			'registration_date' => @$user->registration_date,
			'req_reset_pass' => @$user->req_reset_pass,
			'user_status' => @$user->user_status
		);
		$this->model->insertUser($userData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('user?id='.$user_id).'" class="alert-link">'.$user_id.'</a>)');
		return redirect()->to(base_url('user'));
	}
}
