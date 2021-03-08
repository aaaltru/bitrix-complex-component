<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Highloadblock\HighloadBlockTable as HL;

CModule::IncludeModule('highloadblock');
CModule::includeModule('altaa.main');
/**
 * Class ALTBlogSection
 */
class ALTBlogSection extends CBitrixComponent implements Controllerable
{
    private $HLBID;
    private $sectionCode;
    private $sectionId;
    private $elements;

    // Обязательный метод
    public function configureActions()
    {
        return [
            'sendMessage' => [
                'prefilters' => [
                    new ActionFilter\Authentication
                ],
            ],
        ];
    }

    /**
     *  Установка ID блока
     */
    private function setHLBID()
    {
        $this->HLBID = \Bitrix\Main\Config\Option::get("altaa.main", "BLOG_ID");
    }

    /**
     * Получить ID блока
     * @return mixed
     */
    private function getHLBID()
    {
        return $this->HLBID;
    }

    /**
     * Получение объекта HL-block
     * @return \Bitrix\Main\ORM\Data\DataManager
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function getObjHlblock(){

        $HLBID = $this->getHLBID();

        $hlblock = HL::getById($HLBID)->fetch();

        $entity = HL::compileEntity($hlblock);

        $result = $entity->getDataClass();

        return $result;
    }

    /**
     * Установка раздела
     */
    private function setSectionCode($SECTION)
    {
        $this->sectionCode = $SECTION;
    }

    /**
     * Получить название раздела
     */
    private function getSectionCode()
    {
        return $this->sectionCode;
    }

    /**
     * Получить ID раздела
     */
    private function setIdSection()
    {
        // объект HLB
        $HL_BLOCK_OBJ = $this->getObjHlblock();

        $SELECT = ["ID"];

        $rsData = $HL_BLOCK_OBJ::getList(array(
            "select" => $SELECT,
            "order" => array("ID" => "ASC"),
            "filter" => array(
                "=UF_CODE"=>$this->getSectionCode()
            )  // Задаем параметры фильтра выборки
        ));

        if($arData = $rsData->Fetch()){

            // обновление по id записи
            $result = $arData["ID"];

        }

        $this->sectionId = $result;
    }

    /**
     * Получить ID раздела
     */
    private function getIdSection()
    {
        return $this->sectionId;
    }

    /**
     * Установка элементов
     */
    private function setElements()
    {
        // объект HLB
        $HL_BLOCK_OBJ = $this->getObjHlblock();

        $SELECT = [
            "ID",
            "UF_NAME",
            "UF_CODE",
            "UF_SECTION",
            "UF_POST",
            //"UF_ROOT",
            "UF_BASE_POST",
            "UF_FILE",
            "UF_DATE_CREATE",
            "UF_STATUS",
            "UF_PREVIEW",
            "UF_COMPLICATION",
            "RT_STATUS.USER_FIELD_ID",
            "RT_STATUS.VALUE",
            "RT_STATUS.XML_ID",
            "RT_POST.ID",
            "RT_POST.UF_NAME",
            "RT_POST.UF_SECTION",
            "RT_POST.UF_POST",
            "RT_POST.UF_ROOT",
            "RT_POST.UF_BASE_POST",
            "RT_POST.UF_FILE",
            //"RT_POST.UF_DATE_CREATE",
            "RT_POST.UF_STATUS",
            "RT_POST.UF_COMPLICATION",
        ];

        $rsData = $HL_BLOCK_OBJ::getList(array(
            "select" => $SELECT,
            "runtime" => array(
                'RT_STATUS' => [
                    'data_type' => \Altaa\Main\UserFildEnumTable::class,
                    'reference' => ['=this.UF_STATUS' => 'ref.ID'],
                    'join_type' => 'left'
                ],
                'RT_POST' => [
                    'data_type' => $this->getObjHlblock(),
                    'reference' => ['=this.UF_BASE_POST' => 'ref.ID'],
                    'join_type' => 'left'
                ],
            ),
            "order" => array("ID" => "ASC"),
            "filter" => array(
                "=UF_BASE_POST"=>$this->getIdSection()
            )  // Задаем параметры фильтра выборки
        ));

        while($arData = $rsData->Fetch()){

            // обновление по id записи
            $result[] = $arData;

        }

        $this->elements = $result;
    }

    /**
     * Получение разделов
     */
    private function getElements()
    {
        return $this->elements;
    }

    /**
     *
     */
    public function executeComponent()
    {
        // установка id
        $this->setHLBID();
        // установка раздела
        $this->setSectionCode($this->arParams["SECTION"]);
        // установка id раздела
        $this->setIdSection();
        // установка разделов
        $this->setElements();
        // получение основого раздела
        $this->arResult["MAIN_SECTION"] = $this->arParams["MAIN_SECTION"];
        // получение названия раздела
        //$this->arResult["SECTION_NAME"] = $this->();
        // получение кода раздела
        $this->arResult["SECTION_CODE"] = $this->getSectionCode();
        // получение id раздела
        $this->arResult["SECTION_ID"] = $this->getIdSection();
        // получение элементов
        $this->arResult["ELEMENTS"] = $this->getElements();
        // вход в темплейт компонента
        $this->includeComponentTemplate();
    }
}