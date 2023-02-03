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
- ``name``
- ``email``
- ``avatar``
- ``date_of_birth``

### Scopes

| Scope        | Description                                                                                   |
|--------------|-----------------------------------------------------------------------------------------------|
| user-info    | (Default scope) Get personal information of user such as id, name, email & date of birth.     |
| user-avatar  | Get user avatar if present. Only available using the method ``$user->getAvatar()``.           |
| user-address | Get user address if present. Only available using the method ``$user->getAddress()``.         |
| card         | Get user student card. Only available using the method ``$user->getCard()``.                  |
| qr           | Generate a student qr code. Only available using the method ``$user->getQr(bool $extended)``. |
