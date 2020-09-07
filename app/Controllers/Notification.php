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
			'title' =>  'Notification',
			'total_notif' => $this->setting->getNotifCount('0'),
			'notification' => $this->setting->getNotif('0')
		);
		echo view('layout/header', $data);
		echo view('notification/view_notification');
		echo view('layout/footer');
	}

	public function getData()
	{
		$columns = array(
			0 => 'notif_id',
			1 => 'first_name',
			2 => 'notif_title',
			3 => 'notif_desc',
			4 => 'notif_date',
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
				$time_diff = (time() - strtotime($row->notif_date));
				$second = $time_diff;
				$minute = round($time_diff / 60 );
				$hour = round($time_diff / 3600 );
				$day = round($time_diff / 86400 );
				$week = round($time_diff / 604800 );
				$month = round($time_diff / 2419200 );

				if ($second < 60) {
					$notif_date = 'Just now';
				} else if ($minute < 60) {
					if($minute == 1){
						$notif_date = 'One minutes ago';
					}else{
						$notif_date = $minute.' minutes ago';
					}
				} else if ($hour < 24) {
					if($hour == 1){
						$notif_date = 'An hour ago';
					}else{
						$notif_date = $hour.' hours ago';
					}
				} else if ($day < 7) {
					if($day == 1){
						$notif_date = 'Yesterday';
					}else{
						$notif_date = $day.' days ago';
					}
				} else if ($week < 4) {
					if($week == 1){
						$notif_date = 'A week ago';
					}else{
						$notif_date = $week.' weeks ago';
					}
				} else if ($month < 12) {
					if($month == 1){
						$notif_date = 'A month ago';
					}else{
						$notif_date = $month.' months ago';
					}
				} else {
					$notif_date = date('F d, Y H:i', strtotime($row->notif_date));
				}
				
				if($row->is_read == 1){
					$is_read = '<span class="text-success">Read</span>';
				}elseif($row->is_read == 0){
					$is_read = '<span class="text-danger">Unread</span>';
				}else{
					$is_read = '';
				}
				$nestedData['number'] = $start;
				$nestedData['sender_name'] = $row->first_name.' '.$row->last_name;
				$nestedData['notif_title'] = $row->notif_title;
				$nestedData['notif_desc'] = $row->notif_desc;
				$nestedData['notif_date'] = $notif_date;
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
		$fields = array('sender_name', 'notif_title', 'notif_desc', 'notif_date', 'is_read');
		$columns[]['data'] = 'number';
		foreach ($fields as $field) {
			$columns[] = array(
				'data' => $field
			);
		}
		echo json_encode($columns); 
	}
}
