<?php
require "Iharos/src/Kernel/App.php";
use Iharos\Label\Label;

$app = new Iharos\Kernel\App();
$app::bind('Iharos\Label\LabelModule');

Label::label("Uzenet");

?>