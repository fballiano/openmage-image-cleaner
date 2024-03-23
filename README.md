# The best image cleaner for OpenMage (and Magento 1.9)

<table><tr><td align=center>
<strong>If you find my work valuable, please consider sponsoring</strong><br />
<a href="https://github.com/sponsors/fballiano" target=_blank title="Sponsor me on GitHub"><img src="https://img.shields.io/badge/sponsor-30363D?style=for-the-badge&logo=GitHub-Sponsors&logoColor=#white" alt="Sponsor me on GitHub" /></a>
<a href="https://www.buymeacoffee.com/fballiano" target=_blank title="Buy me a coffee"><img src="https://img.shields.io/badge/Buy_Me_A_Coffee-FFDD00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black" alt="Buy me a coffee" /></a>
<a href="https://www.paypal.com/paypalme/fabrizioballiano" target=_blank title="Donate via PayPal"><img src="https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="Donate via PayPal" /></a>
</td></tr></table>

Features
---------
- Identify and remove orphan **category images** (reading data from the default "image" attribute and **all custom attributes** of type "image").
- Identify and remove orphan **product images** (reading data from media_gallery) and **product images cache**.
- Identify and remove orphan **WYSIWYG images and files** (reading used images/files from cms_block, cms_page, core_email_template tables and all /skin/frontend CSS files).
- Check before delete: you can review (and download) the identified images before removing them.
- Possibility to **blacklist folders and/or files** (with wildcard support) not to ever identify them as orphans.
- Possibility to **flush media/import, media/tmp, var/export, var/importexport**.
- Compatible with **Magento 1.9, OpenMage 19 and OpenMage 20 on PHP >= 7.4**.

How to use it
-------------
- Install via composer (`composer require fballiano/openmage-image-cleaner`), 
  modman (`modman clone https://github.com/fballiano/openmage-image-cleaner`)
  or any other way you like
- Navigate to backend's "System -> Tools -> Image Cleaner" section
- Click "Sync category images" and/or "Sync product images" or "Sync WYSIWYG"
- The grid will show all the images that are detected as orphans
- Delete them (check the backup section!) one by one or with a mass action

If you want to change the default thumbnail size (in the image cleaner admin grid) go to
"System -> Configuration -> Advanced -> Admin -> Image Cleaner".

If you want to blacklist folders or files go to
"System -> Configuration -> Advanced -> Admin -> Image Cleaner".

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
