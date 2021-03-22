<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Vilex;


class VileXConfiguracao
{
    /** @var string */
    private $root = './';
    /** @var string */
    private $base_html = '';
    /** @var string */
    private $extensao_template = 'phtml';

    /**
     * VileXConfiguracao constructor.
     * @param string $root
     * @param string $base_html
     * @param string $extensao_template
     */
    public function __construct(string $root = './', string $base_html = '', string $extensao_template = 'phtml')
    {
        $this->root = $root;
        $this->base_html = $base_html;
        $this->extensao_template = $extensao_template;
    }

    /**
     * @return string
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * @param string $root
     * @return VileXConfiguracao
     */
    public function setRoot(string $root): VileXConfiguracao
    {
        $this->root = $root;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseHtml(): string
    {
        return $this->base_html;
    }

    /**
     * @param string $base_html
     * @return VileXConfiguracao
     */
    public function setBaseHtml(string $base_html): VileXConfiguracao
    {
        $this->base_html = $base_html;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtensaoTemplate(): string
    {
        return $this->extensao_template;
    }

    /**
     * @param string $extensao_template
     * @return VileXConfiguracao
     */
    public function setExtensaoTemplate(string $extensao_template): VileXConfiguracao
    {
        $this->extensao_template = $extensao_template;
        return $this;
    }
}