<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperatorPrefixModel;
use App\Models\TransactionModel;
use App\Models\OperationTypeModel;
use App\Models\FeeBracketModel;
use App\Models\OtherOperatorPrefixModel;
use App\Models\OperatorCommissionModel;

class Client extends BaseController
{
    protected $clientModel;
    protected $prefixModel;
    protected $transactionModel;
    protected $operationTypeModel;
    protected $feeBracketModel;
    protected $otherOperatorPrefixModel;
    protected $operatorCommissionModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->prefixModel = new OperatorPrefixModel();
        $this->transactionModel = new TransactionModel();
        $this->operationTypeModel = new OperationTypeModel();
        $this->feeBracke->set('savings_percentage', $nPercentage)
                        ->update();tModel = new FeeBracketModel();
        $this->otherOperatorPrefixModel = new OtherOperatorPrefixModel();
        $this->operatorCommissionModel = new OperatorCommissionModel();
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
        
        // Get recent transactions with fee details
        $recentTransactions = $this->transactionModel->where('client_id', $clientId)
                                                    ->orderBy('created_at', 'DESC')
                                                    ->limit(5)
                                                    ->findAll();

        return view('client/dashboard', [
            'client' => $client,
            'recentTransactions' => $recentTransactions
        ]);
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

        $phone = session()->get('phone_number');
        $clientModel = model('ClientModel');
        $client = $clientModel->where('phone_number',$phone)->first();

        if(!$client){
            return redirect()->back->with('error'-'client non trouve');
        }

        $amount = (float) $this->request->getPost('amount');
        $percentage = (float) $client['saving_percentage'];
        $savingAmount = $amount * ($percentage/100);
        $mainAmount = $amount-$savingAmount;

        $clientModel->update($client['id'],['balance'=> $client[saving_balance]+$savingAmount]);
        $transactionModel = model('TransactionModel');
        $transactionModel->insert([])

        if ($amount <= 0) {
            return redirect()->to('/client/deposit')->with('error', 'Le montant doit etre superieur a 0');
        }
       

        $client = $this->clientModel->find($clientId);
        $balanceBefore = $client['balance'];
        $balanceAfter = $balanceBefore + $amount;

        $this->clientModel->updateBalance($clientId, $balanceAfter);

        $operationType = $this->operationTypeModel->getByCode('depot');

        $this->transactionModel->createTransaction([
            'client_id' => $clientId,
            'operation_type_id' => $operationType['id'],
            'amount' => $amount,
            'fee' => 0,
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

        $operationType = $this->operationTypeModel->getByCode('retrait');
        $fee = $this->feeBracketModel->calculateFee($operationType['id'], $amount, false);

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

    public function multiTransfer(): string
    {
        return view('client/multi_transfer');
    }

    public function processMultiTransfer()
    {
        $clientId = session()->get('client_id');
        if (!$clientId) {
            return redirect()->to('/client');
        }

        $amount = (float) $this->request->getPost('amount');
        $recipients = $this->request->getPost('recipients');
        $includeWithdrawalFee = $this->request->getPost('include_withdrawal_fee') === 'on';

        if ($amount <= 0) {
            return redirect()->to('/client/multi-transfer')->with('error', 'Le montant doit etre superieur a 0');
        }

        if (empty($recipients) || !is_array($recipients)) {
            return redirect()->to('/client/multi-transfer')->with('error', 'Au moins un destinataire est requis');
        }

        // Filter out empty recipients
        $recipients = array_filter($recipients, function($phone) {
            return !empty(trim($phone));
        });

        if (count($recipients) < 1) {
            return redirect()->to('/client/multi-transfer')->with('error', 'Au moins un destinataire est requis');
        }

        // Validate that amount is divisible by number of recipients
        $recipientCount = count($recipients);
        $amountPerRecipient = $amount / $recipientCount;

        // Check if amount is evenly divisible
        if (abs($amountPerRecipient * $recipientCount - $amount) > 0.01) {
            return redirect()->to('/client/multi-transfer')->with('error', 'Le montant doit etre divisible par le nombre de destinataires');
        }

        // Validate all recipient phone numbers
        foreach ($recipients as $recipientPhone) {
            if (!$this->prefixModel->isValidPrefix($recipientPhone)) {
                return redirect()->to('/client/multi-transfer')->with('error', 'Le prefixe du numero ' . $recipientPhone . ' n\'est pas valide');
            }
        }

        $client = $this->clientModel->find($clientId);
        $balanceBefore = $client['balance'];

        $operationType = $this->operationTypeModel->getByCode('transfert');
        
        // Calculate fees for each recipient
        $totalFee = 0;
        $withdrawalFeePerRecipient = 0;
        
        if ($includeWithdrawalFee) {
            $withdrawalOperationType = $this->operationTypeModel->getByCode('retrait');
            $withdrawalFeePerRecipient = $this->feeBracketModel->calculateFee($withdrawalOperationType['id'], $amountPerRecipient, false);
        }
        
        foreach ($recipients as $recipientPhone) {
            // Check if recipient is from another operator
            $otherOperator = $this->otherOperatorPrefixModel->isValidOtherPrefix($recipientPhone);
            $isOtherOperator = $otherOperator !== null;
            
            $fee = $this->feeBracketModel->calculateFee($operationType['id'], $amountPerRecipient, $isOtherOperator);
            $totalFee += $fee + $withdrawalFeePerRecipient;
        }
        
        $totalAmount = $amount + $totalFee;

        if ($balanceBefore < $totalAmount) {
            return redirect()->to('/client/multi-transfer')->with('error', 'Solde insuffisant. Solde: ' . $balanceBefore . ' Ar, Montant: ' . $totalAmount . ' Ar');
        }

        $balanceAfter = $balanceBefore - $totalAmount;

        // Start transaction for atomic operation
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update sender balance
            $this->clientModel->updateBalance($clientId, $balanceAfter);

            // Create main transaction record
            $this->transactionModel->createTransaction([
                'client_id' => $clientId,
                'operation_type_id' => $operationType['id'],
                'amount' => $amount,
                'fee' => $totalFee,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'recipient_phone' => implode(', ', $recipients),
                'description' => 'Multi-envoi de ' . $amount . ' Ar vers ' . $recipientCount . ' destinataires (Frais: ' . $totalFee . ' Ar)',
                'include_withdrawal_fee' => $includeWithdrawalFee ? 1 : 0,
                'is_multi_send' => 1,
                'operator_id' => null
            ]);

            // Get the transaction ID
            $transactionId = $db->insertID();

            // Process each recipient
            $multiSendRecipients = [];
            foreach ($recipients as $recipientPhone) {
                // Create or get recipient
                $recipient = $this->clientModel->getClientByPhone($recipientPhone);
                if ($recipient === null) {
                    $this->clientModel->createClient($recipientPhone);
                    $recipient = $this->clientModel->getClientByPhone($recipientPhone);
                }

                // Update recipient balance
                $recipientBalanceBefore = $recipient['balance'];
                $recipientBalanceAfter = $recipientBalanceBefore + $amountPerRecipient;
                $this->clientModel->updateBalance($recipient['id'], $recipientBalanceAfter);

                // Add to multi-send recipients
                $multiSendRecipients[] = [
                    'transaction_id' => $transactionId,
                    'recipient_phone' => $recipientPhone,
                    'amount' => $amountPerRecipient,
                    'fee' => $withdrawalFeePerRecipient
                ];
            }

            // Insert multi-send recipients
            $multiSendModel = new \App\Models\MultiSendRecipientModel();
            $multiSendModel->createRecipients($multiSendRecipients);

            $db->transComplete();

            return redirect()->to('/client/dashboard')->with('success', 'Multi-envoi effectue avec succes vers ' . $recipientCount . ' destinataires');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/client/multi-transfer')->with('error', 'Erreur lors du multi-envoi: ' . $e->getMessage());
        }
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

        $operationType = $this->operationTypeModel->getByCode('transfert');
        
        // Calculate transfer fee (always for sender's operator)
        $transferFee = $this->feeBracketModel->calculateFee($operationType['id'], $amount, false);
        
        // Check if recipient is from another operator
        $otherOperator = $this->otherOperatorPrefixModel->isValidOtherPrefix($recipientPhone);
        $isOtherOperator = $otherOperator !== null;
        
        // Calculate commission for other operator if applicable
        $commission = 0;
        if ($isOtherOperator) {
            $commission = $this->operatorCommissionModel->calculateCommission($otherOperator['id'], $amount);
        }
        
        $includeWithdrawalFee = $this->request->getPost('include_withdrawal_fee') === 'on';
        
        // Calculate withdrawal fee if requested
        $withdrawalFee = 0;
        if ($includeWithdrawalFee) {
            $withdrawalOperationType = $this->operationTypeModel->getByCode('retrait');
            $withdrawalFee = $this->feeBracketModel->calculateFee($withdrawalOperationType['id'], $amount, false);
        }
        
        $totalFee = $transferFee + $commission + $withdrawalFee;
        $totalAmount = $amount + $totalFee;

        if ($balanceBefore < $totalAmount) {
            return redirect()->to('/client/transfer')->with('error', 'Solde insuffisant. Solde: ' . $balanceBefore . ' Ar, Montant: ' . $totalAmount . ' Ar');
        }

        $balanceAfter = $balanceBefore - $totalAmount;

        $this->clientModel->updateBalance($clientId, $balanceAfter);

        $this->transactionModel->createTransaction([
            'client_id' => $clientId,
            'operation_type_id' => $operationType['id'],
            'amount' => $amount,
            'fee' => $totalFee,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'recipient_phone' => $recipientPhone,
            'description' => 'Transfert de ' . $amount . ' Ar vers ' . $recipientPhone . ' (Frais: ' . $transferFee . ' Ar' . ($commission > 0 ? ', Commission: ' . $commission . ' Ar' : '') . ($withdrawalFee > 0 ? ', Frais retrait: ' . $withdrawalFee . ' Ar' : '') . ')',
            'include_withdrawal_fee' => $includeWithdrawalFee ? 1 : 0,
            'is_multi_send' => 0,
            'operator_id' => $isOtherOperator ? $otherOperator['id'] : null
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
