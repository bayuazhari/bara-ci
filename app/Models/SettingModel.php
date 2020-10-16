<?php namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
	public function getSettingByGroup($setting_group)
	{
		$query = $this->db->table('setting')
		->where('setting_group', $setting_group)
		->get();
		return $query->getResult();
	}

	public function getSettingById($setting_id)
	{
		$query = $this->db->table('setting')
		->select('setting_value')
		->where('setting_id', $setting_id)
		->get();
		return $query->getRow();
	}

	public function getMenu($mgroup_id, $mparent_id = NULL)
	{
		$query = $this->db->table('menu')
		->where('mgroup_id', $mgroup_id)
		->where('mparent_id', $mparent_id)
		->orderBy('menu_position', 'ASC')
		->get();
		return $query->getResult();
	}

	public function getMenuByUrl($menu_url)
	{
		$query = $this->db->table('menu')
		->select('menu_id, mparent_id, menu_name, menu_url, mgroup_name')
		->join('menu_group', 'menu.mgroup_id=menu_group.mgroup_id')
		->where('menu_url', $menu_url)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuParent($menu_id)
	{
		$query = $this->db->table('menu')
		->select('mparent_id')
		->where('menu_id', $menu_id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLevelByRole($level_id, $role)
	{
		$query = $this->db->table('level')
		->select('level_role')
		->where('level_id',$level_id)
		->limit(1)
		->get()
		->getRow();
		$roles = json_decode(@$query->level_role);
		if(@$roles){
			foreach ($roles as $row) {
				if($row->menu === $role){
					return $row;
				}
			}
		}
		return false;
	}

	public function getNotif($recipient_id)
	{
		$query = $this->db->table('notification')
		->where('recipient_id', $recipient_id)
		->where('is_read', '0')
		->orderBy('notif_date', 'DESC')
		->limit(5)
		->get();
		return $query->getResult();
	}

	public function getNotifCount($recipient_id)
	{
		$query = $this->db->table('notification')
		->selectCount('notif_id', 'total')
		->where('recipient_id', $recipient_id)
		->where('is_read', '0')
		->get();
		return $query->getRow()->total;
	}

	public function getAllNotif($limit, $start, $col, $dir)
	{
		$query = $this->db->table('notification')
		->select('notif_id, notif_title, notif_desc, notif_date, first_name, last_name, is_read')
		->join('user', 'notification.sender_id=user.user_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getAllNotifCount()
	{
		$query = $this->db->table('notification')
		->selectCount('notif_id', 'total')
		->get();
		return $query->getRow()->total;
	}

	public function searchNotif($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table('notification')
		->select('notif_id, notif_title, notif_desc, notif_date, first_name, last_name, is_read')
		->join('user', 'notification.sender_id=user.user_id')
		->like('notif_title', $search)
		->orLike('notif_desc', $search)
		->orLike('notif_date', $search)
		->orLike('first_name', $search)
		->orLike('last_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchNotifCount($search)
	{
		$query = $this->db->table('notification')
		->selectCount('notif_id', 'total')
		->join('user', 'notification.sender_id=user.user_id')
		->like('notif_title', $search)
		->orLike('notif_desc', $search)
		->orLike('notif_date', $search)
		->orLike('first_name', $search)
		->orLike('last_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getNotifId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(notif_id, 11)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(notif_id, 3, 6)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('ymd');
		$char = "N1".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertNotif($data)
	{
		$this->db->table('notification')
		->insert($data);
	}

	public function updateSetting($setting_id, $setting_data)
	{
		$this->db->table('setting')
		->where('setting_id', $setting_id)
		->update($setting_data);
	}

	public function updateNotif($notif_id, $notif_data)
	{
		$this->db->table('notification')
		->where('notif_id', $notif_id)
		->update($notif_data);
	}
}
