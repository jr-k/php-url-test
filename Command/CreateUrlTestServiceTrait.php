<?php

declare(strict_types=1);

namespace steevanb\PhpUrlTest\Command;

use steevanb\PhpUrlTest\UrlTestService;

trait CreateUrlTestServiceTrait
{
    protected function createFilteredIdsUrlTestService(string $path, bool $recursive, ?array $ids): UrlTestService
    {
        $return = $this->createUrlTestService($path, $recursive);
        if ($return->countTests($ids) === 0) {
            throw new \Exception('No test found.');
        }

        return $return;
    }

    protected function createUrlTestService(string $path, bool $recursive): UrlTestService
    {
        $return = new UrlTestService();
        if (is_dir($path)) {
            $urltestFileName = trim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'urltest.yml';
            if (file_exists($urltestFileName)) {
                $return->setConfigurationFile($urltestFileName);
            }
            $return->addTestDirectory($path, $recursive);
        } elseif (is_file($path) && basename($path) === 'urltest.yml') {
            $return->setConfigurationFile($path);
        } elseif (is_file($path)) {
            $return->addTestFile($path);
        } else {
            throw new \Exception('Invalid path or file name "' . $path . '".');
        }

        return $return;
    }
}
