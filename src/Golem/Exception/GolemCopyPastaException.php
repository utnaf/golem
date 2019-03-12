<?php 

namespace Golem\Exception;

final class GolemCopyPastaException extends \Exception {

    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, 42);
    }

}
