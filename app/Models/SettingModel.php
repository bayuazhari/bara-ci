<?php namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
	public function getMenuByUrl($menu_url)
	{
		$query = $this->db->table('menu')
		->select('menu_id, menu_name, menu_url, mgroup_name')
		->join('menu_group', 'menu.mgroup_id=menu_group.mgroup_id')
		->where('menu_url', $menu_url)
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

}
