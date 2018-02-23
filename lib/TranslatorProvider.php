<?php

/* MIT License

Copyright (c) 2018 Eridan Domoratskiy

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE. */

namespace MaintenanceScreen;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;

/**
 * Provides Translator instances
 * from directories with config files
 *
 * @author ProgMiner
 */
class TranslatorProvider {

    /**
     * @var ConfigurationLoader Configuration loader
     */
    protected $configLoader;

    public function __construct(ConfigurationLoader $configLoader) {
        $this->configLoader = $configLoader;
    }

    /**
     * Makes Translator from config file
     *
     * @param string $lang     Language name
     * @param bool   $fileName Use language name as full filename
     *
     * @return Translator
     */
    public function getTranslator(string $lang, bool $fileName = false): Translator {
        if ($fileName) {
            return Translator::fromConfigFile($lang, $this->configLoader);
        }

        $exception = null;
        try {
            return Translator::fromConfigFile("$lang.yml", $this->configLoader);
        } catch (FileLocatorFileNotFoundException $e) {
            $exception = $e;
        }

        $lang = str_replace('-', '_', $lang);
        if (false !== ($pos = strpos($lang, '_'))) {
            try {
                return $this->getTranslator(substr($lang, 0, $pos));
            } catch (\Throwable $e) {}
        }

        throw $exception;
    }

    /**
     * Makes Translator from config file
     * for preferred language
     *
     * @param array $langs    Languages ordered by preference
     * @param bool  $fileName Use languages as full filename
     *
     * @return Translator
     */
    public function getPreferredTranslator(array $langs, bool $fileName = false): Translator {
        foreach ($langs as $lang) {
            try {
                return $this->getTranslator($lang, $fileName);
            } catch (FileLocatorFileNotFoundException $e) {}
        }

        throw new \RuntimeException('No one Translator config files for languages exists');
    }
}