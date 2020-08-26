<?php namespace App\Models;

use CodeIgniter\Model;

class PopulationModel extends Model
{
	protected $table = 'population';
	protected $primaryKey = 'population_id';

	public function getPopulation($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('population_id, population_source, population_year, country_name, total_population')
		->join('country', 'population.country_id=country.country_id')
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getPopulationCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchPopulation($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->select('population_id, population_source, population_year, country_name, total_population')
		->join('country', 'population.country_id=country.country_id')
		->like('population_source', $search)
		->orLike('population_year', $search)
		->orLike('country_name', $search)
		->orLike('total_population', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchPopulationCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->join('country', 'population.country_id=country.country_id')
		->like('population_source', $search)
		->orLike('population_year', $search)
		->orLike('country_name', $search)
		->orLike('total_population', $search)
		->get();
		return $query->getRow()->total;
	}

	public function getPopulationById($id)
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

	public function getPopulationByField($field, $record)
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

	public function getPopulationRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getPopulationId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(population_id, 9)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(population_id, 3, 4)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('ym');
		$char = "P1".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertPopulation($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updatePopulation($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deletePopulation($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
