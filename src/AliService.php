<?php


namespace Wdy\AliService;


use Illuminate\Support\Facades\Facade;

class AliService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ali_service';
    }
}