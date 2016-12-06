<?php
/**
 * @var \Parishop\Template\View $this
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->title(); ?>
    <?= implode(PHP_EOL, $this->getMeta()); ?>
    <?= implode(PHP_EOL, $this->getLinks()); ?>
</head>
<body>
<?= $this->render('app:header'); ?>
<?php $this->childContent(); ?>
<?= $this->render('app:footer'); ?>
<?= implode(PHP_EOL, $this->getScripts()); ?>
</body>
</html>