<?= $generateView('partials/header') ?>

<div>
    <div class="form-container">
        <form method="POST" action="/login">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken() ?>">
            <h2>Anmelden</h2>
            <input type="email" name="email" placeholder="Email Adresse" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <input type="submit" name="submit" value="Anmelden" required>
        </form>
    </div>
</div>

<?= $generateView('partials/footer') ?>
