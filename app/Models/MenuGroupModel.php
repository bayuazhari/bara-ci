<?php namespace App\Models;

use CodeIgniter\Model;

class MenuGroupModel extends Model
{
	protected $table = 'menu_group';
	protected $primaryKey = 'mgroup_id';

	public function getMenuGroup()
	{
		$query = $this->db->table($this->table)
		->get();
		return $query->getResult();
	}

	public function getMenuGroupById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuGroupByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuGroupRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuGroupId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(mgroup_id, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(mgroup_id, 3, 2)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('y');
		$char = "MG".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertMenuGroup($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateMenuGroup($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteMenuGroup($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
