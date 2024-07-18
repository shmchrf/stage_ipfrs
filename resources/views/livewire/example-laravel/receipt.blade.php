<!DOCTYPE html>
<html>
<head>
    <title>Reçu de Paiement Étudiant</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            width: 70%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #777;
        }
        .details {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .details p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }
        .details strong {
            display: inline-block;
            width: 200px;
            color: #333;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #777;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .signature p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reçu de Paiement Étudiant</h1>
            <!-- <p>Date : {{ $date }}</p>
            <p>Heure : {{ $heure }}</p> -->
        </div>
        <div class="details">
            <p><strong>Nom et Prénom :</strong> {{ $nom_prenom }}</p>
            <p><strong>Téléphone :</strong> {{ $Telephone }}</p>
            <p><strong>Formation :</strong> {{ $formation }}</p>
            <p><strong>Date de Début :</strong> {{ $date_debut }}</p>
            <p><strong>Date de Fin :</strong> {{ $date_fin }}</p>
            <p><strong>Mode de Paiement :</strong> {{ $Mode_peiment }}</p>
            <p><strong>Montant Payé :</strong> {{ $montant_paye }} MRU</p>
            <p><strong>Reste à Payer :</strong> {{ $reste_a_payer }} MRU</p>
            <p><strong>Date de Paiement :</strong> {{ $date_paiement }}</p>
        </div>
        <div class="footer">
            <p><strong>Par :</strong> {{ $par }}</p>
            <p>Date : {{ $date }}         {{ $heure }}</p>
            <!-- <p>Heure : {{ $heure }}</p> -->
            <p><strong>Signature :</strong> {{ $signature }}</p>
        </div>
    </div>
</body>
</html>
