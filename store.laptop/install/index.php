<?php

IncludeModuleLangFile(__FILE__);

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Store\Laptop\InstallHelper\DBManager;
use Store\Laptop\InstallHelper\MockData;

class store_laptop extends CModule
{
    public $MODULE_ID = 'store.laptop';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
	private $documentRoot;
	private array $tablesList = [        
        'Store\Laptop\DBProvider\ORM\StoreManufacturerTable',
        'Store\Laptop\DBProvider\ORM\StoreModelTable',
        'Store\Laptop\DBProvider\ORM\StoreLaptopTable',
        'Store\Laptop\DBProvider\ORM\StorePropertyTable',
        'Store\Laptop\DBProvider\ORM\StoreLaptopPropertyTable',
    ];
    
    public function __construct()
    {
        $this->MODULE_NAME = Loc::getMessage('STORE_LAPTOP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('STORE_LAPTOP_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('STORE_LAPTOP_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('STORE_LAPTOP_PARTNER_URI');

        include __DIR__ . '/version.php';

        /** @var array $arModuleVersion */
        $this->MODULE_VERSION = $arModuleVersion['MODULE_VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['MODULE_VERSION_DATE'];
		$this->documentRoot = Application::getDocumentRoot();
    }

    function DoInstall()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        global $APPLICATION, $step;
        $step = intval($step);
        if ($step < 1) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('STORE_LAPTOP_INSTALL_STEP_1_TITLE'),
                $this->documentRoot.'/local/modules/'.$this->MODULE_ID.'/install/step1.php'
            );
        } elseif ($step === 1) {     
            ModuleManager::registerModule($this->MODULE_ID);                  
            Loader::includeModule($this->MODULE_ID);
            $this->InstallFiles();
            if ($request->getPost('default_db')) {
                $this->UnInstallDB();
                $this->InstallDB();
            }      
        }
    }

    function DoUninstall()
    {
        Loader::includeModule($this->MODULE_ID);
        $request = Application::getInstance()->getContext()->getRequest();
        global $APPLICATION, $step;
        $step = intval($step);
        if ($step < 1) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('STORE_LAPTOP_UNINSTALL_STEP_1_TITLE'),
                $this->documentRoot.'/local/modules/'.$this->MODULE_ID.'/install/unstep1.php'
            );
        } elseif ($step === 1) {
            if ($request->getPost('clear_db')) {
                $this->UnInstallDB();
            }      
        }
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    function InstallFiles()
    {
        if (
			is_dir($this->documentRoot . '/local/components/store/')
		) {
            return;
        }
        CopyDirFiles(
            $this->documentRoot . '/local/modules/' . $this->MODULE_ID . '/install/components/',
            $this->documentRoot . '/local/components',
            false,
            true
        );
    }

    function InstallDB()
    {
        DBManager::createTables($this->tablesList);
        MockData::generateDefault();
    }

    function UnInstallDB()
    {
        DBManager::dropTables($this->tablesList);
    }
}
