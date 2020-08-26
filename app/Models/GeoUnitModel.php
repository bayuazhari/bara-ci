<?php namespace App\Models;

use CodeIgniter\Model;

class GeoUnitModel extends Model
{
	protected $table = 'geo_unit';
	protected $primaryKey = 'geo_unit_id';

	public function getGeoUnit($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getGeoUnitCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchGeoUnit($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->like('geo_unit_code', $search)
		->orLike('geo_unit_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchGeoUnitCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->like('geo_unit_code', $search)
		->orLike('geo_unit_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getGeoUnitById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getGeoUnitByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getGeoUnitRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getGeoUnitId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(geo_unit_id, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(geo_unit_id, 3, 2)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('y');
		$char = "GU".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertGeoUnit($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateGeoUnit($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteGeoUnit($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
