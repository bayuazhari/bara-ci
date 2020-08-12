<?php namespace App\Models;

use CodeIgniter\Model;

class LanguageModel extends Model
{
	protected $table = 'language';
	protected $primaryKey = 'lang_id';

	public function getLanguage()
	{
		$query = $this->db->table($this->table)
		->get();
		return $query->getResult();
	}

	public function getLanguageById($id)
	{
		$query = $this->db->table($this->table)
		->where($this->primaryKey, $id)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLanguageByField($field, $record)
	{
		$query = $this->db->table($this->table)
		->where($field, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLanguageRelatedTable($table, $record)
	{
		$query = $this->db->table($table)
		->where($this->primaryKey, $record)
		->limit(1)
		->get();
		return $query->getRow();
	}

	public function getLanguageId()
	{
		$lastId = $this->db->table($this->table)
		->select('MAX(RIGHT(lang_id, 7)) AS last_id')
		->get();
		$lastMidId = $this->db->table($this->table)
		->select('MAX(MID(lang_id, 3, 2)) AS last_mid_id')
		->get()
		->getRow()
		->last_mid_id;
		$midId = date('y');
		$char = "L1".$midId;
		if($lastMidId == $midId){
			$tmp = ($lastId->getRow()->last_id)+1;
			$id = substr($tmp, -5);
		}else{
			$id = "00001";
		}
		return $char.$id;
	}

	public function insertLanguage($data)
	{
		$this->db->table($this->table)
		->insert($data);
	}

	public function updateLanguage($id, $data)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->update($data);
	}

	public function deleteLanguage($id)
	{
		$this->db->table($this->table)
		->where($this->primaryKey, $id)
		->delete();
	}
}
