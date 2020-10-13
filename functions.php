<?php

use App\Models\Link;


/**
 * Retrieve a reference to the instance of the model class Link
 * @return Link
 */
function cpLink(): Link
{
    return new Link();
}
