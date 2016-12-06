<?php
/**
 * @var \Parishop\Template\View $this
 */
$this->layout('app:html'); ?>
    <div>
        <?php foreach($this->messages() as $message) { ?>
            <div class="alert alert-<?= $message->level(); ?>">
                <?= $message->message(); ?>
            </div>
        <?php } ?>
    </div>
<?php $this->childContent();
