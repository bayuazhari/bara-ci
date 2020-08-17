<?php namespace App\Models;

use CodeIgniter\Model;

class PopulationModel extends Model
{
	protected $table = 'population';
	protected $primaryKey = 'population_id';

	public function getPopulation()
	{
		$query = $this->db->table($this->table)
		->join('country', 'population.country_id=country.country_id')
		->orderBy('population_year', 'DESC')
		->orderBy('population.country_id, population_id', 'ASC')
		->get();
		return $query->getResult();
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
		->select('MAX(RIGHT(population_id, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(population_id, 3, 2)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('y');
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
