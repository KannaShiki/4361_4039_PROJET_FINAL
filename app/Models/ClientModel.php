<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['phone_number', 'balance'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'phone_number' => 'required|is_unique[clients.phone_number]',
        'balance' => 'numeric'
    ];

    protected $validationMessages = [
        'phone_number' => [
            'required' => 'Le numero de telephone est requis',
            'is_unique' => 'Ce numero de telephone existe deja'
        ]
    ];

    public function getClientByPhone(string $phoneNumber): ?array
    {
        return $this->where('phone_number', $phoneNumber)->first();
    }

    public function createClient(string $phoneNumber): bool
    {
        $data = [
            'phone_number' => $phoneNumber,
            'balance' => 0
        ];
        return $this->insert($data) !== false;
    }

    public function updateBalance(int $clientId, float $newBalance): bool
    {
        return $this->update($clientId, ['balance' => $newBalance]);
    }
}
