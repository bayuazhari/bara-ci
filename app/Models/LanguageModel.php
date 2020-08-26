<?php namespace App\Models;

use CodeIgniter\Model;

class LanguageModel extends Model
{
	protected $table = 'language';
	protected $primaryKey = 'lang_id';

	public function getLanguage($limit, $start, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function getLanguageCount()
	{
		$query = $this->db->table($this->table);
		return $query->countAll();
	}

	public function searchLanguage($limit, $start, $search, $col, $dir)
	{
		$query = $this->db->table($this->table)
		->like('lang_code', $search)
		->orLike('lang_name', $search)
		->limit($limit, $start)
		->orderBy($col, $dir)
		->get();
		return $query->getResult();
	}

	public function searchLanguageCount($search)
	{
		$query = $this->db->table($this->table)
		->selectCount($this->primaryKey, 'total')
		->like('lang_code', $search)
		->orLike('lang_name', $search)
		->get();
		return $query->getRow()->total;
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
