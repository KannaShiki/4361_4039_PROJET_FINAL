<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Dashboard Admin</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-telephone-fill"></i> Préfixes
                </h5>
                <p class="card-text fs-4">
                    <a href="/admin/prefixes" class="text-white text-decoration-none">Gérer</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-list-check"></i> Types d'Opérations
                </h5>
                <p class="card-text fs-4">
                    <a href="/admin/operation-types" class="text-white text-decoration-none">Gérer</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-cash-stack"></i> Barèmes de Frais
                </h5>
                <p class="card-text fs-4">
                    <a href="/admin/fee-brackets" class="text-white text-decoration-none">Gérer</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-people-fill"></i> Comptes Clients
                </h5>
                <p class="card-text fs-4">
                    <a href="/admin/client-accounts" class="text-white text-decoration-none">Voir</a>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Bienvenue dans le panneau d'administration</h5>
                <p class="card-text">
                    Utilisez le menu de navigation ou les cartes ci-dessus pour acceder aux differentes fonctionnalites de gestion.
                </p>
                <ul>
                    <li><strong>Prefixes :</strong> Gerer les prefixes de numeros autorises (ex: 033, 037)</li>
                    <li><strong>Types d'Operations :</strong> Gerer les types d'operations disponibles (depot, retrait, transfert)</li>
                    <li><strong>Baremes de Frais :</strong> Configurer les frais par tranche pour chaque type d'operation</li>
                    <li><strong>Comptes Clients :</strong> Visualiser la situation des comptes clients et leurs soldes</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
