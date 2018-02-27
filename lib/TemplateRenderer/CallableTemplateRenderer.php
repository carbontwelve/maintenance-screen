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

namespace MaintenanceScreen\TemplateRenderer;

/**
 * Template renderer for callable templates
 *
 * @author ProgMiner
 */
class CallableTemplateRenderer implements TemplateRendererInterface {

    /**
     * @var callable[] Templates
     */
    protected $templates;

    /**
     * @param callable[] $templates Templates
     */
    public function __construct(array $templates) {
        $this->templates = $templates;
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $template, array $variables): string {
        if (!isset($this->templates[$template])) {
            throw new \RuntimeException("Template {$template} is undefined");
        }

        ob_start();
        
        $this->templates[$template]($variables);

        $ret = ob_get_contents();
        ob_end_clean();

        return $ret;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $template): bool {
        return true;
    }
}