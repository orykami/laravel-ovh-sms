<?php /** @noinspection PhpUnused */

namespace Illuminate\Notifications;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Ovh\Api as OvhApi;

/**
 * Class OvhSmsChannelServiceProvider
 * @package Illuminate\Notifications
 */
class OvhSmsChannelServiceProvider extends ServiceProvider
{
  /**
   * Register the service provider.
   * @return void
   * @throws BindingResolutionException
   */
  public function register()
  {
    // Bind OvhApi in Service Container.
    $this->app->singleton(OvhApi::class, function ($app) {
      /** @var Repository $config */
      $config = $app->make('config');
      return new OvhApi(
        $config->get('services.ovh.app_key'),
        $config->get('services.ovh.app_secret'),
        $config->get('services.ovh.endpoint'),
        $config->get('services.ovh.consumer_key'),
      );
    });

    Notification::resolved(function (ChannelManager $service) {
      $factory = function ($app) {
        return new Channels\OvhSmsChannel(
          $app->make(OvhApi::class),
          $app['config']['services.ovh.sms_account'],
          $app['config']['services.ovh.sms_default_sender'],
          $app['config']['services.ovh.sms_sandbox_mode'] ?? false,
        );
      };
      $service->extend('Illuminate\Notifications\Channels\OvhSmsChannel', $factory);
      $service->extend('ovhSms', $factory);
    });
  }

  /**
   * Get the services provided by the provider.
   * @return array
   */
  public function provides()
  {
    return [OvhApi::class];
  }
}
