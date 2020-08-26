<?php namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model
{
	protected $table = 'state';
	protected $primaryKey = 'state_id';

	public function getState($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('state_id, country_alpha2_code, state_iso_code, state_ref_code, state_name, state_capital, tz_name, geo_unit_name, state_status')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->join('geo_unit', 'state.geo_unit_id=geo_unit.geo_unit_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getStateCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchState($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('state_id, country_alpha2_code, state_iso_code, state_ref_code, state_name, state_capital, tz_name, geo_unit_name, state_status')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->join('geo_unit', 'state.geo_unit_id=geo_unit.geo_unit_id')
		->like('country_alpha2_code', $search)
		->orLike('state_iso_code', $search)
		->orLike('state_ref_code', $search)
		->orLike('state_name', $search)
		->orLike('state_capital', $search)
		->orLike('tz_name', $search)
		->orLike('geo_unit_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchStateCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->join('geo_unit', 'state.geo_unit_id=geo_unit.geo_unit_id')
		->like('country_alpha2_code', $search)
		->orLike('state_iso_code', $search)
		->orLike('state_ref_code', $search)
		->orLike('state_name', $search)
		->orLike('state_capital', $search)
		->orLike('tz_name', $search)
		->orLike('geo_unit_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getStateById($id)
	{
		$query = $this->db->table($this->table)
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getCountry()
	{
		$query = $this->db->table('country')
		->where('country_status', '1')
		->get();
		return $query->getResult();
	}

	public function getTimeZone($country_id)
	{
		$query = $this->db->table('time_zone')
		->where('country_id', $country_id)
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

	public function getCountryByField($field, $record)
	{
		$query = $this->db->table('country')
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
		->select('MAX(RIGHT(state_id, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(state_id, 3, 2)) AS last_mid_id')
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
