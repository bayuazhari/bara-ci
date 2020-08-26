<?php namespace App\Models;

use CodeIgniter\Model;

class SubDistrictModel extends Model
{
	protected $table = 'sub_district';
	protected $primaryKey = 'sdistrict_id';

	public function getSubDistrict($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('sdistrict_id, sdistrict_code, sdistrict_name, district_name, city_name, state_name, sdistrict_status')
		->join('district', 'sub_district.district_id=district.district_id')
		->join('city', 'district.city_id=city.city_id')
		->join('state', 'city.state_id=state.state_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getSubDistrictCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchSubDistrict($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('sdistrict_id, sdistrict_code, sdistrict_name, district_name, city_name, state_name, sdistrict_status')
		->join('district', 'sub_district.district_id=district.district_id')
		->join('city', 'district.city_id=city.city_id')
		->join('state', 'city.state_id=state.state_id')
		->like('sdistrict_code', $search)
		->orLike('sdistrict_name', $search)
		->orLike('district_name', $search)
		->orLike('city_name', $search)
		->orLike('state_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchSubDistrictCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->join('district', 'sub_district.district_id=district.district_id')
		->join('city', 'district.city_id=city.city_id')
		->join('state', 'city.state_id=state.state_id')
		->like('sdistrict_code', $search)
		->orLike('sdistrict_name', $search)
		->orLike('district_name', $search)
		->orLike('city_name', $search)
		->orLike('state_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getSubDistrictById($id)
	{
		$query = $this->db->table($this->table)
		->join('district', 'sub_district.district_id=district.district_id')
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

	public function getDistrict($city_id)
	{
		$query = $this->db->table('district')
		->where('city_id', $city_id)
		->where('district_status', '1')
		->get();
		return $query->getResult();
	}

	public function getSubDistrictByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getDistrictByField($field, $record)
	{
		$query = $this->db->table('district')
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getSubDistrictRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getSubDistrictId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(sdistrict_id, 11)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(sdistrict_id, 3, 6)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('ymd');
		$char = "SD".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertSubDistrict($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateSubDistrict($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteSubDistrict($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
