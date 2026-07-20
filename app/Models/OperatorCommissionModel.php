<?php

namespace App\Models;

use CodeIgniter\Model;

class OperatorCommissionModel extends Model
{
    protected $table = 'operator_commissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['operator_prefix_id', 'commission_percentage', 'commission_amount'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function getAllCommissions(): array
    {
        return $this->select('operator_commissions.*, other_operator_prefixes.prefix, other_operator_prefixes.operator_name')
                    ->join('other_operator_prefixes', 'other_operator_prefixes.id = operator_commissions.operator_prefix_id')
                    ->orderBy('other_operator_prefixes.operator_name', 'ASC')
                    ->findAll();
    }

    public function getCommissionByOperatorId(int $operatorPrefixId): ?array
    {
        return $this->where('operator_prefix_id', $operatorPrefixId)->first();
    }

    public function calculateCommission(int $operatorPrefixId, float $amount): float
    {
        $commission = $this->getCommissionByOperatorId($operatorPrefixId);
        if (!$commission) {
            return 0;
        }

        $commissionAmount = $commission['commission_amount'];
        if ($commission['commission_percentage'] > 0) {
            $commissionAmount += ($amount * $commission['commission_percentage'] / 100);
        }

        return $commissionAmount;
    }
}
