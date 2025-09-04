<?= $generateView('partials/header') ?>

<div>
    <div class="form-container">
        <form method="POST" action="/register">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken() ?>">
            <h2>Registrierung</h2>
            <input type="text" name="firstName" placeholder="Vorname" required>
            <input type="text" name="lastName" placeholder="Nachname" required>
            <input type="email" name="email" placeholder="E-Mail-Adresse" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <input type="submit" name="submit" value="Anmelden" required>
        </form>
    </div>
</div>

<?= $generateView('partials/footer') ?>
