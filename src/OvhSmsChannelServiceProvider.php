<?php

namespace Illuminate\Notifications;

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
    // Bind Nexmo Client in Service Container.
    $this->app->singleton(OvhApi::class, function ($app) {
      return new OvhApi(
        $this->app['config']['service.ovh.app_key'],
        $this->app['config']['service.ovh.app_secret'],
        $this->app['config']['service.ovh.endpoint'],
        $this->app['config']['service.ovh.consumer_key'],
      );
    });

    Notification::resolved(function (ChannelManager $service) {
      $service->extend('ovhSms', function ($app) {
        return new Channels\OvhSmsChannel(
          $this->app->make(OvhApi::class),
          $this->app['config']['services.ovh.sms.account'],
          $this->app['config']['services.ovh.sms.from'],
        );
      });
    });
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return [OvhApi::class];
  }
}
