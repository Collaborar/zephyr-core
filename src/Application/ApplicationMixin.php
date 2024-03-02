<?php

namespace ZephyrPackage\Application;

/**
 * Can be applied to your App class via a "@mixin" annotation for better IDE support.
 * This class is not meant to be used in any other capacity.
 */
final class AplicationMixin
{
    /**
     * Prevent class instantiation.
     */
    private function __construct()
    {
    }
}
