<?php

namespace GeevooDE\OAuth2;

use SocialiteProviders\Manager\SocialiteWasCalled;

class GeevooExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('geevoo', Provider::class);
    }
}
