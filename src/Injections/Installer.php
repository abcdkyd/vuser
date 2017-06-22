<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-03-10 14:12
 */
namespace Notadd\Vuser\Injections;

use Notadd\Foundation\Module\Abstracts\Installer as AbstractInstaller;

/**
 * Class Installer.
 */
class Installer extends AbstractInstaller
{
    /**
     * @return bool
     */
    public function handle()
    {
        return true;
    }

    /**
     * @return array
     */
    public function require ()
    {
        return [];
    }
}
