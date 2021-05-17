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

Currently, this package isn't packaged with packagist (https://packagist.org/). You must add a private repository to your composer.json file in order to use this package. You can add private repository in your composer.json file by adding this section :

```json
{
"repositories": [
    {
      "name": "orykami/laravel-ovh-sms",
      "type": "git",
      "url": "https://github.com/orykami/laravel-ovh-sms"
    }
  ]
}
```

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
    'app_key' => env('OVH_APP_KEY', 'YOUR_APP_KEY_HERE'),
    'app_secret' => env('OVH_APP_SECRET', 'YOUR_APP_SECRET_HERE'),
    'endpoint' => env('OVH_ENDPOINT', 'OVH_ENDPOINT_HERE'),
    'consumer_key' => env('OVH_CONSUMER_KEY', 'YOUR_CONSUMER_KEY_HERE'),
    'sms_account' => env('OVH_SMS_ACCOUNT', 'sms-xxxxxxx-x'),
    'sms_default_sender' => env('OVH_SMS_DEFAULT_SENDER', 'SENDER_NAME')),
  ],
];
```

## Using with Laravel Notifications

This package can be used as a driver for Laravel Notifications (Laravel >= 7.X).  

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
Be aware that Channel method send will return OVH credits consumed (if needed for quotas/metrics).

## Licence
MIT
