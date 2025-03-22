# Magento 2 Simbeez_PurchasedOn

## Overview
Simbeez_PurchasedOn is a Magento 2 extension that provides functionality to admin user to view On Order, Refunded, Shipped, Completed Order Qty and Closed Order Qty at product page.

## Features
- admin user to view On Order, Refunded, Shipped, Completed Order Qty and Closed Order Qty at product page

---

## Folder Structure in GitHub

Your GitHub repository should follow this structure:

```
Magento2-Simbeez-PurchasedOn/   <-- This is your GitHub repo
│── Simbeez/
│   ├── PurchasedOn/
│   │   ├── etc/
│   │   │   ├── module.xml
│   │   ├── Model/
│   │   ├── Controller/
│   │   ├── Helper/
│   │   ├── view/
│   │   ├── registration.php
│   │   ├── composer.json
│   │   ├── README.md
```

After cloning the repository, move the contents inside `app/code/` in your Magento 2 installation:

```bash
cd /path/to/magento2/app/code/
git clone https://github.com/yourusername/Magento2-Simbeez-PurchasedOn.git Simbeez/PurchasedOn
```

This will result in the following Magento 2 directory structure:

```
app/
├── code/
│   ├── Simbeez/
│   │   ├── PurchasedOn/
│   │   │   ├── etc/
│   │   │   ├── Model/
│   │   │   ├── Controller/
│   │   │   ├── Helper/
│   │   │   ├── view/
│   │   │   ├── registration.php
│   │   │   ├── composer.json
│   │   │   ├── README.md
```

---

## Installation

### 1. Manual Installation (Recommended for Development)

1. Navigate to your Magento root directory:

   ```bash
   cd /path/to/magento2/
   ```

2. Copy the module files to `app/code/Simbeez/PurchasedOn/`.

3. Run the following Magento CLI commands:

   ```bash
   php bin/magento module:enable Simbeez_PurchasedOn
   php bin/magento setup:upgrade
   php bin/magento cache:flush
   php bin/magento setup:di:compile
   php bin/magento setup:static-content:deploy -f
   ```
---

## Usage
- [Explain how the user can utilize the module]
- Example commands (if applicable):

  ```bash
  php bin/magento simbeez:purchasedon
  ```

---

## Uninstallation

If you want to remove the module, run:

```bash
php bin/magento module:disable Simbeez_PurchasedOn
php bin/magento setup:upgrade
php bin/magento cache:flush
rm -rf app/code/Simbeez/PurchasedOn/
composer remove simbeez/purchased-on
```

---

## Changelog
### Version 1.0.0
- Initial release

---

## Support
For any issues, please open a GitHub issue or contact rathodsunny005@gmail.com.

---

## License
This module is licensed under a **proprietary license**. Redistribution, modification, or resale of this extension without permission from Simbeez is strictly prohibited. Unauthorized use, copying, or sharing of this extension will result in legal action.

