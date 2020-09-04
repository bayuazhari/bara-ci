<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\CountryModel;

class Country extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new CountryModel();
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
			echo view('country/view_country');
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
				0 => 'country_id',
				1 => 'country_alpha2_code',
				2 => 'country_alpha2_code',
				3 => 'country_alpha3_code',
				4 => 'country_numeric_code',
				5 => 'country_name',
				6 => 'country_status'
			);
			$limit = $this->request->getPost('length');
			$start = $this->request->getPost('start');
			$order = $columns[$this->request->getPost('order')[0]['column']];
			$dir = $this->request->getPost('order')[0]['dir'];

			$totalData = $this->model->getCountryCount();
			$totalFiltered = $totalData;
			if(empty($this->request->getPost('search')['value'])){
				$country = $this->model->getCountry($limit, $start, $order, $dir);
			}else{
				$search = $this->request->getPost('search')['value'];
				$country =  $this->model->searchCountry($limit, $start, $search, $order, $dir);
				$totalFiltered = $this->model->searchCountryCount($search);
			}

			$data = array();
			if(@$country){
				foreach($country as $row){
					$start++;
					if($row->country_status == 1){
						$country_status = '<span class="text-success">Active</span>';
					}elseif($row->country_status == 0){
						$country_status = '<span class="text-danger">Inactive</span>';
					}else{
						$country_status = '';
					}
					if(@$checkLevel->update == 1){
						$action_edit = '<a href="'.base_url('country/edit/'.$row->country_id).'" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>';
					}
					if(@$this->model->getCountryRelatedTable('population', $row->country_id) AND @$this->model->getCountryRelatedTable('time_zone', $row->country_id)){
						$delete_disabled = 'disabled';
					}
					if(@$checkLevel->delete == 1){
						$action_delete = '<a href="javascript:;" class="dropdown-item '.@$delete_disabled.'"  data-toggle="modal" data-target="#modal-delete" data-href="'.base_url('country/delete/'.$row->country_id).'"><i class="fa fa-trash-alt"></i> Delete</a>';
					}
					$nestedData['number'] = $start;
					$nestedData['country_icon'] = '<h4 class="flag-icon flag-icon-'.strtolower($row->country_alpha2_code).'"></h4>';
					$nestedData['country_alpha2_code'] = $row->country_alpha2_code;
					$nestedData['country_alpha3_code'] = $row->country_alpha3_code;
					$nestedData['country_numeric_code'] = $row->country_numeric_code;
					$nestedData['country_name'] = $row->country_name;
					$nestedData['country_status'] = $country_status;
					$nestedData['action'] = '<div class="btn-group"><a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right"><a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#modal-detail" data-id="'.$row->country_id.'" data-href="'.base_url('country/detail/').'"><i class="fa fa-info-circle"></i> Detail</a>'.@$action_edit.@$action_delete.'</div></div>';
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
		$fields = array('country_icon', 'country_alpha2_code', 'country_alpha3_code', 'country_numeric_code', 'country_name', 'country_status');
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
			'country' => $this->model->getCountryDetail($id),
			'population' => $this->model->getLastYearPopulation($id)
		);
		echo view('country/view_country_detail', $data);
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
				'currency' => $this->model->getCurrency(),
				'language' => $this->model->getLanguage()
			);
			$validation = $this->validate([
				'country_alpha2_code' => ['label' => 'Alpha-2 Code', 'rules' => 'required|alpha|min_length[2]|max_length[2]|is_unique[country.country_alpha2_code]'],
				'country_alpha3_code' => ['label' => 'Alpha-3 Code', 'rules' => 'required|alpha|min_length[3]|max_length[3]|is_unique[country.country_alpha3_code]'],
				'country_numeric_code' => ['label' => 'Numeric Code', 'rules' => 'required|numeric|min_length[3]|max_length[3]|is_unique[country.country_numeric_code]'],
				'country_name' => ['label' => 'Name', 'rules' => 'required'],
				'country_capital' => ['label' => 'Capital', 'rules' => 'permit_empty'],
				'country_demonym' => ['label' => 'Demonym', 'rules' => 'permit_empty'],
				'country_area' => ['label' => 'Total Area', 'rules' => 'permit_empty|numeric'],
				'idd_code' => ['label' => 'IDD Code', 'rules' => 'permit_empty|numeric|max_length[5]'],
				'cctld' => ['label' => 'ccTLD', 'rules' => 'permit_empty'],
				'currency' => ['label' => 'Currency', 'rules' => 'permit_empty'],
				'language' => ['label' => 'Language', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('country/form_add_country', $data);
				echo view('layout/footer');
			}else{
				if(@$this->request->getPost('country_area')){
					$country_area = $this->request->getPost('country_area');
				}else{
					$country_area = NULL;
				}
				if(@$this->request->getPost('currency')){
					$currency = $this->request->getPost('currency');
				}else{
					$currency = NULL;
				}
				if(@$this->request->getPost('language')){
					$language = $this->request->getPost('language');
				}else{
					$language = NULL;
				}
				$countryData = array(
					'country_id' => $this->model->getCountryId(),
					'country_alpha2_code' => $this->request->getPost('country_alpha2_code'),
					'country_alpha3_code' => $this->request->getPost('country_alpha3_code'),
					'country_numeric_code' => $this->request->getPost('country_numeric_code'),
					'country_name' => $this->request->getPost('country_name'),
					'country_capital' => $this->request->getPost('country_capital'),
					'country_demonym' => $this->request->getPost('country_demonym'),
					'country_area' => $country_area,
					'idd_code' => $this->request->getPost('idd_code'),
					'cctld' => $this->request->getPost('cctld'),
					'currency_id' => $currency,
					'lang_id' => $language,
					'country_status' => 1
				);
				$this->model->insertCountry($countryData);
				session()->setFlashdata('success', 'Country has been added successfully. (SysCode: <a href="'.base_url('country?id='.$countryData['country_id']).'" class="alert-link">'.$countryData['country_id'].'</a>)');
				return redirect()->to(base_url('country'));
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
				'country_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[country_csv]|ext_in[country_csv,csv]|max_size[country_csv,2048]']
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
				echo view('country/form_bulk_upload_country');
				echo view('layout/footer');
			}else{
				$country_csv = $this->request->getFile('country_csv')->getTempName();
				$file = file_get_contents($country_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['country'] = array();
				foreach ($lines as $line) {
					$data['country'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('country/form_bulk_upload_country', $data);
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
				'country.*.country_alpha2_code' => ['label' => 'Alpha-2 Code', 'rules' => 'required|alpha|min_length[2]|max_length[2]|is_unique[country.country_alpha2_code]'],
				'country.*.country_alpha3_code' => ['label' => 'Alpha-3 Code', 'rules' => 'required|alpha|min_length[3]|max_length[3]|is_unique[country.country_alpha3_code]'],
				'country.*.country_numeric_code' => ['label' => 'Numeric Code', 'rules' => 'required|numeric|min_length[3]|max_length[3]|is_unique[country.country_numeric_code]'],
				'country.*.country_name' => ['label' => 'Name', 'rules' => 'required'],
				'country.*.country_capital' => ['label' => 'Capital', 'rules' => 'permit_empty'],
				'country.*.country_demonym' => ['label' => 'Demonym', 'rules' => 'permit_empty'],
				'country.*.country_area' => ['label' => 'Total Area', 'rules' => 'permit_empty|numeric'],
				'country.*.idd_code' => ['label' => 'IDD Code', 'rules' => 'permit_empty|numeric|max_length[5]'],
				'country.*.cctld' => ['label' => 'ccTLD', 'rules' => 'permit_empty'],
				'country.*.currency' => ['label' => 'Currency', 'rules' => 'permit_empty'],
				'country.*.language' => ['label' => 'Language', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('country/bulk_upload'));
			}else{
				foreach ($this->request->getPost('country') as $row) {
					if(@$row['country_area']){
						$country_area = $row['country_area'];
					}else{
						$country_area = NULL;
					}
					if(@$row['currency']){
						$currency = $row['currency'];
					}else{
						$currency = NULL;
					}
					if(@$row['language']){
						$language = $row['language'];
					}else{
						$language = NULL;
					}
					$countryData = array(
						'country_id' => $this->model->getCountryId(),
						'country_alpha2_code' => $row['country_alpha2_code'],
						'country_alpha3_code' => $row['country_alpha3_code'],
						'country_numeric_code' => $row['country_numeric_code'],
						'country_name' => $row['country_name'],
						'country_capital' => $row['country_capital'],
						'country_demonym' => $row['country_demonym'],
						'country_area' => $country_area,
						'idd_code' => $row['idd_code'],
						'cctld' => $row['cctld'],
						'currency_id' => $currency,
						'lang_id' => $language,
						'country_status' => 1
					);
					$this->model->insertCountry($countryData);
				}
				session()->setFlashdata('success', 'Countries has been added successfully.');
				return redirect()->to(base_url('country'));
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
				'currency' => $this->model->getCurrency(),
				'language' => $this->model->getLanguage(),
				'country' => $this->model->getCountryById($id)
			);
			if($data['country']->country_alpha2_code == $this->request->getPost('country_alpha2_code')){
				$country_alpha2_code_rules = 'required|alpha|min_length[2]|max_length[2]';
			}else{
				$country_alpha2_code_rules = 'required|alpha|min_length[2]|max_length[2]|is_unique[country.country_alpha2_code]';
			}
			if($data['country']->country_alpha3_code == $this->request->getPost('country_alpha3_code')){
				$country_alpha3_code_rules = 'required|alpha|min_length[3]|max_length[3]';
			}else{
				$country_alpha3_code_rules = 'required|alpha|min_length[3]|max_length[3]|is_unique[country.country_alpha3_code]';
			}
			if($data['country']->country_numeric_code == $this->request->getPost('country_numeric_code')){
				$country_numeric_code_rules = 'required|numeric|min_length[3]|max_length[3]';
			}else{
				$country_numeric_code_rules = 'required|numeric|min_length[3]|max_length[3]|is_unique[country.country_numeric_code]';
			}
			$validation = $this->validate([
				'country_alpha2_code' => ['label' => 'Alpha-2 Code', 'rules' => $country_alpha2_code_rules],
				'country_alpha3_code' => ['label' => 'Alpha-3 Code', 'rules' => $country_alpha3_code_rules],
				'country_numeric_code' => ['label' => 'Numeric Code', 'rules' => $country_numeric_code_rules],
				'country_name' => ['label' => 'Name', 'rules' => 'required'],
				'country_capital' => ['label' => 'Capital', 'rules' => 'permit_empty'],
				'country_demonym' => ['label' => 'Demonym', 'rules' => 'permit_empty'],
				'country_area' => ['label' => 'Total Area', 'rules' => 'permit_empty|numeric'],
				'idd_code' => ['label' => 'IDD Code', 'rules' => 'permit_empty|numeric|max_length[5]'],
				'cctld' => ['label' => 'ccTLD', 'rules' => 'permit_empty'],
				'currency' => ['label' => 'Currency', 'rules' => 'permit_empty'],
				'language' => ['label' => 'Language', 'rules' => 'permit_empty'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('country/form_edit_country', $data);
				echo view('layout/footer');
			}else{
				if(@$this->request->getPost('country_area')){
					$country_area = $this->request->getPost('country_area');
				}else{
					$country_area = NULL;
				}
				if(@$this->request->getPost('currency')){
					$currency = $this->request->getPost('currency');
				}else{
					$currency = NULL;
				}
				if(@$this->request->getPost('language')){
					$language = $this->request->getPost('language');
				}else{
					$language = NULL;
				}
				$countryData = array(
					'country_alpha2_code' => $this->request->getPost('country_alpha2_code'),
					'country_alpha3_code' => $this->request->getPost('country_alpha3_code'),
					'country_numeric_code' => $this->request->getPost('country_numeric_code'),
					'country_name' => $this->request->getPost('country_name'),
					'country_capital' => $this->request->getPost('country_capital'),
					'country_demonym' => $this->request->getPost('country_demonym'),
					'country_area' => $country_area,
					'idd_code' => $this->request->getPost('idd_code'),
					'cctld' => $this->request->getPost('cctld'),
					'currency_id' => $currency,
					'lang_id' => $language,
					'country_status' => $this->request->getPost('status')
				);
				$this->model->updateCountry($id, $countryData);
				session()->setFlashdata('success', 'Country has been updated successfully. (SysCode: <a href="'.base_url('country?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('country'));
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
			$countryData = $this->model->getCountryById($id);
			$this->model->deleteCountry($id);
			session()->setFlashdata('warning', 'Country has been removed successfully. <a href="'.base_url('country/undo?data='.json_encode($countryData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('country'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$country = json_decode($this->request->getGet('data'));
		$checkCountry = $this->model->getCountryById(@$country->country_id);
		if(@$checkCountry){
			$country_id = $this->model->getCountryId();
		}else{
			$country_id = @$country->country_id;
		}
		$countryData = array(
			'country_id' => $country_id,
			'country_alpha2_code' => @$country->country_alpha2_code,
			'country_alpha3_code' => @$country->country_alpha3_code,
			'country_numeric_code' => @$country->country_numeric_code,
			'country_name' => @$country->country_name,
			'country_capital' => @$country->country_capital,
			'country_demonym' => @$country->country_demonym,
			'country_area' => @$country->country_area,
			'idd_code' => @$country->idd_code,
			'cctld' => @$country->cctld,
			'currency_id' => @$country->currency_id,
			'lang_id' => @$country->lang_id,
			'country_status' => @$country->country_status
		);
		$this->model->insertCountry($countryData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('country?id='.$country_id).'" class="alert-link">'.$country_id.'</a>)');
		return redirect()->to(base_url('country'));
	}
}
