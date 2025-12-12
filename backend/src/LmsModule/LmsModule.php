<?php

declare(strict_types=1);

namespace Psk\LmsModule;

use Ox3a\Common\Module\ModuleInterface;
use Ox3a\Common\StudentApplication;

final class LmsModule implements ModuleInterface
{
    /**
     * @var string
     */
    private $path = __DIR__;

    /**
     * @var StudentApplication
     */
    private $app;

    public function setApp(StudentApplication $app): void
    {
        $this->app = $app;
    }

    public function getName(): string
    {
        $className = static::class;
        return substr($className, strrpos($className, '\\') + 1);
    }

    public function getConfigDir(): string
    {
        return $this->path . '/configs';
    }

    public function getResourceDir(): string
    {
        return $this->path . '/Resources';
    }

    public function bootstrap(): void
    {
    }
}
