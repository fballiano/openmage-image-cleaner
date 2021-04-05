# The best image cleaner for Magento 1.x and OpenMage

Why the need for yet another image cleaner?
---------
Simply because I couldn't find any with all the features I needed (category images, custom attributes, modern PHP versions).

Features
---------
- Identify and remove orphan **category images** (reading data from the default "image" attribute and **all custom attributes** of type "image").
- Identify and remove orphan **product images** (reading data from media_gallery)
- Check before delete: you can review the identified images before removing them
- Compatible with **Magento 1.9 and OpenMage 20.0 on PHP 7.2, 7.3, 7.4**

How to use it
-------------
- Install via composer (`composer require fballiano/magento1-image-cleaner`), 
  modman (`modman clone https://github.com/fballiano/magento-full-catalog-translate`)
  or any other way you like
- Navigate to backend's "System -> Tools -> Image Cleaner" section
- Click "Sync category images" and/or "Sync product images"
- The grid will show all the images that are detected as orphans
- Delete them (check the backup section!) one by one or with a mass action

Backup!!!
---------
Backup your database and files before launching the cleaning process!!!
This module is provided "as is" and I'll not be responsible for any data damage.

Support
-------
If you have any issues with this extension, open an issue on GitHub.

Contribution
------------
Any contributions are highly appreciated. The best way to contribute code is to open a
[pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Fabrizio Balliano  
[http://fabrizioballiano.com](http://fabrizioballiano.com)  
[@fballiano](https://twitter.com/fballiano)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) Fabrizio Balliano
