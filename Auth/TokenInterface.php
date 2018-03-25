<?php

namespace WriteDown\Auth;

interface TokenInterface
{
    /**
     * Generate a secure token.
     *
     * @param int $length
     *
     * @return string
     */
    public function generate($length = 64);
}
