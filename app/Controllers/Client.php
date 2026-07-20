<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperatorPrefixModel;
use App\Models\TransactionModel;
use App\Models\OperationTypeModel;
use App\Models\FeeBracketModel;

class Client extends BaseController
{
    protected $clientModel;
    protected $prefixModel;
    protected $transactionModel;
    protected $operationTypeModel;
    protected $feeBracketModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->prefixModel = new OperatorPrefixModel();
        $this->transactionModel = new TransactionModel();
        $this->operationTypeModel = new OperationTypeModel();
        $this->feeBracketModel = new FeeBracketModel();
    }

    public function index(): string
    {
        return view('client/login');
    }

    public function login()
    {
        $phoneNumber = $this->request->getPost('phone_number');

        if (empty($phoneNumber)) {
            return redirect()->to('/client')->with('error', 'Le numero de telephone est requis');
        }

        $phoneNumber = trim($phoneNumber);

        if (!$this->prefixModel->isValidPrefix($phoneNumber)) {
            return redirect()->to('/client')->with('error', 'Le prefixe du numero n\'est pas valide');
        }

        $client = $this->clientModel->getClientByPhone($phoneNumber);

        if ($client === null) {
            $this->clientModel->createClient($phoneNumber);
            $client = $this->clientModel->getClientByPhone($phoneNumber);
        }

        session()->set('client_id', $client['id']);
        session()->set('phone_number', $client['phone_number']);

        return redirect()->to('/client/dashboard');
    }

    public function dashboard(): string
    {
        $clientId = session()->get('client_id');

        if ($clientId === null) {
            return redirect()->to('/client');
        }

        $client = $this->clientModel->find($clientId);

        return view('client/dashboard', ['client' => $client]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/client');
    }

    public function deposit(): string
    {
        $clientId = session()->get('client_id');
        if ($clientId === null) {
            return redirect()->to('/client');
        }
        return view('client/deposit');
    }

    public function processDeposit()
    {
        $clientId = session()->get('client_id');
        if ($clientId === null) {
            return redirect()->to('/client');
        }

        $amount = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            return redirect()->to('/client/deposit')->with('error', 'Le montant doit etre superieur a 0');
        }

        $client = $this->clientModel->find($clientId);
        $balanceBefore = $client['balance'];

        $operationType = $this->operationTypeModel->getByCode('depot');
        $fee = $this->feeBracketModel->calculateFee($operationType['id'], $amount);

        $balanceAfter = $balanceBefore + $amount;

        $this->clientModel->updateBalance($clientId, $balanceAfter);

        $this->transactionModel->createTransaction([
            'client_id' => $clientId,
            'operation_type_id' => $operationType['id'],
            'amount' => $amount,
            'fee' => $fee,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => 'Depot de ' . $amount . ' Ar'
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Depot effectue avec succes');
    }

    public function withdraw(): string
    {
        $clientId = session()->get('client_id');
        if ($clientId === null) {
            return redirect()->to('/client');
        }
        return view('client/withdraw');
    }

    public function processWithdraw()
    {
        $clientId = session()->get('client_id');
        if ($clientId === null) {
            return redirect()->to('/client');
        }

        $amount = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            return redirect()->to('/client/withdraw')->with('error', 'Le montant doit etre superieur a 0');
        }

        $client = $this->clientModel->find($clientId);
        $balanceBefore = $client['balance'];

        $operationType = $this->operationTypeModel->getOperationTypeByCode('retrait');
        $feeBracket = $this->feeBracketModel->getFeeForAmount($operationType['id'], $amount);

        if ($feeBracket === null) {
            return redirect()->to('/client/withdraw')->with('error', 'Aucun bareme de frais trouve pour ce montant');
        }

        $fee = $feeBracket['fee_amount'];
        $totalAmount = $amount + $fee;

        if ($balanceBefore < $totalAmount) {
            return redirect()->to('/client/withdraw')->with('error', 'Solde insuffisant. Solde: ' . $balanceBefore . ' Ar, Montant: ' . $totalAmount . ' Ar');
        }

        $balanceAfter = $balanceBefore - $totalAmount;

        $this->clientModel->updateBalance($clientId, $balanceAfter);

        $this->transactionModel->createTransaction([
            'client_id' => $clientId,
            'operation_type_id' => $operationType['id'],
            'amount' => $amount,
            'fee' => $fee,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => 'Retrait de ' . $amount . ' Ar (Frais: ' . $fee . ' Ar)'
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Retrait effectue avec succes');
    }

    public function transfer(): string
    {
        $clientId = session()->get('client_id');
        if ($clientId === null) {
            return redirect()->to('/client');
        }
        return view('client/transfer');
    }

    public function processTransfer()
    {
        $clientId = session()->get('client_id');
        if ($clientId === null) {
            return redirect()->to('/client');
        }

        $recipientPhone = $this->request->getPost('recipient_phone');
        $amount = (float) $this->request->getPost('amount');

        if (empty($recipientPhone)) {
            return redirect()->to('/client/transfer')->with('error', 'Le numero du destinataire est requis');
        }

        if ($amount <= 0) {
            return redirect()->to('/client/transfer')->with('error', 'Le montant doit etre superieur a 0');
        }

        if (!$this->prefixModel->isValidPrefix($recipientPhone)) {
            return redirect()->to('/client/transfer')->with('error', 'Le prefixe du numero du destinataire n\'est pas valide');
        }

        $client = $this->clientModel->find($clientId);
        $balanceBefore = $client['balance'];

        $operationType = $this->operationTypeModel->getOperationTypeByCode('transfert');
        $feeBracket = $this->feeBracketModel->getFeeForAmount($operationType['id'], $amount);

        if ($feeBracket === null) {
            return redirect()->to('/client/transfer')->with('error', 'Aucun bareme de frais trouve pour ce montant');
        }

        $fee = $feeBracket['fee_amount'];
        $totalAmount = $amount + $fee;

        if ($balanceBefore < $totalAmount) {
            return redirect()->to('/client/transfer')->with('error', 'Solde insuffisant. Solde: ' . $balanceBefore . ' Ar, Montant: ' . $totalAmount . ' Ar');
        }

        $balanceAfter = $balanceBefore - $totalAmount;

        $this->clientModel->updateBalance($clientId, $balanceAfter);

        $this->transactionModel->createTransaction([
            'client_id' => $clientId,
            'operation_type_id' => $operationType['id'],
            'amount' => $amount,
            'fee' => $fee,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'recipient_phone' => $recipientPhone,
            'description' => 'Transfert de ' . $amount . ' Ar vers ' . $recipientPhone . ' (Frais: ' . $fee . ' Ar)'
        ]);

        $recipient = $this->clientModel->getClientByPhone($recipientPhone);
        if ($recipient === null) {
            $this->clientModel->createClient($recipientPhone);
            $recipient = $this->clientModel->getClientByPhone($recipientPhone);
        }

        $recipientBalanceBefore = $recipient['balance'];
        $recipientBalanceAfter = $recipientBalanceBefore + $amount;

        $this->clientModel->updateBalance($recipient['id'], $recipientBalanceAfter);

        $this->transactionModel->createTransaction([
            'client_id' => $recipient['id'],
            'operation_type_id' => $operationType['id'],
            'amount' => $amount,
            'fee' => 0,
            'balance_before' => $recipientBalanceBefore,
            'balance_after' => $recipientBalanceAfter,
            'description' => 'Reception de transfert de ' . $amount . ' Ar de ' . $client['phone_number']
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Transfert effectue avec succes');
    }

    public function history(): string
    {
        $clientId = session()->get('client_id');
        if ($clientId === null) {
            return redirect()->to('/client');
        }

        $transactions = $this->transactionModel->getClientTransactions($clientId);
        return view('client/history', ['transactions' => $transactions]);
    }
}
