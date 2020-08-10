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
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$data = array(
				'title' =>  @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'model' => $this->model,
				'city' => $this->model->getCity(),
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('city/view_city', $data);
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
				'state' => $this->model->getState()
			);
			$validation = $this->validate([
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
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$validation = $this->validate([
				'city_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[city_csv]|ext_in[city_csv,csv]|max_size[city_csv,2048]']
			]);
			$data = array(
				'title' => @$checkMenu->menu_name,
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
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
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
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->update == 1){
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'state' => $this->model->getState(),
				'city' => $this->model->getCityById($id)
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
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
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
