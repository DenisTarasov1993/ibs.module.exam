<?php

namespace Store\Laptop\URL;

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\HttpRequest;
use Bitrix\Iblock\Component\Tools;
use Store\Laptop\DBProvider\ORM\LaptopList;
use Store\Laptop\DBProvider\ORM\StoreLaptopTable;
use Store\Laptop\DBProvider\ORM\StoreModelTable;
use Store\Laptop\DBProvider\ORM\StoreModel;
use Store\Laptop\DBProvider\ORM\StoreManufacturerTable;

class SearchEngineFriendly
{
    private array $stringParameters = [];
    private array $urlVariables = [];
    private array $idsList = [];
    private array $examiner = [
        '#BRAND#' => StoreManufacturerTable::class,
        '#MODEL#' => StoreModelTable::class,
        '#NOTEBOOK#' => StoreLaptopTable::class,
    ];
    private array $availTemplates = [];
    
    private string $typePage;
    private HttpRequest $request;
    
    
    /**
     * Проанализировать урл, установить параметры
     */
    public function __construct(string $sefFolder = '', $availTemplates)
    {
        foreach ($availTemplates as $page => $template) {
            $this->availTemplates[$page] = ['alias' => $template];
        }
        $this->request = Context::getCurrent()
            ->getRequest()
        ;
        $this->setParameters($sefFolder);
        $this->typePage = $this->selectTypePage();
        $this->examVariables();
        if ($this->getModelId() && $this->getBrandId()) {
            $this->isBrandModelRelation();
        }
    }

    /**
     * Получить выбранный id ноутбука
     * @return int - id ноутбука, если бы определён
     */
    public function getDetailId(): int
    {
        return $this->getIdByKey('#NOTEBOOK#');
    }
    
    /**
     * Получить выбранный id модели
     * @return int - id модели, если бы определён
     */
    public function getModelId(): int
    {
        return $this->getIdByKey('#MODEL#');
    }
    
    /**
     * Получить выбранный id производителя
     * @return int - id производителя, если бы определён
     */
    public function getBrandId(): int
    {
        return $this->getIdByKey('#BRAND#');
    }
    
    /**
     * Получить выбранный id по макросу
     * @param string $key - фрагмент урла
     * @return int - id для указанного макроса, если был определён
     */
    private function getIdByKey(string $key = ''): int
    { 
        if (array_key_exists($key, $this->idsList)) {
            return $this->idsList[$key];
        }
        return 0;
    }

    public function getVariables(): array
    {
        return $this->urlVariables;
    }
    
    /**
     * вернуть определённый тип страницы
     * @return string - наименования типа
     */
    public function getTypePage(): string
    {
        return trim($this->selectTypePage());
    }
    
    /**
     * Проверим что выбранные параметры содержаться в таблицах,
     * в случае ошибки вызовем 404
     */
    private function examVariables(): void
    {
        foreach ($this->urlVariables as $rules) {
            foreach ($rules as $macro => $code) {
                if (0 == $this->idsList[$macro] = LaptopList::isCodeHere(trim($code), $this->examiner[$macro])) {
                    $this->call404();
                    break;
                }
            }
        }
    }
    
    /**
     * Разобъём доступные форматы урлов на фрагменты
     */
    private function setRulesTemplate(): void
    {
        foreach ($this->availTemplates as $typeKey => $parameters) {
            $rulesList = [];
            foreach (explode('/', $parameters['alias']) as $parameter) {
                if (!$parameter) {
                    continue;
                }
                $rulesList[] = $parameter;
            }
            $this->availTemplates[$typeKey]['rules'] = $rulesList;
        }
    }
    
    /**
     * Проверим зависимость выбранных Бренда и модели, 
     * в случае отсутствия вызовем 404
     */
    private function isBrandModelRelation(): void
    {
        if (
            StoreModel::getParentManufacturer(
                $this->getModelId()
            ) != $this->getBrandId()
        ){
            $this->call404();
        }
    }
    
    /**
     * является ли фрагмент урла макросом
     * @param string $parameter - фрагмент урла
     * @return bool - Макрос | "сильный" фрагмент
     */
    private function isMacro(string $parameter = ''): bool
    {
        return '#'.trim($parameter, '#').'#' == $parameter;
    }
    
    /**
     * Определим под какой из шаблонов подходит наш УРЛ
     * @return string - определённый тип страницы
     */
    private function selectTypePage(): string
    {
        $this->setRulesTemplate();
        $examList = [];
        foreach ($this->availTemplates as $typeKey => $parameters) {
            if (count($parameters['rules']) != count($this->stringParameters)) {
                continue;
            }
            if (count($this->stringParameters) == 0) {
                return $typeKey;
            }
            foreach ($parameters['rules'] as $keyParam => $parameter) {   
                if ($this->isMacro($parameter)) {
                    $examList[$typeKey]['variables'][] = [
                        $parameter => $this->stringParameters[$keyParam]
                    ];
                    continue;
                }
                /* Проверим есть ли в урле жёстко прописанные условия */
                if ($parameter == $this->stringParameters[$keyParam]) {
                    $examList[$typeKey]['hardParams'] = true;
                } else {
                    unset($examList[$typeKey]);
                    break;
                }
            }
        }
        if (empty($examList)) {          
            $this->call404();
            return '';
        }
        $examList = $this->dropWeakRules($examList);
        
        foreach ($examList as $typeKey => $possibleCondition) {
            $this->urlVariables = $possibleCondition['variables'];
            return $typeKey;
        }
    }
    
    /**
     * Если есть условия с "сильными" параметрами, уберём условия без них 
     * @param array $examList - Набор подходящих директорий
     * @return string - Директории без "слабых" условий
     */
    private function dropWeakRules(array $examList = []): array
    {
        $isHardRules = false;
        foreach ($examList as $possibleCondition) {
            if ($possibleCondition['hardParams']) {
                $isHardRules = true;
                break;
            }
        }
        if ($isHardRules) {
            foreach ($examList as $typeKey => $possibleCondition) {
                if ($possibleCondition['hardParams']) {
                    continue;
                }
                unset($examList[$typeKey]);
            }
        }
        return $examList;
    }
    
    /**
     * Разобъём урл на фрагменты, проверим правильность указанной рабочей директории
     * @param string $sefFolder - рабочая директория
     */
    private function setParameters(string $sefFolder = ''): void
    {
        if (
            mb_strpos(
                $this->request->getRequestedPageDirectory(), 
                $sefFolder
            ) !== 0
        ) {
            $this->call404();
        }
        $parameterString = mb_substr(
            $this->request->getRequestedPageDirectory(),
            mb_strlen($sefFolder)
        );
    
        foreach (explode('/', $parameterString) as $urlFragment) {
            if (!$urlFragment) {
                continue;
            }
            $this->stringParameters[] = $urlFragment;
        }
    }
 
    /**
     * Вызов 404 страницы
     */
    private function call404(): void
    {
        Tools::process404(
            '',
            true,
            true,
            true
        );
    }
    
    /**
     * Заменим макросы в шаблоне на актуальные коды
     * @param string $sefFolder - рабочая директория
     * @param string $template - Шаблон урла для формирования реального урла
     * @param array $codes - Коды элементов для подстановки
     * @param array $macros - Доступные к замене макросы
     * @return string - Готовый урл элемента
     */
    public static function makeNormalUrl(
        string $sefFolder = '', 
        string $template = '', 
        array $codes = [], 
        array $macros = []
    ): string {
        return preg_replace(
            '|([/]+)|s', 
            '/', 
            $sefFolder.str_replace(
                $macros, 
                $codes, 
                $template
            )
        );
    }
    
}
