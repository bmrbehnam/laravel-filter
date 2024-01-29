<?php

namespace App\Exceptions;

use App\Notifications\FilterExceptionNotification;
use Exception;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FilterException extends Exception
{
    public function __construct(Throwable $throwable)
    {
        parent::__construct($throwable->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->notifyAdmin();
    }

    private function notifyAdmin(): void
    {
        Notification::route('mail', config('admin.email'))->route('sms', config('admin.mobile'))
            ->notify(new FilterExceptionNotification());
    }
}
