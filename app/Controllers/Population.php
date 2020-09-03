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
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' =>  @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
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

	public function getData()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->read == 1){
			$columns = array(
				0 => 'population_id',
				1 => 'population_source',
				2 => 'population_year',
				3 => 'country_name',
				4 => 'total_population'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getPopulationCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$population = $this->model->getPopulation($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$population =  $this->model->searchPopulation($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchPopulationCount($search);
			}

			$data = array();
			if(@$population){
				foreach($population as $row){
					$start++;
					if(@$checkLevel->update == 1 OR @$checkLevel->delete == 1){
						if(@$checkLevel->update == 1){
							$action_edit = '<a href="'.base_url('population/edit/'.$row->population_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
						}
						/*if(@$this->model->getPopulationRelatedTable('state', $row->population_id)){
							$delete_disabled = 'disabled';
						}*/
						if(@$checkLevel->delete == 1){
							$action_delete = '<a href="javascript:;" class="dropdown-item '.@$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('population/delete/'.$row->population_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
						}
						$actions = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right">'.@$action_edit.@$action_delete.'</div></div>';
					}else{
						$actions = 'No action';
					}
					$nestedData['number'] = $start;
					$nestedData['population_source'] = $row->population_source;
					$nestedData['population_year'] = $row->population_year;
					$nestedData['country_name'] = $row->country_name;
					$nestedData['total_population'] = number_format($row->total_population);
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
		$fields = array('population_source', 'population_year', 'country_name', 'total_population');
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

	public function add()
	{
		$checkMenu = $this->setting->getMenuByUrl($this->request->uri->getSegment(1));
		$checkLevel = $this->setting->getLevelByRole('L12000001', @$checkMenu->menu_id);
		if(@$checkLevel->create == 1){
			$data = array(
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry()
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'population_source' => ['label' => 'Source', 'rules' => 'required'],
				'population_year' => ['label' => 'Year', 'rules' => 'required|numeric|min_length[4]|max_length[4]'],
				'total_population' => ['label' => 'Total', 'rules' => 'required|numeric']
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
				'setting' => $this->setting,
				'segment' => $this->request->uri,
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
				'population.*.population_year' => ['label' => 'Year', 'rules' => 'required|numeric|min_length[4]|max_length[4]'],
				'population.*.total_population' => ['label' => 'Total', 'rules' => 'required|numeric']
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
				'setting' => $this->setting,
				'segment' => $this->request->uri,
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'request' => $this->request,
				'country' => $this->model->getCountry(),
				'population' => $this->model->getPopulationById($id)
			);
			$validation = $this->validate([
				'country' => ['label' => 'Country', 'rules' => 'required'],
				'population_source' => ['label' => 'Source', 'rules' => 'required'],
				'population_year' => ['label' => 'Year', 'rules' => 'required|numeric|min_length[4]|max_length[4]'],
				'total_population' => ['label' => 'Total', 'rules' => 'required|numeric']
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
