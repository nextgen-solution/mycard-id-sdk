<?php

namespace MyCard\ID;

use SocialiteProviders\Manager\SocialiteWasCalled;

class ExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('mycard', __NAMESPACE__.'\SocialiteProvider');
    }
}
