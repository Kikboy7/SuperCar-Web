<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle ?? 'Administration'; ?> | SuperCar</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            color: #1f2937;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: #1e3a8a;
            color: white;
            padding: 25px 18px;
        }

        .sidebar h2 {
            margin: 0 0 25px;
            font-size: 24px;
        }

        .sidebar a {
            display: block;
            color: #dbeafe;
            text-decoration: none;
            padding: 11px 12px;
            margin-bottom: 8px;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: #2563eb;
            color: white;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .topbar h1 {
            margin: 0;
            color: #111827;
        }

        .card {
            background: white;
            border: 1px solid #dbe3ee;
            border-radius: 8px;
            padding: 22px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
            margin-bottom: 22px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #eff6ff;
            color: #1e3a8a;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 12px;
            font-family: Arial, sans-serif;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border-radius: 5px;
            border: none;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .btn-danger {
            background: #dc2626;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-light {
            background: #e5e7eb;
            color: #111827;
        }

        .message {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 18px;
        }

        .status {
            font-weight: bold;
        }

        @media (max-width: 900px) {
            .admin-layout {
                flex-direction: column;
            }

            .sidebar {
                width: auto;
            }

            .grid,
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="admin-layout">
    <aside class="sidebar">
        <h2>SuperCar Admin</h2>
        <a href="dashboard.php">Tableau de bord</a>
        <a href="voitures.php">Voitures</a>
        <a href="essais.php">Demandes d'essai</a>
        <a href="messages.php">Messages</a>
        <a href="services.php">Services</a>
        <a href="../index.php">Voir le site</a>
        <a href="logout.php">Deconnexion</a>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1><?php echo $pageTitle ?? 'Administration'; ?></h1>
        </div>
