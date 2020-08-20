<?php namespace App\Models;

use CodeIgniter\Model;

class DistrictModel extends Model
{
	protected $table = 'district';
	protected $primaryKey = 'district_id';

	public function getDistrict()
	{
		$query = $this->db->table($this->table)
		->select('district_id, district_code, district_name, district_status, city_name, state_name, country_name')
		->join('city', 'district.city_id=city.city_id')
		->join('state', 'city.state_id=state.state_id')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->orderBy('time_zone.country_id, city.state_id, district.city_id, district_id', 'ASC')
		->get();
		return $query->getResult();
	}

	public function getDistrictById($id)
	{
		$query = $this->db->table($this->table)
		->join('city', 'district.city_id=city.city_id')
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getState()
	{
		$query = $this->db->table('state')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->where('state_status', '1')
		->orderBy('time_zone.country_id, state.geo_unit_id, state_id', 'ASC')
		->get();
		return $query->getResult();
	}

	public function getCity($state_id)
	{
		$query = $this->db->table('city')
		->where('state_id', $state_id)
		->where('city_status', '1')
		->get();
		return $query->getResult();
	}

	public function getDistrictByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getCityByField($field, $record)
	{
		$query = $this->db->table('city')
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getDistrictRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getDistrictId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(district_id, 11)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(district_id, 3, 6)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('ymd');
		$char = "D1".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertDistrict($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateDistrict($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteDistrict($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
