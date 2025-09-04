<?php
/** @var \Framework\Views\ViewHelpers\FlashMessages $flashMessages */
?>

<?php foreach ($flashMessages() ?? [] as $message): ?>
    <div class="<?= $message->type->value ?>Message"><?= $message->text ?></div>
<?php endforeach; ?>
