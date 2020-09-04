<?php namespace App\Controllers;

use App\Models\SettingModel;

class Notification extends BaseController
{
	public function __construct()
	{
		$this->setting = new SettingModel();
	}

	public function index()
	{
		$data = array(
			'setting' => $this->setting,
			'segment' => $this->request->uri,
			'title' =>  @$checkMenu->menu_name,
			'total_notif' => $this->setting->getNotifCount('0'),
			'notification' => $this->setting->getNotif('0'),
			'breadcrumb' => @$checkMenu->mgroup_name
		);
		echo view('layout/header', $data);
		echo view('notification/view_notification');
		echo view('layout/footer');
	}

	public function getData()
	{
		$columns = array(
			0 => 'notif_id',
			1 => 'notif_title',
			2 => 'notif_desc',
			3 => 'notif_date',
			4 => 'first_name',
			5 => 'is_read'
		);
		$limit = $this->request->getPost('length');
		$start = $this->request->getPost('start');
		$order = $columns[$this->request->getPost('order')[0]['column']];
		$dir = $this->request->getPost('order')[0]['dir'];

		$totalData = $this->setting->getAllNotifCount();
		$totalFiltered = $totalData;
		if(empty($this->request->getPost('search')['value'])){
			$notification = $this->setting->getAllNotif($limit, $start, $order, $dir);
		}else{
			$search = $this->request->getPost('search')['value'];
			$notification =  $this->setting->searchNotif($limit, $start, $search, $order, $dir);
			$totalFiltered = $this->setting->searchNotifCount($search);
		}

		$data = array();
		if(@$notification){
			foreach($notification as $row){
				$start++;
				if($row->is_read == 1){
					$is_read = '<span class="text-success">Read</span>';
				}elseif($row->is_read == 0){
					$is_read = '<span class="text-danger">Unread</span>';
				}else{
					$is_read = '';
				}
				$nestedData['number'] = $start;
				$nestedData['notif_title'] = $row->notif_title;
				$nestedData['notif_desc'] = $row->notif_desc;
				$nestedData['notif_date'] = $row->notif_date;
				$nestedData['sender_name'] = $row->first_name.' '.$row->last_name;
				$nestedData['is_read'] = $is_read;
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
	}

	public function getColumns()
	{
		$fields = array('notif_title', 'notif_desc', 'notif_date', 'sender_name', 'is_read');
		$columns[]['data'] = 'number';
		foreach ($fields as $field) {
			$columns[] = array(
				'data' => $field
			);
		}
		echo json_encode($columns); 
	}
}
