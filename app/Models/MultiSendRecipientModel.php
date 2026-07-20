<?php

namespace App\Models;

use CodeIgniter\Model;

class MultiSendRecipientModel extends Model
{
    protected $table = 'multi_send_recipients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['transaction_id', 'recipient_phone', 'amount', 'fee'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function getRecipientsByTransactionId(int $transactionId): array
    {
        return $this->where('transaction_id', $transactionId)->findAll();
    }

    public function createRecipients(array $recipients): bool
    {
        return $this->insertBatch($recipients) !== false;
    }

    public function deleteRecipientsByTransactionId(int $transactionId): bool
    {
        return $this->where('transaction_id', $transactionId)->delete();
    }
}
