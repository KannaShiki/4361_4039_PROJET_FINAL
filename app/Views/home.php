<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('/assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h1 class="mb-0">Mobile Money</h1>
                        <p class="mb-0">Bienvenue</p>
                    </div>
                    <div class="card-body text-center py-5">
                        <h2 class="mb-4">Choisissez votre interface</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="/admin/login" class="btn btn-primary btn-lg w-100 py-4">
                                    <i class="bi bi-gear-fill"></i><br>
                                    Admin
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="/client" class="btn btn-success btn-lg w-100 py-4">
                                    <i class="bi bi-person-fill"></i><br>
                                    Client
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
