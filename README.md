# Geevoo OAuth2 Connector

```bash
composer require geevoode/socialite-connect
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

This is a fork from @SocialiteProviders (https://github.com/SocialiteProviders/Laravel-Passport).

### Add configuration to `config/services.php`

```php
'geevoo' => [    
  'client_id' => env('GEEVOO_CLIENT_ID'),  
  'client_secret' => env('GEEVOO_CLIENT_SECRET'),  
  'redirect' => env('GEEVOO_REDIRECT_URI'),
  'host' => env('GEEVOO_HOST'),
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \GeevooDE\OAuth2\GeevooExtendSocialite::class.'@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('geevoo')->redirect();
```

### Returned User fields

- ``id``
- ``nickname``
- ``name``
- ``email``
- ``avatar``
