<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationTypeModel extends Model
{
    protected $table = 'operation_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['code', 'name', 'description'];

    protected $useTimestamps = false;

    public function getAllOperationTypes(): array
    {
        return $this->orderBy('id', 'ASC')->findAll();
    }

    public function getByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }
}
