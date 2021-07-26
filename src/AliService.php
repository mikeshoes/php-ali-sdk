<?php


namespace Wdy\AliService;


use Illuminate\Support\Facades\Facade;
use Wdy\AliService\Interfaces\OssInterface;

/**
 * @method static OssInterface gate(string $name = null) eg. file, media
 *
 * @see ServiceManager
 */

class AliService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ali_service';
    }
}