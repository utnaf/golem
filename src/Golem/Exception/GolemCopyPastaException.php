<?php 

namespace Golem\Exception;

use Symfony\Component\Filesystem\Exception\IOException;

final class GolemCopyPastaException extends IOException {

    public function __constructor($message)
    {
        parent::__constructor($message, 42);
    }

}