<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\PopulationModel;

class Population extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new PopulationModel();
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
				'population' => $this->model->getPopulation(),
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('population/view_population', $data);
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
				'population_source' => ['label' => 'Source', 'rules' => 'required'],
				'population_year' => ['label' => 'Year', 'rules' => 'required'],
				'total_population' => ['label' => 'Total', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('population/form_add_population', $data);
				echo view('layout/footer');
			}else{
				$populationData = array(
					'population_id' => $this->model->getPopulationId(),
					'country_id' => $this->request->getPost('country'),
					'population_source' => $this->request->getPost('population_source'),
					'population_year' => $this->request->getPost('population_year'),
					'total_population' => $this->request->getPost('total_population')
				);
				$this->model->insertPopulation($populationData);
				session()->setFlashdata('success', 'Population has been added successfully. (SysCode: <a href="'.base_url('population?id='.$populationData['population_id']).'" class="alert-link">'.$populationData['population_id'].'</a>)');
				return redirect()->to(base_url('population'));
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
				'population_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[population_csv]|ext_in[population_csv,csv]|max_size[population_csv,2048]']
			]);
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'validation' => $this->validator
			);
			if(!$validation){
				echo view('layout/header', $data);
				echo view('population/form_bulk_upload_population');
				echo view('layout/footer');
			}else{
				$population_csv = $this->request->getFile('population_csv')->getTempName();
				$file = file_get_contents($population_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['population'] = array();
				foreach ($lines as $line) {
					$data['population'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('population/form_bulk_upload_population', $data);
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
				'population.*.country' => ['label' => 'Country', 'rules' => 'required'],
				'population.*.population_source' => ['label' => 'Source', 'rules' => 'required'],
				'population.*.population_year' => ['label' => 'Year', 'rules' => 'required'],
				'population.*.total_population' => ['label' => 'Total', 'rules' => 'required']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('population/bulk_upload'));
			}else{
				foreach ($this->request->getPost('population') as $row) {
					$populationData = array(
						'population_id' => $this->model->getPopulationId(),
						'country_id' => $row['country'],
						'population_source' => $row['population_source'],
						'population_year' => $row['population_year'],
						'total_population' => $row['total_population']
					);
					$this->model->insertPopulation($populationData);
				}
				session()->setFlashdata('success', 'Populations has been added successfully.');
				return redirect()->to(base_url('population'));
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
				'population' => $this->model->getPopulationById($id)
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'population_source' => ['label' => 'Source', 'rules' => 'required'],
				'population_year' => ['label' => 'Year', 'rules' => 'required'],
				'total_population' => ['label' => 'Total', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('population/form_edit_population', $data);
				echo view('layout/footer');
			}else{
				$populationData = array(
					'country_id' => $this->request->getPost('country'),
					'population_source' => $this->request->getPost('population_source'),
					'population_year' => $this->request->getPost('population_year'),
					'total_population' => $this->request->getPost('total_population')
				);
				$this->model->updatePopulation($id, $populationData);
				session()->setFlashdata('success', 'Population has been updated successfully. (SysCode: <a href="'.base_url('population?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('population'));
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
			$populationData = $this->model->getPopulationById($id);
			$this->model->deletePopulation($id);
			session()->setFlashdata('warning', 'Population has been removed successfully. <a href="'.base_url('population/undo?data='.json_encode($populationData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('population'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$population = json_decode($this->request->getGet('data'));
		$checkPopulation = $this->model->getPopulationById(@$population->population_id);
		if(@$checkPopulation){
			$population_id = $this->model->getPopulationId();
		}else{
			$population_id = @$population->population_id;
		}
		$populationData = array(
			'population_id' => $population_id,
			'country_id' => @$population->country_id,
			'population_source' => @$population->population_source,
			'population_year' => @$population->population_year,
			'total_population' => @$population->total_population
		);
		$this->model->insertPopulation($populationData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('population?id='.$population_id).'" class="alert-link">'.$population_id.'</a>)');
		return redirect()->to(base_url('population'));
	}
}
