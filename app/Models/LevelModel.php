<?php namespace App\Models;

use CodeIgniter\Model;

class LevelModel extends Model
{
	protected $table = 'level';
	protected $primaryKey = 'level_id';

	public function getLevel($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->join('menu', 'level.menu_id=menu.menu_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getLevelCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchLevel($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->join('menu', 'level.menu_id=menu.menu_id')
		->like('level_name', $search)
		->orLike('menu_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchLevelCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->join('menu', 'level.menu_id=menu.menu_id')
		->like('level_name', $search)
		->orLike('menu_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getLevelDetail($id)
	{
		$query = $this->db->table($this->table)
		->join('menu', 'level.menu_id=menu.menu_id')
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLevelById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenu()
	{
		$query = $this->db->table('menu')
		->where('menu_status', '1')
		->orderBy('mgroup_id, mparent_id, menu_position', 'ASC')
		->get();
		return $query->getResult();
	}

	public function getLevelByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLevelRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLevelId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(level_id, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(level_id, 3, 2)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('y');
		$char = "L1".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertLevel($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateLevel($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteLevel($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
