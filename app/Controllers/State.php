<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\StateModel;

class State extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new StateModel();
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
			echo view('state/view_state');
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
				0 => 'state_id',
				1 => 'state_iso_code',
				2 => 'state_ref_code',
				3 => 'state_name',
				4 => 'state_capital',
				5 => 'tz_name',
				6 => 'geo_unit_name',
				7 => 'state_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getStateCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$state = $this->model->getState($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$state =  $this->model->searchState($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchStateCount($search);
			}

			$data = array();
			if(@$state){
				foreach($state as $row){
					$start++;
					if($row->state_status == 1){
						$state_status = '<span class="text-success">Active</span>';
					}elseif($row->state_status == 0){
						$state_status = '<span class="text-danger">Inactive</span>';
					}else{
						$state_status = '';
					}
					if(@$checkLevel->update == 1 OR @$checkLevel->delete == 1){
						if(@$checkLevel->update == 1){
							$action_edit = '<a href="'.base_url('state/edit/'.$row->state_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
						}
						if(@$this->model->getStateRelatedTable('city', $row->state_id)){
							$delete_disabled = 'disabled';
						}
						if(@$checkLevel->delete == 1){
							$action_delete = '<a href="javascript:;" class="dropdown-item '.@$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('state/delete/'.$row->state_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
						}
						$actions = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right">'.@$action_edit.@$action_delete.'</div></div>';
					}else{
						$actions = 'No action';
					}
					$nestedData['number'] = $start;
					$nestedData['state_iso_code'] = $row->country_alpha2_code.'-'.$row->state_iso_code;
					$nestedData['state_ref_code'] = $row->state_ref_code;
					$nestedData['state_name'] = $row->state_name;
					$nestedData['state_capital'] = $row->state_capital;
					$nestedData['tz_name'] = $row->tz_name;
					$nestedData['geo_unit_name'] = $row->geo_unit_name;
					$nestedData['state_status'] = $state_status;
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
		$fields = array('state_iso_code', 'state_ref_code', 'state_name', 'state_capital', 'tz_name', 'geo_unit_name', 'state_status');
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

	public function get_time_zone()
	{
		$time_zone = $this->model->getTimeZone($this->request->getPost('country'));
		$time_zone_list = '<option></option>';
		if(@$time_zone){
			foreach ($time_zone as $row) {
				$time_zone_list .= '<option value="'.$row->tz_id.'">'.$row->tz_name.'</option>';
			}
		}
		$country = $this->model->getCountryByField('country_id', $this->request->getPost('country'));
		$callback = array(
			'time_zone_list' => $time_zone_list,
			'country_code' => @$country->country_alpha2_code.' -'
		);
		echo json_encode($callback);
	}

	public function add()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole(session('level_id'), @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			if(@$this->request->getPost('country')){
				$time_zone = $this->model->getTimeZone($this->request->getPost('country'));
				$iso_prefix_code = @$this->model->getCountryByField('country_id', $this->request->getPost('country'))->country_alpha2_code.' -';
			}else{
				$time_zone = NULL;
				$iso_prefix_code = NULL;
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
				'iso_prefix_code' => $iso_prefix_code,
				'time_zone' => $time_zone,
				'geo_unit' => $this->model->getGeoUnit()
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'time_zone' => ['label' => 'Time Zone', 'rules' => 'required'],
				'geo_unit' => ['label' => 'Geographical Unit', 'rules' => 'required'],
				'state_iso_code' => ['label' => 'ISO Code', 'rules' => 'required|alpha|min_length[2]|max_length[2]|is_unique[state.state_iso_code]'],
				'state_ref_code' => ['label' => 'Numeric Code', 'rules' => 'required|numeric|min_length[2]|max_length[2]|is_unique[state.state_ref_code]'],
				'state_name' => ['label' => 'Name', 'rules' => 'required'],
				'state_capital' => ['label' => 'Capital', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('state/form_add_state', $data);
				echo view('layout/footer');
			}else{
				$stateData = array(
					'state_id' => $this->model->getStateId(),
					'tz_id' => $this->request->getPost('time_zone'),
					'geo_unit_id' => $this->request->getPost('geo_unit'),
					'state_iso_code' => $this->request->getPost('state_iso_code'),
					'state_ref_code' => $this->request->getPost('state_ref_code'),
					'state_name' => $this->request->getPost('state_name'),
					'state_capital' => $this->request->getPost('state_capital'),
					'state_status' => 1
				);
				$this->model->insertState($stateData);
				session()->setFlashdata('success', 'State has been added successfully. (SysCode: <a href="'.base_url('state?id='.$stateData['state_id']).'" class="alert-link">'.$stateData['state_id'].'</a>)');
				return redirect()->to(base_url('state'));
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
				'state_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[state_csv]|ext_in[state_csv,csv]|max_size[state_csv,2048]']
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
				echo view('state/form_bulk_upload_state');
				echo view('layout/footer');
			}else{
				$state_csv = $this->request->getFile('state_csv')->getTempName();
				$file = file_get_contents($state_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['state'] = array();
				foreach ($lines as $line) {
					$data['state'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('state/form_bulk_upload_state', $data);
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
				'state.*.time_zone' => ['label' => 'Time Zone', 'rules' => 'required'],
				'state.*.geo_unit' => ['label' => 'Geographical Unit', 'rules' => 'required'],
				'state.*.state_iso_code' => ['label' => 'ISO Code', 'rules' => 'required|alpha|min_length[2]|max_length[2]|is_unique[state.state_iso_code]'],
				'state.*.state_ref_code' => ['label' => 'Numeric Code', 'rules' => 'required|numeric|min_length[2]|max_length[2]|is_unique[state.state_ref_code]'],
				'state.*.state_name' => ['label' => 'Name', 'rules' => 'required'],
				'state.*.state_capital' => ['label' => 'Capital', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('state/bulk_upload'));
			}else{
				foreach ($this->request->getPost('state') as $row) {
					$stateData = array(
						'state_id' => $this->model->getStateId(),
						'tz_id' => $row['time_zone'],
						'geo_unit_id' => $row['geo_unit'],
						'state_iso_code' => $row['state_iso_code'],
						'state_ref_code' => $row['state_ref_code'],
						'state_name' => $row['state_name'],
						'state_capital' => $row['state_capital'],
						'state_status' => 1
					);
					$this->model->insertState($stateData);
				}
				session()->setFlashdata('success', 'States has been added successfully.');
				return redirect()->to(base_url('state'));
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
			$state = $this->model->getStateById($id);
			if(@$this->request->getPost('country')){
				$time_zone = $this->model->getTimeZone($this->request->getPost('country'));
				$iso_prefix_code = @$this->model->getCountryByField('country_id', $this->request->getPost('country'))->country_alpha2_code.' -';
			}elseif(@$state->country_id){
				$time_zone = $this->model->getTimeZone($state->country_id);
				$iso_prefix_code = @$this->model->getCountryByField('country_id', $state->country_id)->country_alpha2_code.' -';
			}else{
				$time_zone = NULL;
				$iso_prefix_code = NULL;
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
				'iso_prefix_code' => $iso_prefix_code,
				'time_zone' => $time_zone,
				'geo_unit' => $this->model->getGeoUnit(),
				'state' => $state
			);
			if($data['state']->state_iso_code == $this->request->getPost('state_iso_code')){
				$state_iso_code_rules = 'required|alpha|min_length[2]|max_length[2]';
			}else{
				$state_iso_code_rules = 'required|alpha|min_length[2]|max_length[2]|is_unique[state.state_iso_code]';
			}
			if($data['state']->state_ref_code == $this->request->getPost('state_ref_code')){
				$state_ref_code_rules = 'required|numeric|min_length[2]|max_length[2]';
			}else{
				$state_ref_code_rules = 'required|numeric|min_length[2]|max_length[2]|is_unique[state.state_ref_code]';
			}
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'time_zone' => ['label' => 'Time Zone', 'rules' => 'required'],
				'geo_unit' => ['label' => 'Geographical Unit', 'rules' => 'required'],
				'state_iso_code' => ['label' => 'Alpha-2 Code', 'rules' => $state_iso_code_rules],
				'state_ref_code' => ['label' => 'Numeric Code', 'rules' => $state_ref_code_rules],
				'state_name' => ['label' => 'Name', 'rules' => 'required'],
				'state_capital' => ['label' => 'Capital', 'rules' => 'permit_empty'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('state/form_edit_state', $data);
				echo view('layout/footer');
			}else{
				$stateData = array(
					'tz_id' => $this->request->getPost('time_zone'),
					'geo_unit_id' => $this->request->getPost('geo_unit'),
					'state_iso_code' => $this->request->getPost('state_iso_code'),
					'state_ref_code' => $this->request->getPost('state_ref_code'),
					'state_name' => $this->request->getPost('state_name'),
					'state_capital' => $this->request->getPost('state_capital'),
					'state_status' => $this->request->getPost('status')
				);
				$this->model->updateState($id, $stateData);
				session()->setFlashdata('success', 'State has been updated successfully. (SysCode: <a href="'.base_url('state?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('state'));
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
			$stateData = $this->model->getStateById($id);
			$this->model->deleteState($id);
			session()->setFlashdata('warning', 'State has been removed successfully. <a href="'.base_url('state/undo?data='.json_encode($stateData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('state'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$state = json_decode($this->request->getGet('data'));
		$checkState = $this->model->getStateById(@$state->state_id);
		if(@$checkState){
			$state_id = $this->model->getStateId();
		}else{
			$state_id = @$state->state_id;
		}
		$stateData = array(
			'state_id' => $state_id,
			'tz_id' => @$state->tz_id,
			'geo_unit_id' => @$state->geo_unit_id,
			'state_iso_code' => @$state->state_iso_code,
			'state_ref_code' => @$state->state_ref_code,
			'state_name' => @$state->state_name,
			'state_capital' => @$state->state_capital,
			'state_status' => @$state->state_status
		);
		$this->model->insertState($stateData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('state?id='.$state_id).'" class="alert-link">'.$state_id.'</a>)');
		return redirect()->to(base_url('state'));
	}
}
