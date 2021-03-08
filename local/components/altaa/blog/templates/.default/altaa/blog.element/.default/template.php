<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?use Bitrix\Main\UI\Extension;?>

<?$this->addExternalCss("/local/include/js/highlight/styles/darcula.css");?>
<?$this->addExternalJS("/local/include/js/highlight/highlight.pack.js");?>
<?$this->addExternalJS("/local/include/js/highlight/highlightjs-line-numbers.min.js");?>
<?Extension::load('ui.vue');?>

<script>hljs.initHighlightingOnLoad();hljs.initLineNumbersOnLoad();</script>
<h1 class="h3"><?=$arResult['ELEMENT']["UF_NAME"]?></h1>

<div class="row">
    <div class="col-12 bg-white p-3">
        <?=$arResult['ELEMENT']["UF_CONTENT"]?>
    </div>
</div>


<!--<pre><?/*print_r($arResult)*/?></pre>-->
