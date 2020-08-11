<?php namespace App\Models;

use CodeIgniter\Model;

class CurrencyModel extends Model
{
	protected $table = 'currency';
	protected $primaryKey = 'currency_id';

	public function getCurrency()
	{
		$query = $this->db->table($this->table)
		->get();
		return $query->getResult();
	}

	public function getCurrencyById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getCurrencyByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getCurrencyRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getCurrencyId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(currency_id, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(currency_id, 3, 2)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('y');
		$char = "C2".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertCurrency($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateCurrency($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteCurrency($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
