<?php

namespace Paksuco\DuskTimeTravel;

use Illuminate\Support\Carbon;
use Laravel\Dusk\Browser as DuskBrowser;

trait CanChangeDuskTime
{
    /**
     * Travels to the specified time
     *
     * @param   Carbon  $time  The time to mimic on the browser
     *
     * @return  Browser        The browser instance to support chaining
     */
    public function travelTo(Carbon $time)
    {
        return tap($this, function (DuskBrowser $browser) use ($time) {
            $browser->plainCookie("dusk-skip-time", $time->toIso8601String());
        });
    }

    /**
     * Returns back to the current time
     *
     * @return  Browser        The browser instance to support chaining
     */
    public function travelBack()
    {
        return tap($this, function (DuskBrowser $browser) {
            $browser->deleteCookie("dusk-skip-time");
        });
    }
}
