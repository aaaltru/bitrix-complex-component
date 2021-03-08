<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<h1 class="h3">Просмотр записей раздела</h1>
<p>Тут находятся все матриалы по выбанному разделу</p>


<?foreach ($arResult["ELEMENTS"] as $key => $value):?>

    <div class="card mb-3">
        <div class="card-body">

            <div class="row">

                <div class="col-12">

                    <p class="card-text"><small class="text-muted"><?=$value["UF_DATE_CREATE"]?></small></p>

                    <h5 class="card-title"><?=$value["UF_NAME"]?></h5>
                    <p class="card-text"><?=$value["UF_PREVIEW"]?></p>

                </div>

                <div class="col-6 pt-3">
                    <span class="badge bg-secondary text-white">#<?=$value["BLOG_RT_POST_UF_NAME"]?></span>
                    <?
                    switch ($value["BLOG_RT_STATUS_XML_ID"]) {
                        case 'new':     $class = 'bg-danger text-white'; break;
                        case 'edit':    $class = 'bg-warning text-dark'; break;
                        case 'closed':  $class = 'bg-secondary text-white'; break;
                        case 'public':  $class = 'bg-secondary text-white'; break;
                        case 'hide':    $class = 'bg-dark'; break;
                        default:        $class = 'bg-primary';
                    }?>

                    <span class="badge <?=$class?>">#<?=$value["BLOG_RT_STATUS_VALUE"]?></span>

                </div>


                <div class="col-6">

                    <a href="<?=$arResult["MAIN_SECTION"]?><?=$arResult["SECTION_CODE"]?>/<?=$value["UF_CODE"]?>/" class="btn btn-danger float-right">Просмотреть</a>

                </div>
            </div>
        </div>
    </div>

<?endforeach;?>
<!--
<pre><?/*print_r($arResult)*/?></pre>-->
