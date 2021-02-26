<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\UserModel;

class User extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new UserModel();
		
		$this->email = \Config\Services::email();
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$columns = array(
				0 => 'user_id',
				1 => 'user_id',
				2 => 'first_name',
				3 => 'user_email',
				4 => 'country_calling_code',
				5 => 'level_name',
				6 => 'user_status'
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
					if(@$row->user_photo_name){
						$user_photo = $row->user_id.'/'.$row->user_photo_name;
					}else{
						$user_photo = 'user-0.png';
					}

					if($row->email_verification == 1){
						$email_verification = ' <i class="fa fa-check-circle text-success"></i>';
					}elseif($row->email_verification == 0){
						$email_verification = ' <a href="javascript:;" data-toggle="modal" data-target="#modal-confirm" data-header="Email Verification" data-body="<p>Are you sure? You will send email verification to user.</p>" data-href="'.base_url('user/send_email/'.$row->user_id).'"><i class="fa fa-exclamation-triangle text-warning"></i></a>';
					}else{
						$email_verification = '';
					}

					if($row->phone_verification == 1){
						$phone_verification = ' <i class="fa fa-check-circle text-success"></i>';
					}elseif($row->phone_verification == 0){
						$phone_verification = ' <a href="javascript:;" data-toggle="modal" data-target="#modal-confirm" data-header="Phone Verification" data-body="<p>Are you sure? You will send SMS verification to user.</p>" data-href="'.base_url('user/send_sms/'.$row->user_id).'"><i class="fa fa-exclamation-triangle text-warning"></i></a>';
					}else{
						$phone_verification = '';
					}

					if($row->user_status == 1){
						$user_status = '<span class="text-success">Active</span>';
					}elseif($row->user_status == 0){
						$user_status = '<span class="text-danger">Blocked</span>';
					}else{
						$user_status = '';
					}

					if(@$checkLevel->update == 1){
						$action_edit = '<a href="'.base_url('user/edit/'.$row->user_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
					}
					if(@$checkLevel->delete == 1){
						$action_delete = '<a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('user/delete/'.$row->user_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
					}
					$nestedData['number'] = $start;
					$nestedData['user_photo'] = '<img src="'.base_url('assets/img/user/'.$user_photo).'" class="img-rounded height-30" />';
					$nestedData['full_name'] = $row->first_name.' '.$row->last_name;
					$nestedData['user_email'] = $row->user_email.$email_verification;
					$nestedData['user_phone'] = '+'.$row->country_calling_code.$row->user_phone.$phone_verification;
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
		$columns[] = array(
			'data' => 'user_photo',
			'className' => 'with-img'
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

	public function detail()
	{
		$id = $this->request->getPost('id');
		$data = array(
			'user' => $this->model->getUserDetail($id)
		);
		echo view('user/view_user_detail', $data);
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
			'sub_district_list' => $sub_district_list,
			'zip_code' => ''
		);
		echo json_encode($callback);
	}

	public function get_zip_code()
	{
		$sub_district = $this->model->getSubDistrictById($this->request->getPost('sub_district'));
		$callback = array(
			'zip_code' => $sub_district->zip_code
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
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
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
				'sub_district' => ['label' => 'Sub District', 'rules' => 'required'],
				'user_photo' => ['label' => 'Photo', 'rules' => 'permit_empty|ext_in[user_photo,png,jpg,gif]|max_size[user_photo,2048]']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('user/form_add_user', $data);
				echo view('layout/footer');
			}else{
				$user_id = $this->model->getUserId();
				$user_photo = $this->request->getFile('user_photo');
				if($user_photo != ''){
					if(is_dir('../public/assets/img/user/'.$user_id) == false){
						mkdir('../public/assets/img/user/'.$user_id);
					}
					$user_photo_name = $user_photo->getRandomName();
					$user_photo->move('../public/assets/img/user/'.$user_id, $user_photo_name);
					$user_photo_ext = $user_photo->getClientExtension();
					$user_photo_size = $user_photo->getSize();
				}else{
					$user_photo_name = NULL;
					$user_photo_ext = NULL;
					$user_photo_size = NULL;
				}
				$userData = array(
					'user_id' => $user_id,
					'level_id' => $this->request->getPost('level'),
					'first_name' => $this->request->getPost('first_name'),
					'last_name' => $this->request->getPost('last_name'),
					'user_email' => $this->request->getPost('user_email'),
					'email_verification' => 0,
					'country_calling_code' => $this->request->getPost('country_calling_code'),
					'user_phone' => $this->request->getPost('user_phone'),
					'phone_verification' => 0,
					'user_password' => hash('sha256', $this->request->getPost('user_password')),
					'user_address' => $this->request->getPost('user_address'),
					'sdistrict_id' => $this->request->getPost('sub_district'),
					'user_photo_name' => $user_photo_name,
					'user_photo_ext' => $user_photo_ext,
					'user_photo_size' => $user_photo_size,
					'req_reset_pass' => 0,
					'user_status' => 1
				);
				$this->model->insertUser($userData);

				$userHistoryData = array(
					'uhistory_id' => $this->model->getUserHistoryId(),
					'user_id' => $userData['user_id'],
					'uhistory_action' => 'Register',
					'uhistory_time' => date('Y-m-d H:i:s')
				);
				$this->model->insertUserHistory($userHistoryData);

				$notifData = array(
					'notif_id' => $this->setting->getNotifId(),
					'sender_id' => session('user_id'),
					'recipient_id' => 'U120091600001',
					'notif_class' => 'fa fa-plus media-object bg-silver-darker',
					'notif_title' => 'New user registered',
					'notif_desc' => 'User data with ID:'.$userData['user_id'].' has been added successfully.',
					'notif_url' => 'user?id='.$userData['user_id'],
					'notif_date' => date('Y-m-d H:i:s'),
					'notif_data' => json_encode($userData),
					'is_read' => 0
				);
				$this->setting->insertNotif($notifData);

				$email_message = $this->send_email_verification($user_id);

				session()->setFlashdata($email_message['session_item'], 'User has been added successfully. (SysCode: <a href="'.base_url('user?id='.$userData['user_id']).'" class="alert-link">'.$userData['user_id'].'</a>)'.$email_message['email_msg']);
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$validation = $this->validate([
				'user_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[user_csv]|ext_in[user_csv,csv]|max_size[user_csv,2048]']
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
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
						'email_verification' => 0,
						'country_calling_code' => $row['country_calling_code'],
						'user_phone' => $row['user_phone'],
						'phone_verification' => 0,
						'user_password' => $row['user_password'],
						'user_address' => $row['user_address'],
						'sdistrict_id' => $row['sub_district'],
						'req_reset_pass' => 0,
						'user_status' => 1
					);
					$this->model->insertUser($userData);

					$userHistoryData = array(
						'uhistory_id' => $this->model->getUserHistoryId(),
						'user_id' => $userData['user_id'],
						'uhistory_action' => 'Register',
						'uhistory_time' => date('Y-m-d H:i:s')
					);
					$this->model->insertUserHistory($userHistoryData);

					$email_message = $this->send_email_verification($userData['user_id']);
				}

				$notifData = array(
					'notif_id' => $this->setting->getNotifId(),
					'sender_id' => session('user_id'),
					'recipient_id' => 'U120091600001',
					'notif_class' => 'fas fa-file-csv media-object bg-silver-darker',
					'notif_title' => 'User data import completed',
					'notif_desc' => 'Users has been added successfully.',
					'notif_url' => 'user',
					'notif_date' => date('Y-m-d H:i:s'),
					'notif_data' => json_encode($this->request->getPost('user')),
					'is_read' => 0
				);
				$this->setting->insertNotif($notifData);

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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			$user = $this->model->getUserDetail($id);
			if(@$this->request->getPost('country')){
				$state = $this->model->getState($this->request->getPost('country'));
			}elseif(@$user->country_id){
				$state = $this->model->getState($user->country_id);
			}else{
				$state = NULL;
			}
			if(@$this->request->getPost('state')){
				$city = $this->model->getCity($this->request->getPost('state'));
			}elseif(@$user->state_id){
				$city = $this->model->getCity($user->state_id);
			}else{
				$city = NULL;
			}
			if(@$this->request->getPost('city')){
				$district = $this->model->getDistrict($this->request->getPost('city'));
			}elseif(@$user->city_id){
				$district = $this->model->getDistrict($user->city_id);
			}else{
				$district = NULL;
			}
			if(@$this->request->getPost('district')){
				$sub_district = $this->model->getSubDistrict($this->request->getPost('district'));
			}elseif(@$user->district_id){
				$sub_district = $this->model->getSubDistrict($user->district_id);
			}else{
				$sub_district = NULL;
			}
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'total_notif' => $this->setting->getNotifCount(session('user_id')),
				'notification' => $this->setting->getNotif(session('user_id')),
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'level' => $this->model->getLevel(),
				'country_calling_code' => $this->model->getIddCode(),
				'country' => $this->model->getCountry(),
				'state' => $state,
				'city' => $city,
				'district' => $district,
				'sub_district' => $sub_district,
				'user' => $user
			);
			if($data['user']->user_email == $this->request->getPost('user_email')){
				$user_email_rules = 'required|valid_email';
				$email_verification = $data['user']->email_verification;
			}else{
				$user_email_rules = 'required|valid_email|is_unique[user.user_email]';
				$email_verification = 0;
			}
			if($data['user']->user_phone == $this->request->getPost('user_phone')){
				$user_phone_rules = 'required|numeric';
				$phone_verification = $data['user']->phone_verification;
			}else{
				$user_phone_rules = 'required|numeric|is_unique[user.user_phone]';
				$phone_verification = 0;
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
				'sub_district' => ['label' => 'Sub District', 'rules' => 'required'],
				'user_photo' => ['label' => 'Photo', 'rules' => 'permit_empty|ext_in[user_photo,png,jpg,gif]|max_size[user_photo,2048]']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('user/form_edit_user', $data);
				echo view('layout/footer');
			}else{
				$user_photo = $this->request->getFile('user_photo');
				if($user_photo != ''){
					if(is_dir('../public/assets/img/user/'.$id) == false){
						mkdir('../public/assets/img/user/'.$id);
					}
					$user_photo_name = $user_photo->getRandomName();
					$user_photo->move('../public/assets/img/user/'.$id, $user_photo_name);
					$user_photo_ext = $user_photo->getClientExtension();
					$user_photo_size = $user_photo->getSize();
				}else{
					$user_photo_name = $data['user']->user_photo_name;
					$user_photo_ext = $data['user']->user_photo_ext;
					$user_photo_size = $data['user']->user_photo_size;
				}
				$userData = array(
					'level_id' => $this->request->getPost('level'),
					'first_name' => $this->request->getPost('first_name'),
					'last_name' => $this->request->getPost('last_name'),
					'user_email' => $this->request->getPost('user_email'),
					'email_verification' => $email_verification,
					'country_calling_code' => $this->request->getPost('country_calling_code'),
					'user_phone' => $this->request->getPost('user_phone'),
					'phone_verification' => $phone_verification,
					'user_address' => $this->request->getPost('user_address'),
					'sdistrict_id' => $this->request->getPost('sub_district'),
					'user_photo_name' => $user_photo_name,
					'user_photo_ext' => $user_photo_ext,
					'user_photo_size' => $user_photo_size,
					'user_status' => $this->request->getPost('status')
				);
				$this->model->updateUser($id, $userData);

				$userHistoryData = array(
					'uhistory_id' => $this->model->getUserHistoryId(),
					'user_id' => $id,
					'uhistory_action' => 'Update',
					'uhistory_time' => date('Y-m-d H:i:s')
				);
				$this->model->insertUserHistory($userHistoryData);

				$notifData = array(
					'notif_id' => $this->setting->getNotifId(),
					'sender_id' => session('user_id'),
					'recipient_id' => 'U120091600001',
					'notif_class' => 'fa fa-edit media-object bg-silver-darker',
					'notif_title' => 'Update user data',
					'notif_desc' => 'User data with ID:'.$id.' has been updated successfully.',
					'notif_url' => 'user?id='.$id,
					'notif_date' => date('Y-m-d H:i:s'),
					'notif_data' => json_encode($userData),
					'is_read' => 0
				);
				$this->setting->insertNotif($notifData);

				$email_message = $this->send_email_verification($id);

				session()->setFlashdata($email_message['session_item'], 'User has been updated successfully. (SysCode: <a href="'.base_url('user?id='.$id).'" class="alert-link">'.$id.'</a>)'.$email_message['email_msg']);
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
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->delete == 1){
			$userData = $this->model->getUserById($id);
			$this->model->deleteUser($id);

			$userHistoryData = array(
				'uhistory_id' => $this->model->getUserHistoryId(),
				'user_id' => $id,
				'uhistory_action' => 'Delete',
				'uhistory_time' => date('Y-m-d H:i:s')
			);
			$this->model->insertUserHistory($userHistoryData);

			$notifData = array(
				'notif_id' => $this->setting->getNotifId(),
				'sender_id' => session('user_id'),
				'recipient_id' => 'U120091600001',
				'notif_class' => 'fa fa-trash-alt media-object bg-silver-darker',
				'notif_title' => 'User has been deleted',
				'notif_desc' => 'User data with ID:'.$id.' has been removed successfully.',
				'notif_url' => 'user',
				'notif_date' => date('Y-m-d H:i:s'),
				'notif_data' => json_encode($userData),
				'is_read' => 0
			);
			$this->setting->insertNotif($notifData);

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

			if(@$user->user_email == $checkUser->user_email){
				$userHistoryUpdate = array(
					'user_id' => $user_id
				);
				$this->model->updateUserHistory($checkUser->user_id, $userHistoryUpdate);
			}
		}else{
			$user_id = @$user->user_id;
		}
		$userData = array(
			'user_id' => $user_id,
			'level_id' => @$user->level_id,
			'first_name' => @$user->first_name,
			'last_name' => @$user->last_name,
			'user_email' => @$user->user_email,
			'email_verification' => @$user->email_verification,
			'country_calling_code' => @$user->country_calling_code,
			'user_phone' => @$user->user_phone,
			'phone_verification' => @$user->phone_verification,
			'user_password' => @$user->user_password,
			'user_address' => @$user->user_address,
			'sdistrict_id' => @$user->sdistrict_id,
			'user_photo_name' => @$user->user_photo_name,
			'user_photo_ext' => @$user->user_photo_ext,
			'user_photo_size' => @$user->user_photo_size,
			'req_reset_pass' => @$user->req_reset_pass,
			'user_status' => @$user->user_status
		);
		$this->model->insertUser($userData);

		$userHistoryData = array(
			'uhistory_id' => $this->model->getUserHistoryId(),
			'user_id' => $user_id,
			'uhistory_action' => 'Restore',
			'uhistory_time' => date('Y-m-d H:i:s')
		);
		$this->model->insertUserHistory($userHistoryData);

		$notifData = array(
			'notif_id' => $this->setting->getNotifId(),
			'sender_id' => session('user_id'),
			'recipient_id' => 'U120091600001',
			'notif_class' => 'fas fa-trash-restore media-object bg-silver-darker',
			'notif_title' => 'Restore a deleted user',
			'notif_desc' => 'User data with ID:'.$user_id.' has been restored successfully.',
			'notif_url' => 'user?id='.$user_id,
			'notif_date' => date('Y-m-d H:i:s'),
			'notif_data' => json_encode($userData),
			'is_read' => 0
		);
		$this->setting->insertNotif($notifData);

		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('user?id='.$user_id).'" class="alert-link">'.$user_id.'</a>)');
		return redirect()->to(base_url('user'));
	}

	protected function send_email_verification($id)
	{
		$user = $this->model->getUserById($id);
		if(@$user->email_verification == 0 AND @$user->user_status == 1){
			$config = array(
				'protocol' => @$this->setting->getSettingById(12)->setting_value,
				'SMTPHost' => @$this->setting->getSettingById(13)->setting_value,
				'SMTPUser' => @$this->setting->getSettingById(14)->setting_value,
				'SMTPPass' => @$this->setting->getSettingById(15)->setting_value,
				'SMTPPort' => @$this->setting->getSettingById(16)->setting_value,
				'SMTPCrypto' => @$this->setting->getSettingById(17)->setting_value,
				'mailType' => 'html'
			);

			$this->email->initialize($config);

			$web_title = @$this->setting->getSettingById(1)->setting_value;

			$this->email->setFrom($config['SMTPUser'], $web_title);
			$this->email->setTo(@$user->user_email);
			$this->email->setSubject('['.$web_title.'] Please verify your email address');
			$this->email->setMessage('Hi '.@$user->first_name.' '.@$user->last_name.',<br><br>To confirm that this is your email, please verify your email:<br><br><a href="'.base_url('login/verify_email/'.$id).'">Click here</a><br><br>Thank you,<br>'.$web_title.' Team');
			if($this->email->send()){
				$data = array(
					'session_item' => 'success',
					'email_msg' => ''
				);
			}else{
				$data = array(
					'session_item' => 'warning',
					'email_msg' => '<br><br>'.$this->email->printDebugger(['headers'])
				);
			}
		}else{
			$data = array(
				'session_item' => 'success',
				'email_msg' => ''
			);
		}
		return $data;
	}

	public function send_email($id)
	{
		$email_message = $this->send_email_verification($id);

		if(@$email_message['email_msg']){
			$email_msg = 'Email failed to sent. (SysCode: <a href="'.base_url('user?id='.$id).'" class="alert-link">'.$id.'</a>)'.$email_message['email_msg'];
		}else{
			$email_msg = 'Email sent successfully. (SysCode: <a href="'.base_url('user?id='.$id).'" class="alert-link">'.$id.'</a>)';
		}

		session()->setFlashdata($email_message['session_item'], $email_msg);
		return redirect()->to(base_url('user'));
	}
}
