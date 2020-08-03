<?php namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model
{
	protected $table = 'state';
	protected $primaryKey = 'state_id';

	public function getState()
	{
		$query = $this->db->table($this->table)
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->join('geo_unit', 'state.geo_unit_id=geo_unit.geo_unit_id')
		->orderBy('time_zone.country_id, state.geo_unit_id, state_id', 'ASC')
		->get();
		return $query->getResult();
	}

	public function getStateById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getTimeZone()
	{
		$query = $this->db->table('time_zone')
		->where('tz_status', '1')
		->get();
		return $query->getResult();
	}

	public function getGeoUnit()
	{
		$query = $this->db->table('geo_unit')
		->where('geo_unit_status', '1')
		->get();
		return $query->getResult();
	}

	public function getStateByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getTimeZoneByField($field, $record)
	{
		$query = $this->db->table('time_zone')
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getGeoUnitByField($field, $record)
	{
		$query = $this->db->table('geo_unit')
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getStateRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getStateId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(`state_id`, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(`state_id`, 3, 2)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('y');
		$char = "S1".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertState($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateState($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteState($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
