<?php namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
	protected $table = 'city';
	protected $primaryKey = 'city_id';

	public function getCity($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('city_id, city_code, city_name, capital_city_code, capital_city_name, state_name, country_name, city_status')
		->join('state', 'city.state_id=state.state_id')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getCityCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchCity($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('city_id, city_code, city_name, capital_city_code, capital_city_name, state_name, country_name, city_status')
		->join('state', 'city.state_id=state.state_id')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->like('city_code', $search)
		->orLike('city_name', $search)
		->orLike('capital_city_code', $search)
		->orLike('capital_city_name', $search)
		->orLike('state_name', $search)
		->orLike('country_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchCityCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->join('state', 'city.state_id=state.state_id')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->like('city_code', $search)
		->orLike('city_name', $search)
		->orLike('capital_city_code', $search)
		->orLike('capital_city_name', $search)
		->orLike('state_name', $search)
		->orLike('country_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getCityById($id)
	{
		$query = $this->db->table($this->table)
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

	public function getCityByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getStateByField($field, $record)
	{
		$query = $this->db->table('state')
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getCityRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getCityId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(city_id, 9)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(city_id, 3, 4)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('ym');
		$char = "C3".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertCity($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateCity($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteCity($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
