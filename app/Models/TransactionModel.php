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
    protected $allowedFields = ['client_id', 'operation_type_id', 'amount', 'fee', 'balance_before', 'balance_after', 'recipient_phone', 'description'];

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

    public function getAllTransactions(): array
    {
        return $this->select('transactions.*, clients.phone_number, operation_types.name as operation_name')
                    ->join('clients', 'clients.id = transactions.client_id')
                    ->join('operation_types', 'operation_types.id = transactions.operation_type_id')
                    ->orderBy('transactions.created_at', 'DESC')
                    ->findAll();
    }

    public function getTransactionsByClientId(int $clientId): array
    {
        return $this->select('transactions.*, operation_types.name as operation_name')
                    ->join('operation_types', 'operation_types.id = transactions.operation_type_id')
                    ->where('client_id', $clientId)
                    ->orderBy('transactions.created_at', 'DESC')
                    ->findAll();
    }
}
