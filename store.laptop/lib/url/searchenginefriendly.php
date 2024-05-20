<?php

namespace Store\Laptop\URL;

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\HttpRequest;
use Bitrix\Iblock\Component\Tools;
use Store\Laptop\DBProvider\ORM\StoreLaptop;
use Store\Laptop\DBProvider\ORM\StoreModel;
use Store\Laptop\DBProvider\ORM\StoreManufacturer;

class SearchEngineFriendly
{
    private array $stringParameters = [];
    private array $urlVariables = [];
    private array $idsList = [];
    private array $examiner = [
        '#BRAND#' => StoreManufacturer::class,
        '#MODEL#' => StoreModel::class,
        '#NOTEBOOK#' => StoreLaptop::class,
    ];
    private array $availTemplates = [];
    
    private string $typePage;
    private HttpRequest $request;
    
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

    public function getDetailId(): string
    {
        return $this->getCodeByKey('#NOTEBOOK#');
    }
    
    public function getModelId(): string
    {
        return $this->getCodeByKey('#MODEL#');
    }
    
    public function getBrandId(): string
    {
        return $this->getCodeByKey('#BRAND#');
    }
    
    private function getCodeByKey(string $key = ''): int
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
    
    public function getTypePage(): string
    {
        return trim($this->selectTypePage());
    }

    private function examVariables(): void
    {
        foreach ($this->urlVariables as $rules) {
            foreach ($rules as $macro => $code) {
                if (0 == $this->idsList[$macro] = $this->examiner[$macro]::isCodeHere(trim($code))) {
                    $this->call404();
                    break;
                }
            }
        }
    }
    
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
    
    private function isMacro(string $parameter = ''): bool
    {
        return '#'.trim($parameter, '#').'#' == $parameter;
    }
    
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
 
    private function call404(): void
    {
        Tools::process404(
            '',
            true,
            true,
            true
        );
    }
    
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
