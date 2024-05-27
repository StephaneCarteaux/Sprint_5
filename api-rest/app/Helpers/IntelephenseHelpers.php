<?php

namespace Illuminate\Contracts\Auth;

interface Authenticatable
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function can();
    public function cannot();
}