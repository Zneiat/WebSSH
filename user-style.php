<?php
require_once ('core/core.php');
header('Content-type: text/css');
use app\UserTmpData;
$UserTmpData = new UserTmpData();

$RgbPreg = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
$BgColor = @addslashes(htmlspecialchars(trim($UserTmpData->Data('BgColor'))));
$FontColor  = @addslashes(htmlspecialchars(trim($UserTmpData->Data('FontColor'))));
$FontSize = @addslashes(htmlspecialchars(trim($UserTmpData->Data('FontSize'))));
switch ($FontSize){
    case 'small':
        $FontSize = '14px';
        break;
    case 'middle':
        $FontSize = '16px';
        break;
    case 'big':
        $FontSize = '18px';
        break;
    default:
        $FontSize = '16px';
}
?>
html, body {
    <?= preg_match($RgbPreg,$BgColor)?'background: '.$BgColor.';':null ?>

    <?= preg_match($RgbPreg,$FontColor)?'color: '.$FontColor.';':null ?>

    <?= 'font-size: '.$FontSize.';' ?>
    
}

@-webkit-keyframes terminal-blink {
    0%,100% {<?= preg_match($RgbPreg,$FontColor)?'color: '.$FontColor.';':null ?><?= preg_match($RgbPreg,$BgColor)?'background-color: '.$BgColor.';':null ?>}
    50% {<?= preg_match($RgbPreg,$FontColor)?'background-color: '.$FontColor.';':null ?><?= preg_match($RgbPreg,$BgColor)?'color: '.$BgColor.';':null ?>}
}

@-ms-keyframes terminal-blink {
    0%,100% {<?= preg_match($RgbPreg,$FontColor)?'color: '.$FontColor.';':null ?><?= preg_match($RgbPreg,$BgColor)?'background-color: '.$BgColor.';':null ?>}
    50% {<?= preg_match($RgbPreg,$FontColor)?'background-color: '.$FontColor.';':null ?><?= preg_match($RgbPreg,$BgColor)?'color: '.$BgColor.';':null ?>}
}

@-moz-keyframes terminal-blink {
    0%,100% {<?= preg_match($RgbPreg,$FontColor)?'color: '.$FontColor.';':null ?><?= preg_match($RgbPreg,$BgColor)?'background-color: '.$BgColor.';':null ?>}
    50% {<?= preg_match($RgbPreg,$FontColor)?'background-color: '.$FontColor.';':null ?><?= preg_match($RgbPreg,$BgColor)?'color: '.$BgColor.';':null ?>}
}

@keyframes terminal-blink {
    0%,100% {<?= preg_match($RgbPreg,$FontColor)?'color: '.$FontColor.';':null ?><?= preg_match($RgbPreg,$BgColor)?'background-color: '.$BgColor.';':null ?>}
    50% {<?= preg_match($RgbPreg,$FontColor)?'background-color: '.$FontColor.';':null ?><?= preg_match($RgbPreg,$BgColor)?'color: '.$BgColor.';':null ?>}
}
