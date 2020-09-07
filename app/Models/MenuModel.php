<?php namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
	protected $table = 'menu';
	protected $primaryKey = 'menu_id';

	public function getMenu($mgroup_id)
	{
		$query = $this->db->table($this->table)
		->where('mgroup_id', $mgroup_id)
		->orderBy('mparent_id, menu_position', 'ASC')
		->get();
		return $query->getResult();
	}

	public function getMenuById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuGroup()
	{
		$query = $this->db->table('menu_group')
		->where('mgroup_status', '1')
		->get();
		return $query->getResult();
	}

	public function getMenuByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuGroupByField($field, $record)
	{
		$query = $this->db->table('country')
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(menu_id, 9)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(menu_id, 3, 4)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('ym');
		$char = "M1".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertMenu($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateMenu($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteMenu($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}