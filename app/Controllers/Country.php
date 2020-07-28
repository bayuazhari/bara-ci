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
				'title' =>  @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'country' => $this->model->getCountry(),
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('country/view_country', $data);
			echo view('layout/footer');
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
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
		echo json_encode($this->request->getPost('country'));
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
				'language' => ['label' => 'Language', 'rules' => 'permit_empty']
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
