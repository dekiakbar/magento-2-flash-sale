## Main Functionalities
Magento 2 Flash sale module

## Installation
### Zip file
  - Unzip the zip file in `app/code/Deki`
  - Enable the module by running `php bin/magento module:enable Deki_FlashSale`
  - Apply database updates by running `php bin/magento setup:upgrade`
  - Compile the module by running `php bin/magento setup:di:compile`
  - Compile static assets by running `php bin/magento setup:static-content:deploy`
  - Flush the cache by running `php bin/magento cache:flush`

### Composer
  - Install the module composer by running `composer require deki/magento-2-flash-sale`
  - Enable the module by running `php bin/magento module:enable Deki_FlashSale`
  - Apply database updates by running `php bin/magento setup:upgrade`
  - Compile the module by running `php bin/magento setup:di:compile`
  - Compile static assets by running `php bin/magento setup:static-content:deploy`
  - Flush the cache by running `php bin/magento cache:flush`

## Configuration
  - Admin > Deki > Flash Sale > Flash Sale Event : Add/edit flash sale event and product
  - Admin > Deki > Flash Sale > Configuration : Flash sale module configuration