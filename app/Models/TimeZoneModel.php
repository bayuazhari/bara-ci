<?php namespace App\Models;

use CodeIgniter\Model;

class TimeZoneModel extends Model
{
	protected $table = 'time_zone';
	protected $primaryKey = 'tz_id';

	public function getTimeZone($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('tz_id, tz_name, tz_abbr, country_name, tz_status')
		->join('country', 'time_zone.country_id=country.country_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getTimeZoneCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchTimeZone($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('tz_id, tz_name, tz_abbr, country_name, tz_status')
		->join('country', 'time_zone.country_id=country.country_id')
		->like('tz_name', $search)
		->orLike('tz_abbr', $search)
		->orLike('country_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchTimeZoneCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->join('country', 'time_zone.country_id=country.country_id')
		->like('tz_name', $search)
		->orLike('tz_abbr', $search)
		->orLike('country_name', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getTimeZoneById($id)
	{
		$query = $this->db->table($this->table)
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

	public function getTimeZoneByField($field, $record)
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

	public function getTimeZoneRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getTimeZoneId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(tz_id, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(tz_id, 3, 2)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('y');
		$char = "TZ".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertTimeZone($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateTimeZone($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteTimeZone($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
