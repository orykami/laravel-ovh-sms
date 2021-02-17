# Laravel OVH SMS

This is an unofficial OVH SMS integration of the [ovh/php-ovh](https://github.com/ovh/php-ovh) library for Laravel 7+.

- Original [PHP OVH library](https://github.com/ovh/php-ovh/blob/master/README.md)
- Based on [Nexmo notification channel](https://github.com/laravel/nexmo-notification-channel)

## Summary

- [Installation](#installation)
- [Configuration](#configuration)
- [Using with Laravel Notifications](#using-with-laravel-notifications)
    - [Example notification](#example-notification)
- [Getting credentials](#getting-credentials)
- [Support](#support)
- [Licence](#licence)

## Installation

Require this package with composer:  
```bash
composer require orykami/laravel-ovh-sms
```

After updating composer, add the ServiceProvider to the **providers** array in config/app.php:  
```php
Illuminate\Notifications\OvhSmsChannelServiceProvider::class,
```

## Configuration

This package require some configuration in `config/services.php` 

```php
return [
  // Add configuration to third party services
  'ovh' => [
    'app_key' => 'YOUR_APP_KEY_HERE',
    'app_secret' => 'YOUR_APP_SECRET_HERE',
    'endpoint' => 'OVH_ENDPOINT_HERE',
    'consumer_key' => 'YOUR_CONSUMER_KEY_HERE',
    'sms_account' => 'YOUR_SMS_ACCOUNT_HERE (sms-xxxxxxx-x)',
  ],
];
```

## Using with Laravel Notifications

This package can be used as a driver for Laravel Notifications (Laravel >= 5.3).  

### Example notification

Here's a simple notification example.  

```php
namespace App\Notifications;

use Illuminate\Notifications\Channels\OvhSmsChannel;
use Illuminate\Notifications\Messages\OvhSmsMessage;
use Illuminate\Notifications\Notification;

class ExampleNotification extends Notification
{
    /**
     * Notification via OvhSmsChannel.
     */
    public function via($notifiable)
    {
        return [OvhSmsChannel::class];
    }

    /**
     * Your notification must implements "toOvh()"
     */
    public function toOvh($notifiable)
    {
    	return (new OvhSmsMessage('A new invoice was paid! Amount: $9.00'));
    }
}
```

Also, your Notifiable model must implements **routeNotificationForOvhSms()**.  

```php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    /**
     * Returns the user's phone number.
     */
    public function routeNotificationForOvhSms()
    {
        return $this->phone; // Ex: +33611223344
    }
}
```

You're all set to use the new Laravel Notifications system ! :-)

## Licence

MIT
