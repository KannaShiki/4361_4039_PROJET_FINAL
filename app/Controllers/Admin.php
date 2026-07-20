<?php

namespace App\Controllers;

use App\Models\OperatorPrefixModel;
use App\Models\OperationTypeModel;
use App\Models\FeeBracketModel;
use App\Models\ClientModel;
use App\Models\OtherOperatorPrefixModel;
use App\Models\OperatorCommissionModel;

class Admin extends BaseController
{
    protected $prefixModel;
    protected $operationTypeModel;
    protected $feeBracketModel;
    protected $clientModel;
    protected $otherOperatorPrefixModel;
    protected $operatorCommissionModel;

    public function __construct()
    {
        helper('form');

        $this->prefixModel = new OperatorPrefixModel();
        $this->operationTypeModel = new OperationTypeModel();
        $this->feeBracketModel = new FeeBracketModel();
        $this->clientModel = new ClientModel();
        $this->otherOperatorPrefixModel = new OtherOperatorPrefixModel();
        $this->operatorCommissionModel = new OperatorCommissionModel();
    }

    // ==================== DASHBOARD ====================
    
    public function index(): string
    {
        $data['title'] = 'Dashboard Admin';
        return view('admin/dashboard', $data);
    }

    // ==================== PREFIXES OPERATEURS ====================
    
    public function prefixes(): string
    {
        $data['prefixes'] = $this->prefixModel->getAllPrefixes();
        $data['title'] = 'Gestion des Prefixes Operateurs';
        return view('admin/prefixes', $data);
    }

    public function addPrefix()
    {
        $prefix = $this->request->getPost('prefix');
        
        if (empty($prefix)) {
            return redirect()->to('/admin/prefixes')->with('error', 'Le prefixe est obligatoire');
        }

        if (!preg_match('/^\d{3}$/', $prefix)) {
            return redirect()->to('/admin/prefixes')->with('error', 'Le prefixe doit etre compose de 3 chiffres');
        }

        try {
            $data = ['prefix' => (string)$prefix];
            $this->prefixModel->insert($data);
            return redirect()->to('/admin/prefixes')->with('success', 'Prefixe ajoute avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/prefixes')->with('error', 'Ce prefixe existe deja');
        }
    }

    public function deletePrefix($id)
    {
        try {
            $this->prefixModel->delete($id);
            return redirect()->to('/admin/prefixes')->with('success', 'Prefixe supprime avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/prefixes')->with('error', 'Erreur lors de la suppression');
        }
    }

    // ==================== TYPES D'OPERATIONS ====================
    
    public function operationTypes(): string
    {
        $data['operationTypes'] = $this->operationTypeModel->getAllOperationTypes();
        $data['title'] = 'Gestion des Types d\'Operations';
        return view('admin/operation_types', $data);
    }

    public function addOperationType()
    {
        $code = $this->request->getPost('code');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        
        if (empty($code) || empty($name)) {
            return redirect()->to('/admin/operation-types')->with('error', 'Le code et le nom sont obligatoires');
        }

        try {
            $data = [
                'code' => (string)$code,
                'name' => (string)$name,
                'description' => $description ? (string)$description : null
            ];
            $this->operationTypeModel->insert($data);
            return redirect()->to('/admin/operation-types')->with('success', 'Type d\'operation ajoute avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/operation-types')->with('error', 'Ce code existe deja');
        }
    }

    public function deleteOperationType($id)
    {
        try {
            $this->operationTypeModel->delete($id);
            return redirect()->to('/admin/operation-types')->with('success', 'Type d\'operation supprime avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/operation-types')->with('error', 'Erreur lors de la suppression');
        }
    }

    // ==================== BAREMES DE FRAIS ====================

    protected function hasRequiredFeeBracketFields($operationTypeId, $minAmount, $maxAmount, $feeAmount): bool
    {
        return $operationTypeId !== null && $operationTypeId !== ''
            && $minAmount !== null && $minAmount !== ''
            && $maxAmount !== null && $maxAmount !== ''
            && $feeAmount !== null && $feeAmount !== '';
    }
    
    public function feeBrackets(): string
    {
        $data['feeBrackets'] = $this->feeBracketModel->getAllFeeBrackets();
        $data['operationTypes'] = $this->operationTypeModel->getAllOperationTypes();
        $data['title'] = 'Gestion des Baremes de Frais';
        return view('admin/fee_brackets', $data);
    }

    public function addFeeBracket()
    {
        $operationTypeId = $this->request->getPost('operation_type_id');
        $minAmount = $this->request->getPost('min_amount');
        $maxAmount = $this->request->getPost('max_amount');
        $feeAmount = $this->request->getPost('fee_amount');
        $feePercentage = $this->request->getPost('fee_percentage');
        
        if (!$this->hasRequiredFeeBracketFields($operationTypeId, $minAmount, $maxAmount, $feeAmount)) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Tous les champs sont obligatoires');
        }

        $minAmountValue = (float) $minAmount;
        $maxAmountValue = (float) $maxAmount;
        if ($minAmountValue >= $maxAmountValue) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Le montant minimum doit etre inferieur au montant maximum');
        }

        try {
            $data = [
                'operation_type_id' => (int)$operationTypeId,
                'min_amount' => $minAmountValue,
                'max_amount' => $maxAmountValue,
                'fee_amount' => (float)$feeAmount,
                'fee_percentage' => (float)($feePercentage !== null && $feePercentage !== '' ? $feePercentage : 0)
            ];
            $this->feeBracketModel->insert($data);
            return redirect()->to('/admin/fee-brackets')->with('success', 'Bareme de frais ajoute avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Erreur lors de l\'ajout');
        }
    }

    public function editFeeBracket($id): string
    {
        $data['feeBracket'] = $this->feeBracketModel->find($id);
        $data['operationTypes'] = $this->operationTypeModel->getAllOperationTypes();
        $data['title'] = 'Modifier le Bareme de Frais';
        
        if (!$data['feeBracket']) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Bareme non trouve');
        }
        
        return view('admin/edit_fee_bracket', $data);
    }

    public function updateFeeBracket($id)
    {
        $operationTypeId = $this->request->getPost('operation_type_id');
        $minAmount = $this->request->getPost('min_amount');
        $maxAmount = $this->request->getPost('max_amount');
        $feeAmount = $this->request->getPost('fee_amount');
        $feePercentage = $this->request->getPost('fee_percentage');
        
        if (!$this->hasRequiredFeeBracketFields($operationTypeId, $minAmount, $maxAmount, $feeAmount)) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Tous les champs sont obligatoires');
        }

        $minAmountValue = (float) $minAmount;
        $maxAmountValue = (float) $maxAmount;
        if ($minAmountValue >= $maxAmountValue) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Le montant minimum doit etre inferieur au montant maximum');
        }

        try {
            $data = [
                'operation_type_id' => (int)$operationTypeId,
                'min_amount' => $minAmountValue,
                'max_amount' => $maxAmountValue,
                'fee_amount' => (float)$feeAmount,
                'fee_percentage' => (float)($feePercentage !== null && $feePercentage !== '' ? $feePercentage : 0)
            ];
            $this->feeBracketModel->update($id, $data);
            return redirect()->to('/admin/fee-brackets')->with('success', 'Bareme de frais modifie avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Erreur lors de la modification');
        }
    }

    public function deleteFeeBracket($id)
    {
        try {
            $this->feeBracketModel->delete($id);
            return redirect()->to('/admin/fee-brackets')->with('success', 'Bareme de frais supprime avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Erreur lors de la suppression');
        }
    }

    // ==================== SITUATION DES COMPTES CLIENTS ====================

    public function clientAccounts(): string
    {
        $operatorFilter = $this->request->getGet('operator_filter');
        
        $clientsQuery = $this->clientModel->orderBy('phone_number', 'ASC');
        
        if ($operatorFilter) {
            $clientsQuery->like('phone_number', $operatorFilter, 'after');
        }
        
        $data['clients'] = $clientsQuery->findAll();
        $data['title'] = 'Situation des Comptes Clients';
        return view('admin/client_accounts', $data);
    }

    // ==================== PREFIXES AUTRES OPERATEURS ====================

    public function otherOperatorPrefixes(): string
    {
        $data['prefixes'] = $this->otherOperatorPrefixModel->getAllPrefixes();
        $data['title'] = 'Prefixes Autres Operateurs';
        return view('admin/other_operator_prefixes', $data);
    }

    public function addOtherOperatorPrefix()
    {
        $prefix = $this->request->getPost('prefix');
        $operatorName = $this->request->getPost('operator_name');

        if (empty($prefix) || empty($operatorName)) {
            return redirect()->to('/admin/other-operator-prefixes')->with('error', 'Tous les champs sont obligatoires');
        }

        try {
            $this->otherOperatorPrefixModel->insert([
                'prefix' => $prefix,
                'operator_name' => $operatorName
            ]);
            return redirect()->to('/admin/other-operator-prefixes')->with('success', 'Prefixe ajoute avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/other-operator-prefixes')->with('error', 'Erreur lors de l\'ajout (prefixe peut-etre deja existant)');
        }
    }

    public function deleteOtherOperatorPrefix($id)
    {
        try {
            $this->otherOperatorPrefixModel->delete($id);
            return redirect()->to('/admin/other-operator-prefixes')->with('success', 'Prefixe supprime avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/other-operator-prefixes')->with('error', 'Erreur lors de la suppression');
        }
    }

    // ==================== COMMISSIONS INTER-OPERATEURS ====================

    public function operatorCommissions(): string
    {
        $data['commissions'] = $this->operatorCommissionModel->getAllCommissions();
        $data['operators'] = $this->otherOperatorPrefixModel->getAllPrefixes();
        $data['title'] = 'Commissions Inter-Operateurs';
        return view('admin/operator_commissions', $data);
    }

    public function addOperatorCommission()
    {
        $operatorPrefixId = $this->request->getPost('operator_prefix_id');
        $commissionPercentage = $this->request->getPost('commission_percentage');
        $commissionAmount = $this->request->getPost('commission_amount');

        if (empty($operatorPrefixId)) {
            return redirect()->to('/admin/operator-commissions')->with('error', 'L\'operateur est obligatoire');
        }

        try {
            $this->operatorCommissionModel->insert([
                'operator_prefix_id' => $operatorPrefixId,
                'commission_percentage' => $commissionPercentage ?? 0,
                'commission_amount' => $commissionAmount ?? 0
            ]);
            return redirect()->to('/admin/operator-commissions')->with('success', 'Commission ajoutee avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/operator-commissions')->with('error', 'Erreur lors de l\'ajout');
        }
    }

    public function editOperatorCommission($id): string
    {
        $data['commission'] = $this->operatorCommissionModel->find($id);
        $data['operators'] = $this->otherOperatorPrefixModel->getAllPrefixes();
        $data['title'] = 'Modifier Commission';
        return view('admin/edit_operator_commission', $data);
    }

    public function updateOperatorCommission($id)
    {
        $operatorPrefixId = $this->request->getPost('operator_prefix_id');
        $commissionPercentage = $this->request->getPost('commission_percentage');
        $commissionAmount = $this->request->getPost('commission_amount');

        try {
            $this->operatorCommissionModel->update($id, [
                'operator_prefix_id' => $operatorPrefixId,
                'commission_percentage' => $commissionPercentage ?? 0,
                'commission_amount' => $commissionAmount ?? 0
            ]);
            return redirect()->to('/admin/operator-commissions')->with('success', 'Commission modifiee avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/operator-commissions')->with('error', 'Erreur lors de la modification');
        }
    }

    public function deleteOperatorCommission($id)
    {
        try {
            $this->operatorCommissionModel->delete($id);
            return redirect()->to('/admin/operator-commissions')->with('success', 'Commission supprimee avec succes');
        } catch (\Exception $e) {
            return redirect()->to('/admin/operator-commissions')->with('error', 'Erreur lors de la suppression');
        }
    }

    // ==================== RAPPORTS FINANCIERS ====================

    public function financialReports(): string
    {
        $db = \Config\Database::connect();
        
        // Calculate total fees by operation type
        $feesByOperation = $db->query("
            SELECT ot.name as operation_name, SUM(t.fee) as total_fees, COUNT(*) as transaction_count
            FROM transactions t
            JOIN operation_types ot ON ot.id = t.operation_type_id
            GROUP BY t.operation_type_id
            ORDER BY total_fees DESC
        ")->getResultArray();
        
        // Calculate fees by operator (own vs other)
        $feesByOperator = $db->query("
            SELECT 
                CASE WHEN t.operator_id IS NULL THEN 'Operateur propre' ELSE 'Autres operateurs' END as operator_type,
                SUM(t.fee) as total_fees,
                COUNT(*) as transaction_count
            FROM transactions t
            GROUP BY CASE WHEN t.operator_id IS NULL THEN 'Operateur propre' ELSE 'Autres operateurs' END
        ")->getResultArray();
        
        // Calculate amounts to send to other operators
        $amountsToSend = $db->query("
            SELECT 
                oop.operator_name,
                oop.prefix,
                SUM(t.fee) as total_fees,
                COUNT(*) as transaction_count
            FROM transactions t
            JOIN other_operator_prefixes oop ON oop.id = t.operator_id
            WHERE t.operator_id IS NOT NULL
            GROUP BY t.operator_id
            ORDER BY total_fees DESC
        ")->getResultArray();
        
        $data['feesByOperation'] = $feesByOperation;
        $data['feesByOperator'] = $feesByOperator;
        $data['amountsToSend'] = $amountsToSend;
        $data['title'] = 'Rapports Financiers';
        return view('admin/financial_reports', $data);
    }
}
