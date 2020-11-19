<?php namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
	protected $table = 'menu';
	protected $primaryKey = 'menu_id';

	public function getMenu($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('menu_id, menu_class, menu_name, menu_label, menu_url, mparent_id, mgroup_name, menu_status')
		->join('menu_group', 'menu.mgroup_id=menu_group.mgroup_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getMenuCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchMenu($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('menu_id, menu_class, menu_name, menu_label, menu_url, mparent_id, mgroup_name, menu_status')
		->join('menu_group', 'menu.mgroup_id=menu_group.mgroup_id')
		->like('menu_class', $search)
		->orLike('menu_name', $search)
		->orLike('menu_url', $search)
		->orLike('mgroup_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchMenuCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->join('menu_group', 'menu.mgroup_id=menu_group.mgroup_id')
		->like('menu_class', $search)
		->orLike('menu_name', $search)
		->orLike('menu_url', $search)
		->orLike('mgroup_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getMenuById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuByGroup($mgroup_id)
	{
		$query = $this->db->table($this->table)
		->where('mgroup_id', $mgroup_id)
		->orderBy('mparent_id, menu_position', 'ASC')
		->get();
		return $query->getResult();
	}

	public function getMenuPosition($mgroup_id, $mparent_id, $min_position, $max_position)
	{
		$query = $this->db->table($this->table)
		->select('menu_id, menu_position')
		->where('mgroup_id', $mgroup_id)
		->where('mparent_id', $mparent_id)
		->where('menu_position >=', $min_position)
		->where('menu_position <=', $max_position)
		->orderBy('mparent_id, menu_position', 'ASC')
		->get();
		return $query->getResult();
	}

	public function getMenuPosition2($mgroup_id, $mparent_id)
	{
		$query = $this->db->table($this->table)
		->select('menu_id, menu_position')
		->where('mgroup_id', $mgroup_id)
		->where('mparent_id', $mparent_id)
		->orderBy('mparent_id, menu_position', 'ASC')
		->get();
		return $query->getResult();
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

	public function getMenuRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getMenuLastPosition($mgroup_id, $mparent_id = NULL)
	{
		$query = $this->db->table($this->table)
		->selectMax('menu_position', 'position')
		->where('mgroup_id', $mgroup_id)
		->where('mparent_id', $mparent_id)
		->get();
		return $query->getRow()->position;
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