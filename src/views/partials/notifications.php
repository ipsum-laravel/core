<div class="messages <?= $type ?>">
    <button type="button" class="closeMessage" data-dismiss="alert">&times;</button>
    <p><?= $label ?> :</p>
    <ul>
        <?php foreach ($messages as $message) : ?>
        <li><?= $message ?></li>
        <?php endforeach ?>
    </ul>
</div>