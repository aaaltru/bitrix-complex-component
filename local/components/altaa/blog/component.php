<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Highloadblock\HighloadBlockTable as HL;
//подключаем модуль highloadblock
CModule::IncludeModule('highloadblock');

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

// поумолчанию будем использовать только его
if ($arParams['SEF_MODE'] == 'Y') {

    /* Если включен режим поддержки ЧПУ */

    // В этой переменной будем накапливать значения истинных переменных
    $arVariables = array();

    // !!если точное значение в массиве с шаблонами строк отсутствует (например не хватает одного параметра), то приходит пустая строка
    $componentPage = CComponentEngine::ParseComponentPath(
        $arParams['SEF_FOLDER'],
        $arParams['SEF_URL_TEMPLATES'],
        $arVariables // переменная передается по ссылке
    );

    $notFound = false;

    /**
     * Метод служит для поддержки псевдонимов переменных в комплексных компонентах. Восстанавливает
     * истинные переменные из $_REQUEST на основании их псевдонимов из $arParams['VARIABLE_ALIASES'].
     */
    CComponentEngine::InitComponentVariables(
        $componentPage,
        null,
        array(),
        $arVariables
    );


    if ($componentPage == 'section' || $componentPage == 'element'){

        if (isset($arVariables['SECTION']) || isset($arVariables['ELEMENT'])):

            if(isset($arVariables['SECTION'])) $CODE = $arVariables['SECTION'];
            if(isset($arVariables['ELEMENT'])) $CODE = $arVariables['ELEMENT'];

            $HLBID = \Bitrix\Main\Config\Option::get("altaa.main", "BLOG_ID");

            $hlblock = HL::getById($HLBID)->fetch();

            $entity = HL::compileEntity($hlblock);

            $HL_BLOCK_OBJ = $entity->getDataClass();

            $rsData = $HL_BLOCK_OBJ::getList(array(
                "select" => ["ID", "UF_CODE"],
                "order" => ["ID" => "ASC"],
                "filter" => ["=UF_CODE"=>$CODE]
            ));

            if(!$rsData->getSelectedRowsCount()):

                $notFound = true;

            endif;

        else:

            $notFound = true;

            endif;
    }

    /**
     * Редиректим в меню если данные указаны не верно
     */
    if (empty($componentPage) || $notFound) {
        \Bitrix\Iblock\Component\Tools::process404(
            trim($arParams['MESSAGE_404']) ?: 'Элемент или раздел инфоблока не найден',
            true,
            $arParams['SET_STATUS_404'] === 'Y',
            $arParams['SHOW_404'] === 'Y',
            $arParams['FILE_404']
        );
        return;
    }

    $arResult['SECTION'] = $arVariables["SECTION"];
    $arResult['ELEMENT'] = $arVariables["ELEMENT"];

}

$this->IncludeComponentTemplate($componentPage);