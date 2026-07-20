<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\OperatorPrefixModel;

class Client extends BaseController
{
    protected $clientModel;
    protected $prefixModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->prefixModel = new OperatorPrefixModel();
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
}
