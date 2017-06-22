<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-03-10 14:12
 */
namespace Notadd\Vuser\Injections;

use Notadd\Foundation\Module\Abstracts\Uninstaller as AbstractUninstaller;

/**
 * Class Uninstaller.
 */
class Uninstaller extends AbstractUninstaller
{
    /**
     * @return mixed
     */
    public function handle()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function require ()
    {
        return true;
    }
}
