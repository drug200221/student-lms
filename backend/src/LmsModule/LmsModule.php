<?php

namespace Psk\LmsModule;

use Ox3a\Common\Module\ModuleInterface;
use Ox3a\Common\StudentApplication;

class LmsModule implements ModuleInterface
{
    /**
     * @var string
     */
    private $path = __DIR__;

    /**
     * @var StudentApplication
     */
    private $app;

    public function setApp(StudentApplication $app)
    {
        $this->app = $app;
    }

    public function getName()
    {
        $className = static::class;
        return substr($className, strrpos($className, '\\') + 1);
    }

    public function getConfigDir()
    {
        return $this->path . '/configs';
    }

    public function getResourceDir()
    {
        return $this->path . '/Resources';
    }

    public function bootstrap()
    {
    }
}
