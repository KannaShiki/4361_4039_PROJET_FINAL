<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['client_id', 'operation_type_id', 'amount', 'fee', 'balance_before', 'balance_after', 'recipient_phone', 'description', 'include_withdrawal_fee', 'is_multi_send', 'operator_id'];

    protected $useTimestamps = false;

    public function getClientTransactions(int $clientId): array
    {
        return $this->where('client_id', $clientId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function createTransaction(array $data): bool
    {
        return $this->insert($data) !== false;
    }
}
