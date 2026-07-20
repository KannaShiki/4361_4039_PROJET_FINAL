<?php

namespace App\Models;

use CodeIgniter\Model;

class FeeBracketModel extends Model
{
    protected $table = 'fee_brackets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['operation_type_id', 'min_amount', 'max_amount', 'fee_amount', 'fee_percentage'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getFeeForAmount(int $operationTypeId, float $amount): ?array
    {
        return $this->where('operation_type_id', $operationTypeId)
                    ->where('min_amount <=', $amount)
                    ->where('max_amount >=', $amount)
                    ->first();
    }
}
