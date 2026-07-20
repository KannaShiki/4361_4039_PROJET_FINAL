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

    public function getAllFeeBrackets(): array
    {
        return $this->select('fee_brackets.*, operation_types.name as operation_name')
                    ->join('operation_types', 'operation_types.id = fee_brackets.operation_type_id')
                    ->orderBy('operation_type_id', 'ASC')
                    ->orderBy('min_amount', 'ASC')
                    ->findAll();
    }

    public function getByOperationType(int $operationTypeId): array
    {
        return $this->where('operation_type_id', $operationTypeId)
                    ->orderBy('min_amount', 'ASC')
                    ->findAll();
    }

    public function calculateFee(int $operationTypeId, float $amount): float
    {
        $bracket = $this->where('operation_type_id', $operationTypeId)
                        ->where('min_amount <=', $amount)
                        ->where('max_amount >=', $amount)
                        ->first();

        if ($bracket) {
            $fee = $bracket['fee_amount'];
            if ($bracket['fee_percentage'] > 0) {
                $fee += ($amount * $bracket['fee_percentage'] / 100);
            }
            return $fee;
        }

        return 0;
    }
}
