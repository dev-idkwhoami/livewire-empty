<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class NoStacktraceFormatter
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                "[%datetime%] %level_name%: %message% %context%\n",
                null,
                true,
                true
            ));
        }

    }
}
