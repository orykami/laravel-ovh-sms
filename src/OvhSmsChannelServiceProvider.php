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
      $service->extend('ovhSms', function () {
        return new Channels\OvhSmsChannel(
          $this->app->make(OvhApi::class),
          $this->app['config']['services.ovh.sms_account'],
          $this->app['config']['services.ovh.sms_default_sender'],
          $this->app['config']['services.ovh.sms_sandbox_mode'] ?? false,
        );
      });
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
