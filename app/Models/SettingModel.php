<?php namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
	public function getSettingByGroup($group)
	{
		$query = $this->db->table('setting')
		->where('setting_group', $group)
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
		$roles = json_decode($query->level_role);
		foreach ($roles as $row) {
			if($row->role === $role){
				return $row;
			}
		}
		return false;
	}

	public function updateSetting($setting_id, $data)
	{
		$this->db->table('setting')
		->where('setting_id', $setting_id)
		->update($data);
	}
}
