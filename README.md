# Coin-Notifier
Notifies you a Shitcoin ATM is empty or gets refilled.

## Requirements
Composer + PHP 7.3 + Extensions (curl / mbstring and whatever)

## Install
First you install all dependencies  
```bash
composer install
```  
Then you open the created config.php and add your pushover app token.

## Usage
It's simple. Just execute the check.php using php:
````bash
php -f check.php
````