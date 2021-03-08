<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>

<!--<pre>
    <?/*print_r($arResult)*/?>
    <?/*print_r($arParams)*/?>
</pre>
-->

<?
$APPLICATION->IncludeComponent(
    "altaa:blog.index", "",
    array(
        "MAIN_SECTION" => $arParams["SEF_FOLDER"]
    ),
    $component,
    array("HIDE_ICONS" => "Y")
);
?>