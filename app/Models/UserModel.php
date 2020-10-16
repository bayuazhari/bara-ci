<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table = 'user';
	protected $primaryKey = 'user_id';

	public function login($user_email, $user_password)
	{
		$query = $this->db->table($this->table)
		->join('level', 'user.level_id=level.level_id')
		->join('menu', 'level.menu_id=menu.menu_id')
		->join('sub_district', 'user.sdistrict_id=sub_district.sdistrict_id')
		->join('district', 'sub_district.district_id=district.district_id')
		->join('city', 'district.city_id=city.city_id')
		->join('state', 'city.state_id=state.state_id')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->where('user_email', $user_email)
		->where('user_password', $user_password)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getUser($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->join('level', 'user.level_id=level.level_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getUserCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchUser($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->join('level', 'user.level_id=level.level_id')
		->like('first_name', $search)
		->orLike('last_name', $search)
		->orLike('user_email', $search)
		->orLike('country_calling_code', $search)
		->orLike('user_phone', $search)
		->orLike('level_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchUserCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->join('level', 'user.level_id=level.level_id')
		->like('first_name', $search)
		->orLike('last_name', $search)
		->orLike('user_email', $search)
		->orLike('country_calling_code', $search)
		->orLike('user_phone', $search)
		->orLike('level_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getUserDetail($id)
	{
		$query = $this->db->table($this->table)
		->join('level', 'user.level_id=level.level_id')
		->join('sub_district', 'user.sdistrict_id=sub_district.sdistrict_id')
		->join('district', 'sub_district.district_id=district.district_id')
		->join('city', 'district.city_id=city.city_id')
		->join('state', 'city.state_id=state.state_id')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->join('country', 'time_zone.country_id=country.country_id')
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getUserById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLevel()
	{
		$query = $this->db->table('level')
		->where('level_status', '1')
		->get();
		return $query->getResult();
	}

	public function getIddCode()
	{
		$query = $this->db->table('country')
		->where('idd_code IS NOT NULL')
		->select('idd_code')
		->distinct()
		->get();
		return $query->getResult();
	}

	public function getCountry()
	{
		$query = $this->db->table('country')
		->where('country_status', '1')
		->get();
		return $query->getResult();
	}

	public function getState($country_id)
	{
		$query = $this->db->table('state')
		->join('time_zone', 'state.tz_id=time_zone.tz_id')
		->where('country_id', $country_id)
		->where('state_status', '1')
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

	public function getSubDistrict($district_id)
	{
		$query = $this->db->table('sub_district')
		->where('district_id', $district_id)
		->where('sdistrict_status', '1')
		->get();
		return $query->getResult();
	}

	public function getSubDistrictById($sdistrict_id)
	{
		$query = $this->db->table('sub_district')
		->where('sdistrict_id', $sdistrict_id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getUserByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLevelByField($field, $record)
	{
		$query = $this->db->table('level')
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getSubDistrictByField($field, $record)
	{
		$query = $this->db->table('sub_district')
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getUserRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getUserRelatedTable2($table, $field1, $field2, $record)
	{
		$query = $this->db->table($table)
		->where($field1, $record)
		->orWhere($field2, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getUserId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(user_id, 11)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(user_id, 3, 6)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('ymd');
		$char = "U1".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertUser($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateUser($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteUser($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
