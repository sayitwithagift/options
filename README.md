A custom options module for [sayitwithagift.com](https://sayitwithagift.com) (Magento 2).

## Screenshots
### Frontend
![](https://mage2.pro/uploads/default/original/2X/e/ef27df4ec8b8547728e9b84095b6b58db5a9ab13.png)

### Backend
![](https://mage2.pro/uploads/default/original/2X/2/2bb218a881708b233ab6edee153b0cb65831a8e5.png)

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
