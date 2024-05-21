<?php

use Bitrix\Main\Loader;

Loader::registerAutoloadClasses(
    'store.laptop',
    [
        'Store\\Laptop\\DBProvider\\ORM\\FilterSettings' => 'lib/dbprovider/orm/filtersettings.php',
        'Store\\Laptop\\DBProvider\\ORM\\LaptopPage' => 'lib/dbprovider/orm/laptoppage.php',
        'Store\\Laptop\\DBProvider\\ORM\\LaptopList' => 'lib/dbprovider/orm/laptoplist.php',
        'Store\\Laptop\\DBProvider\\ORM\\StoreProperty' => 'lib/dbprovider/orm/storeproperty.php',
        'Store\\Laptop\\DBProvider\\ORM\\StorePropertyTable' => 'lib/dbprovider/orm/storepropertytable.php',
        'Store\\Laptop\\DBProvider\\ORM\\StoreModel' => 'lib/dbprovider/orm/storemodel.php',
        'Store\\Laptop\\DBProvider\\ORM\\StoreModelTable' => 'lib/dbprovider/orm/storemodeltable.php',
        'Store\\Laptop\\DBProvider\\ORM\\StoreLaptop' => 'lib/dbprovider/orm/storelaptop.php',
        'Store\\Laptop\\DBProvider\\ORM\\StoreLaptopTable' => 'lib/dbprovider/orm/storelaptoptable.php',
        'Store\\Laptop\\DBProvider\\ORM\\StoreManufacturer' => 'lib/dbprovider/orm/storemanufacturer.php',
        'Store\\Laptop\\DBProvider\\ORM\\StoreManufacturerTable' => 'lib/dbprovider/orm/storemanufacturertable.php',
        'Store\\Laptop\\DBProvider\\ORM\\StoreLaptopPropertyTable' => 'lib/dbprovider/orm/storelaptoppropertytable.php',
        'Store\\Laptop\\Components\\Settings' => 'lib/components/settings/parentway.php',
        'Store\\Laptop\\URL\\SearchEngineFriendly' => 'lib/url/searchenginefriendly.php',
        'Store\\Laptop\\InstallHelper\\DBManager' => 'lib/installhelper/dbmanager.php',
        'Store\\Laptop\\Helper\\LaptopsList' => 'lib/helper/laptopslist.php',
    ]
);
