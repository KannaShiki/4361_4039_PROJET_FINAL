<?php

namespace App\Controllers;

use App\Models\OperatorPrefixModel;
use App\Models\OperationTypeModel;
use App\Models\FeeBracketModel;
use App\Models\ClientModel;

class Admin extends BaseController
{
    protected $prefixModel;
    protected $operationTypeModel;
    protected $feeBracketModel;
    protected $clientModel;

    public function __construct()
    {
        $this->prefixModel = new OperatorPrefixModel();
        $this->operationTypeModel = new OperationTypeModel();
        $this->feeBracketModel = new FeeBracketModel();
        $this->clientModel = new ClientModel();
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
            $this->prefixModel->insert(['prefix' => $prefix]);
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
            $this->operationTypeModel->insert([
                'code' => $code,
                'name' => $name,
                'description' => $description
            ]);
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
        
        if (empty($operationTypeId) || empty($minAmount) || empty($maxAmount) || empty($feeAmount)) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Tous les champs sont obligatoires');
        }

        if ($minAmount >= $maxAmount) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Le montant minimum doit etre inferieur au montant maximum');
        }

        try {
            $this->feeBracketModel->insert([
                'operation_type_id' => $operationTypeId,
                'min_amount' => $minAmount,
                'max_amount' => $maxAmount,
                'fee_amount' => $feeAmount,
                'fee_percentage' => $feePercentage ?? 0
            ]);
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
        
        if (empty($operationTypeId) || empty($minAmount) || empty($maxAmount) || empty($feeAmount)) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Tous les champs sont obligatoires');
        }

        if ($minAmount >= $maxAmount) {
            return redirect()->to('/admin/fee-brackets')->with('error', 'Le montant minimum doit etre inferieur au montant maximum');
        }

        try {
            $this->feeBracketModel->update($id, [
                'operation_type_id' => $operationTypeId,
                'min_amount' => $minAmount,
                'max_amount' => $maxAmount,
                'fee_amount' => $feeAmount,
                'fee_percentage' => $feePercentage ?? 0
            ]);
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
        $data['clients'] = $this->clientModel->orderBy('phone_number', 'ASC')->findAll();
        $data['title'] = 'Situation des Comptes Clients';
        return view('admin/client_accounts', $data);
    }
}
