<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\GeoUnitModel;

class Geo_unit extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new GeoUnitModel();
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
				'geo_unit' => $this->model->getGeoUnit(),
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('geo_unit/view_geo_unit', $data);
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
				'request' => $this->request
			);
			$validation = $this->validate([
				'geo_unit_code' => ['label' => 'Code', 'rules' => 'required|alpha|min_length[2]|max_length[2]|is_unique[geo_unit.geo_unit_code]'],
				'geo_unit_name' => ['label' => 'Name', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('geo_unit/form_add_geo_unit', $data);
				echo view('layout/footer');
			}else{
				$geoUnitData = array(
					'geo_unit_id' => $this->model->getGeoUnitId(),
					'geo_unit_code' => $this->request->getPost('geo_unit_code'),
					'geo_unit_name' => $this->request->getPost('geo_unit_name'),
					'geo_unit_status' => 1
				);
				$this->model->insertGeoUnit($geoUnitData);
				session()->setFlashdata('success', 'Geographical unit has been added successfully. (SysCode: <a href="'.base_url('geo_unit?id='.$geoUnitData['geo_unit_id']).'" class="alert-link">'.$geoUnitData['geo_unit_id'].'</a>)');
				return redirect()->to(base_url('geo_unit'));
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
				'geo_unit_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[geo_unit_csv]|ext_in[geo_unit_csv,csv]|max_size[geo_unit_csv,2048]']
			]);
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'validation' => $this->validator
			);
			if(!$validation){
				echo view('layout/header', $data);
				echo view('geo_unit/form_bulk_upload_geo_unit');
				echo view('layout/footer');
			}else{
				$geo_unit_csv = $this->request->getFile('geo_unit_csv')->getTempName();
				$file = file_get_contents($geo_unit_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['geo_unit'] = array();
				foreach ($lines as $line) {
					$data['geo_unit'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('geo_unit/form_bulk_upload_geo_unit', $data);
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
				'geo_unit.*.geo_unit_code' => ['label' => 'Code', 'rules' => 'required|alpha|min_length[2]|max_length[2]|is_unique[geo_unit.geo_unit_code]'],
				'geo_unit.*.geo_unit_name' => ['label' => 'Name', 'rules' => 'required']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('geo_unit/bulk_upload'));
			}else{
				foreach ($this->request->getPost('geo_unit') as $row) {
					$geoUnitData = array(
						'geo_unit_id' => $this->model->getGeoUnitId(),
						'geo_unit_code' => $row['geo_unit_code'],
						'geo_unit_name' => $row['geo_unit_name'],
						'geo_unit_status' => 1
					);
					$this->model->insertGeoUnit($geoUnitData);
				}
				session()->setFlashdata('success', 'Geographical unit has been added successfully.');
				return redirect()->to(base_url('geo_unit'));
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
				'geo_unit' => $this->model->getGeoUnitById($id)
			);
			if($data['geo_unit']->geo_unit_code == $this->request->getPost('geo_unit_code')){
				$geo_unit_code_rules = 'required|alpha|min_length[2]|max_length[2]';
			}else{
				$geo_unit_code_rules = 'required|alpha|min_length[2]|max_length[2]|is_unique[geo_unit.geo_unit_code]';
			}
			$validation = $this->validate([
				'geo_unit_code' => ['label' => 'Code', 'rules' => $geo_unit_code_rules],
				'geo_unit_name' => ['label' => 'Name', 'rules' => 'required'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('geo_unit/form_edit_geo_unit', $data);
				echo view('layout/footer');
			}else{
				$geoUnitData = array(
					'geo_unit_code' => $this->request->getPost('geo_unit_code'),
					'geo_unit_name' => $this->request->getPost('geo_unit_name'),
					'geo_unit_status' => $this->request->getPost('status')
				);
				$this->model->updateGeoUnit($id, $geoUnitData);
				session()->setFlashdata('success', 'Geographical unit has been updated successfully. (SysCode: <a href="'.base_url('geo_unit?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('geo_unit'));
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
			$geoUnitData = $this->model->getGeoUnitById($id);
			$this->model->deleteGeoUnit($id);
			session()->setFlashdata('warning', 'Geographical unit has been removed successfully. <a href="'.base_url('geo_unit/undo?data='.json_encode($geoUnitData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('geo_unit'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$geo_unit = json_decode($this->request->getGet('data'));
		$checkGeoUnit = $this->model->getGeoUnitById(@$geo_unit->geo_unit_id);
		if(@$checkGeoUnit){
			$geo_unit_id = $this->model->getGeoUnitId();
		}else{
			$geo_unit_id = @$geo_unit->geo_unit_id;
		}
		$geoUnitData = array(
			'geo_unit_id' => $geo_unit_id,
			'geo_unit_code' => @$geo_unit->geo_unit_code,
			'geo_unit_name' => @$geo_unit->geo_unit_name,
			'geo_unit_status' => @$geo_unit->geo_unit_status
		);
		$this->model->insertGeoUnit($geoUnitData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('geo_unit?id='.$geo_unit_id).'" class="alert-link">'.$geo_unit_id.'</a>)');
		return redirect()->to(base_url('geo_unit'));
	}
}
