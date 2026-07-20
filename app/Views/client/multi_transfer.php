<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Multi-Envoi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('/assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/client/dashboard') ?>">Mobile Money</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('/client/dashboard') ?>">Retour</a>
                <a class="nav-link" href="<?= base_url('/client/logout') ?>">Deconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">Multi-Envoi</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->get('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->get('error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('/client/process-multi-transfer') ?>" method="post">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Montant total (Ar)</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                                       placeholder="Ex: 50000" required min="0.01">
                                <div class="form-text">Ce montant sera divise equitablement entre tous les destinataires</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Destinataires</label>
                                <div id="recipients-container">
                                    <div class="row mb-2 recipient-row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="recipients[]" 
                                                   placeholder="Ex: 0341234567" required>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="form-control-plaintext amount-per-recipient">0 Ar</span>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-recipient" disabled>X</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-recipient">
                                    <i class="bi bi-plus"></i> Ajouter un destinataire
                                </button>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="include_withdrawal_fee" name="include_withdrawal_fee">
                                <label class="form-check-label" for="include_withdrawal_fee">
                                    Inclure les frais de retrait dans le montant envoye
                                </label>
                                <div class="form-text">Les destinataires recevront le montant exact sans frais de retrait</div>
                            </div>
                            
                            <div class="alert alert-info">
                                <strong>Resume:</strong>
                                <div>Nombre de destinataires: <span id="recipient-count">1</span></div>
                                <div>Montant par destinataire: <span id="amount-per-recipient">0</span> Ar</div>
                                <div>Frais totaux estimes: <span id="total-fee">0</span> Ar</div>
                                <div>Montant total a debiter: <span id="total-amount">0</span> Ar</div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info">Effectuer le multi-envoi</button>
                                <a href="<?= base_url('/client/dashboard') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const recipientsContainer = document.getElementById('recipients-container');
            const addRecipientBtn = document.getElementById('add-recipient');
            const recipientCountSpan = document.getElementById('recipient-count');
            const amountPerRecipientSpan = document.getElementById('amount-per-recipient');
            const totalFeeSpan = document.getElementById('total-fee');
            const totalAmountSpan = document.getElementById('total-amount');
            
            function updateSummary() {
                const amount = parseFloat(amountInput.value) || 0;
                const recipientRows = document.querySelectorAll('.recipient-row');
                const recipientCount = recipientRows.length;
                
                recipientCountSpan.textContent = recipientCount;
                
                if (recipientCount > 0 && amount > 0) {
                    const amountPerRecipient = amount / recipientCount;
                    amountPerRecipientSpan.textContent = amountPerRecipient.toFixed(2);
                    
                    // Update amount per recipient display
                    recipientRows.forEach(row => {
                        row.querySelector('.amount-per-recipient').textContent = amountPerRecipient.toFixed(2) + ' Ar';
                    });
                    
                    // Estimate fees (simplified)
                    const estimatedFee = recipientCount * 100; // 100 Ar per recipient
                    totalFeeSpan.textContent = estimatedFee;
                    totalAmountSpan.textContent = (amount + estimatedFee).toFixed(2);
                } else {
                    amountPerRecipientSpan.textContent = '0';
                    totalFeeSpan.textContent = '0';
                    totalAmountSpan.textContent = '0';
                    
                    recipientRows.forEach(row => {
                        row.querySelector('.amount-per-recipient').textContent = '0 Ar';
                    });
                }
                
                // Enable/disable remove buttons
                const removeButtons = document.querySelectorAll('.remove-recipient');
                removeButtons.forEach(btn => {
                    btn.disabled = recipientRows.length <= 1;
                });
            }
            
            addRecipientBtn.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'row mb-2 recipient-row';
                newRow.innerHTML = `
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="recipients[]" 
                               placeholder="Ex: 0341234567" required>
                    </div>
                    <div class="col-md-4">
                        <span class="form-control-plaintext amount-per-recipient">0 Ar</span>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-recipient">X</button>
                    </div>
                `;
                recipientsContainer.appendChild(newRow);
                
                newRow.querySelector('.remove-recipient').addEventListener('click', function() {
                    newRow.remove();
                    updateSummary();
                });
                
                updateSummary();
            });
            
            // Add event listeners to existing remove button
            document.querySelectorAll('.remove-recipient').forEach(btn => {
                btn.addEventListener('click', function() {
                    btn.closest('.recipient-row').remove();
                    updateSummary();
                });
            });
            
            amountInput.addEventListener('input', updateSummary);
            updateSummary();
        });
    </script>
</body>
</html>
