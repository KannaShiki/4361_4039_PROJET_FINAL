<?php

namespace App\Models;

use CodeIgniter\Model;

class OtherOperatorPrefixModel extends Model
{
    protected $table = 'other_operator_prefixes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['prefix', 'operator_name'];

    protected $useTimestamps = false;

    public function getAllPrefixes(): array
    {
        return $this->orderBy('prefix', 'ASC')->findAll();
    }

    public function isValidOtherPrefix(string $phoneNumber): ?array
    {
        if (strlen($phoneNumber) < 3) {
            return null;
        }
        $prefix = substr($phoneNumber, 0, 3);
        return $this->where('prefix', $prefix)->first();
    }

    public function getOperatorByPrefix(string $prefix): ?array
    {
        return $this->where('prefix', $prefix)->first();
    }
}
