<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\TimeZoneModel;

class Time_zone extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new TimeZoneModel();
	}

	public function index()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$data = array(
				'title' =>  @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'model' => $this->model,
				'time_zone' => $this->model->getTimeZone(),
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('time_zone/view_time_zone', $data);
			echo view('layout/footer');
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function add()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry()
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'tz_name' => ['label' => 'Name', 'rules' => 'required'],
				'tz_abbr' => ['label' => 'Abbreviation', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('time_zone/form_add_time_zone', $data);
				echo view('layout/footer');
			}else{
				$timeZoneData = array(
					'tz_id' => $this->model->getTimeZoneId(),
					'country_id' => $this->request->getPost('country'),
					'tz_name' => $this->request->getPost('tz_name'),
					'tz_abbr' => $this->request->getPost('tz_abbr'),
					'tz_status' => 1
				);
				$this->model->insertTimeZone($timeZoneData);
				session()->setFlashdata('success', 'Time zone has been added successfully. (SysCode: <a href="'.base_url('time_zone?id='.$timeZoneData['tz_id']).'" class="alert-link">'.$timeZoneData['tz_id'].'</a>)');
				return redirect()->to(base_url('time_zone'));
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
				'time_zone_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[time_zone_csv]|ext_in[time_zone_csv,csv]|max_size[time_zone_csv,2048]']
			]);
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'validation' => $this->validator
			);
			if(!$validation){
				echo view('layout/header', $data);
				echo view('time_zone/form_bulk_upload_time_zone');
				echo view('layout/footer');
			}else{
				$time_zone_csv = $this->request->getFile('time_zone_csv')->getTempName();
				$file = file_get_contents($time_zone_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['time_zone'] = array();
				foreach ($lines as $line) {
					$data['time_zone'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('time_zone/form_bulk_upload_time_zone', $data);
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
				'time_zone.*.country' => ['label' => 'Country', 'rules' => 'required'],
				'time_zone.*.tz_name' => ['label' => 'Name', 'rules' => 'required'],
				'time_zone.*.tz_abbr' => ['label' => 'Abbreviation', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('time_zone/bulk_upload'));
			}else{
				foreach ($this->request->getPost('time_zone') as $row) {
					$timeZoneData = array(
						'tz_id' => $this->model->getTimeZoneId(),
						'country_id' => $row['country'],
						'tz_name' => $row['tz_name'],
						'tz_abbr' => $row['tz_abbr'],
						'tz_status' => 1
					);
					$this->model->insertTimeZone($timeZoneData);
				}
				session()->setFlashdata('success', 'Time zones has been added successfully.');
				return redirect()->to(base_url('time_zone'));
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
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry(),
				'time_zone' => $this->model->getTimeZoneById($id)
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'tz_name' => ['label' => 'Name', 'rules' => 'required'],
				'tz_abbr' => ['label' => 'Abbreviation', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('time_zone/form_edit_time_zone', $data);
				echo view('layout/footer');
			}else{
				$timeZoneData = array(
					'country_id' => $this->request->getPost('country'),
					'tz_name' => $this->request->getPost('tz_name'),
					'tz_abbr' => $this->request->getPost('tz_abbr'),
					'tz_status' => $this->request->getPost('status')
				);
				$this->model->updateTimeZone($id, $timeZoneData);
				session()->setFlashdata('success', 'Time zone has been updated successfully. (SysCode: <a href="'.base_url('time_zone?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('time_zone'));
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
			$timeZoneData = $this->model->getTimeZoneById($id);
			$this->model->deleteTimeZone($id);
			session()->setFlashdata('warning', 'Time zone has been removed successfully. <a href="'.base_url('time_zone/undo?data='.json_encode($timeZoneData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('time_zone'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$time_zone = json_decode($this->request->getGet('data'));
		$checkTimeZone = $this->model->getTimeZoneById(@$time_zone->tz_id);
		if(@$checkTimeZone){
			$tz_id = $this->model->getTimeZoneId();
		}else{
			$tz_id = @$time_zone->tz_id;
		}
		$timeZoneData = array(
			'tz_id' => $tz_id,
			'country_id' => @$time_zone->country_id,
			'tz_name' => @$time_zone->tz_name,
			'tz_abbr' => @$time_zone->tz_abbr,
			'tz_status' => @$time_zone->tz_status
		);
		$this->model->insertTimeZone($timeZoneData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('time_zone?id='.$tz_id).'" class="alert-link">'.$tz_id.'</a>)');
		return redirect()->to(base_url('time_zone'));
	}
}
