<!DOCTYPE html>
<html>
<head>
    <title>Reçu de Paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reçu de Paiement</h1>
            <p>Date: {{ $date }}</p>
        </div>
        <div class="details">
            <p><strong>Nom et Prénom:</strong> {{ $nom_prenom }}</p>
            <p><strong>Téléphone:</strong> {{ $Telephone }}</p>
            <p><strong>Formation:</strong> {{ $formation }}</p>
            <p><strong>Date de Début:</strong> {{ $date_debut }}</p>
            <p><strong>Date de Fin:</strong> {{ $date_fin }}</p>
            <p><strong>Prix Réel:</strong> {{ $prix_reel }} MRU</p>
            <p><strong>Montant Payé:</strong> {{ $montant_paye }} MRU</p>
            <p><strong>Reste à Payer:</strong> {{ $reste_a_payer }} MRU</p>
            <p><strong>Mode de Paiement:</strong> {{ $Mode_peiment }}</p>
        </div>
        <div class="footer">
            <p><strong>Par:</strong> {{ $par }}</p>
            <p><strong>Signature Autorisée:</strong> {{ $signature }}</p>
        </div>
        <div class="signature">
            <p>Signature:</p>
        </div>
    </div>
</body>
</html>
