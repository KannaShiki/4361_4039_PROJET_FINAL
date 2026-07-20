<?php

namespace App\Models;

use CodeIgniter\Model;

class OperatorPrefixModel extends Model
{
    protected $table = 'operator_prefixes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['prefix'];

    protected $useTimestamps = false;

    public function getAllPrefixes(): array
    {
        return $this->findAll();
    }

    public function isValidPrefix(string $phoneNumber): bool
    {
        if (strlen($phoneNumber) < 3) {
            return false;
        }
        $prefix = substr($phoneNumber, 0, 3);
        $result = $this->where('prefix', $prefix)->first();
        return $result !== null;
    }

    public function getPrefix(string $phoneNumber): ?string
    {
        if (strlen($phoneNumber) < 3) {
            return null;
        }
        return substr($phoneNumber, 0, 3);
    }
}
