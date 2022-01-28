<?php

namespace App\Infrastructure\Exceptions;

use App\Application\Contracts\Exceptions\NotFoundExceptionInterface;
use Exception;

class NotFoundException extends Exception implements NotFoundExceptionInterface
{

}
