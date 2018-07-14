A custom options module for [sayitwithagift.com](https://sayitwithagift.com).

## How to install
```
composer require sayitwithagift/options:*
bin/magento setup:upgrade
cachetool opcache:reset && cachetool stat:clear && bin/magento cache:clean
rm -rf pub/static/* && bin/magento setup:static-content:deploy en_US en_GB
rm -rf var/di var/generation generated/code && bin/magento setup:di:compile
cachetool opcache:reset && cachetool stat:clear && bin/magento cache:clean
```
If you have problems with these commands, please check the [detailed instruction](https://mage2.pro/t/263).