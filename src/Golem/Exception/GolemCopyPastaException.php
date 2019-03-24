<?php

namespace Golem\Exception;

final class GolemCopyPastaException extends \Exception
{
    const DONT_PANIC = 42;

    /** @param string $message */
    public function __construct($message)
    {
        parent::__construct($message, static::DONT_PANIC);
    }
}
