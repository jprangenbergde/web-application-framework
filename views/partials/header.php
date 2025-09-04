<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>EcoRide</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            overflow: hidden;
        }

        .navigation {
            padding: 20px;
            background-color: #37632d;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navigation a {
            color: white;
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .navigation a:hover {
            color: #ffdd57;
        }

        header {
            background-color: #37632d;
            color: white;
            padding: 20px;
            font-size: 1.5rem;
        }

        #content {
            padding: 20px;
        }

        footer {
            background-color: #37632d;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 0.9rem;
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }

        .form-container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 40px auto;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
            font-size: 1.5rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        textarea {
            min-height: 150px;
        }

        input[type="text"],
        input[type="datetime-local"] {
            width: 49%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
            justify-content: space-between;
        }

        input[type="submit"] {
            width: 100%;
            padding: 0.8rem;
            background-color: #37632d;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #5b9e4d;
            transform: scale(1.05);
        }

        .errorMessage {
            color: red;
        }

        .successMessage {
            color: green;
        }

        .warningMessage {
            color: orange;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="navigation">
        <header>
            <a href="/">EcoRide</a>
        </header>
        <?php
        if ($isAuthenticated()) : ?>
            <nav>
                <a href="/logout">Abmelden</a>
            </nav>
        <?php
        else: ?>
            <nav>
                <a href="/login">Anmelden</a>
                <a href="/register">Registrieren</a>
            </nav>
        <?php
        endif; ?>
    </div>
    <div id="content">
        <?= $generateView('partials/flashMessages') ?>
