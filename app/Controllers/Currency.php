<?php namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\CurrencyModel;

class Currency extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
		$this->model = new CurrencyModel();
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
				'currency' => $this->model->getCurrency(),
				'checkLevel' => $checkLevel
			);
			echo view('layout/header', $data);
			echo view('currency/view_currency', $data);
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
				'currency_code' => ['label' => 'Code', 'rules' => 'required|alpha|min_length[3]|max_length[3]|is_unique[currency.currency_code]'],
				'currency_name' => ['label' => 'Name', 'rules' => 'required'],
				'currency_symbol' => ['label' => 'Symbol', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('currency/form_add_currency', $data);
				echo view('layout/footer');
			}else{
				$currencyData = array(
					'currency_id' => $this->model->getCurrencyId(),
					'currency_code' => $this->request->getPost('currency_code'),
					'currency_name' => $this->request->getPost('currency_name'),
					'currency_symbol' => $this->request->getPost('currency_symbol'),
					'currency_status' => 1
				);
				$this->model->insertCurrency($currencyData);
				session()->setFlashdata('success', 'Currency has been added successfully. (SysCode: <a href="'.base_url('currency?id='.$currencyData['currency_id']).'" class="alert-link">'.$currencyData['currency_id'].'</a>)');
				return redirect()->to(base_url('currency'));
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
				'currency_csv' => ['label' => 'Upload CSV File', 'rules' => 'uploaded[currency_csv]|ext_in[currency_csv,csv]|max_size[currency_csv,2048]']
			]);
			$data = array(
				'title' => @$checkMenu->menu_name,
				'breadcrumb' => @$checkMenu->mgroup_name,
				'validation' => $this->validator
			);
			if(!$validation){
				echo view('layout/header', $data);
				echo view('currency/form_bulk_upload_currency');
				echo view('layout/footer');
			}else{
				$currency_csv = $this->request->getFile('currency_csv')->getTempName();
				$file = file_get_contents($currency_csv);
				$lines = explode("\n", $file);
				$head = str_getcsv(array_shift($lines));
				$data['currency'] = array();
				foreach ($lines as $line) {
					$data['currency'][] = array_combine($head, str_getcsv($line));
				}
				$data['model'] = $this->model;
				echo view('layout/header', $data);
				echo view('currency/form_bulk_upload_currency', $data);
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
				'currency.*.currency_code' => ['label' => 'Code', 'rules' => 'required|alpha|min_length[3]|max_length[3]|is_unique[currency.currency_code]'],
				'currency.*.currency_name' => ['label' => 'Name', 'rules' => 'required'],
				'currency.*.currency_symbol' => ['label' => 'Symbol', 'rules' => 'permit_empty']
			]);
			if(!$validation){
				session()->setFlashdata('warning', 'The CSV file you uploaded contains some errors.'.$this->validator->listErrors());
				return redirect()->to(base_url('currency/bulk_upload'));
			}else{
				foreach ($this->request->getPost('currency') as $row) {
					$currencyData = array(
						'currency_id' => $this->model->getCurrencyId(),
						'currency_code' => $row['currency_code'],
						'currency_name' => $row['currency_name'],
						'currency_symbol' => $row['currency_symbol'],
						'currency_status' => 1
					);
					$this->model->insertCurrency($currencyData);
				}
				session()->setFlashdata('success', 'Currencies has been added successfully.');
				return redirect()->to(base_url('currency'));
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
				'currency' => $this->model->getCurrencyById($id)
			);
			if($data['currency']->currency_code == $this->request->getPost('currency_code')){
				$currency_code_rules = 'required|alpha|min_length[3]|max_length[3]';
			}else{
				$currency_code_rules = 'required|alpha|min_length[3]|max_length[3]|is_unique[currency.currency_code]';
			}
			$validation = $this->validate([
				'currency_code' => ['label' => 'Code', 'rules' => $currency_code_rules],
				'currency_name' => ['label' => 'Name', 'rules' => 'required'],
				'currency_symbol' => ['label' => 'Symbol', 'rules' => 'permit_empty'],
				'status' => ['label' => 'Status', 'rules' => 'required']
			]);
			if(!$validation){
				$data['validation'] = $this->validator;
				echo view('layout/header', $data);
				echo view('currency/form_edit_currency', $data);
				echo view('layout/footer');
			}else{
				$currencyData = array(
					'currency_code' => $this->request->getPost('currency_code'),
					'currency_name' => $this->request->getPost('currency_name'),
					'currency_symbol' => $this->request->getPost('currency_symbol'),
					'currency_status' => $this->request->getPost('status')
				);
				$this->model->updateCurrency($id, $currencyData);
				session()->setFlashdata('success', 'Currency has been updated successfully. (SysCode: <a href="'.base_url('currency?id='.$id).'" class="alert-link">'.$id.'</a>)');
				return redirect()->to(base_url('currency'));
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
			$currencyData = $this->model->getCurrencyById($id);
			$this->model->deleteCurrency($id);
			session()->setFlashdata('warning', 'Currency has been removed successfully. <a href="'.base_url('currency/undo?data='.json_encode($currencyData)).'" class="alert-link">Undo</a>');
			return redirect()->to(base_url('currency'));
		}else{
			session()->setFlashdata('warning', 'Sorry, You are not allowed to access this page.');
			return redirect()->to(base_url('login?redirect='.@$checkMenu->menu_url));
		}
	}

	public function undo()
	{
		$currency = json_decode($this->request->getGet('data'));
		$checkCurrency = $this->model->getCurrencyById(@$currency->currency_id);
		if(@$checkCurrency){
			$currency_id = $this->model->getCurrencyId();
		}else{
			$currency_id = @$currency->currency_id;
		}
		$currencyData = array(
			'currency_id' => $currency_id,
			'currency_code' => @$currency->currency_code,
			'currency_name' => @$currency->currency_name,
			'currency_symbol' => @$currency->currency_symbol,
			'currency_status' => @$currency->currency_status
		);
		$this->model->insertCurrency($currencyData);
		session()->setFlashdata('success', 'Action undone. (SysCode: <a href="'.base_url('currency?id='.$currency_id).'" class="alert-link">'.$currency_id.'</a>)');
		return redirect()->to(base_url('currency'));
	}
}
