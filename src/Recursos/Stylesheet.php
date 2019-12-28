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

namespace Vilex\Recursos;

use Vilex\Services\Array2AtributosHtml;

/**
 * Class Stylesheet
 * @package Vilex\Recursos
 * @covers StylesheetTest
 */
class Stylesheet extends AbstractRecurso
{
    /** @var string */
    private $media = 'all';

    /**
     * Stylesheet constructor.
     * @param string $src
     * @param string|null $versao
     * @param string $media
     */
    public function __construct(string $src, ?string $versao = null, string $media = 'all')
    {
        parent::__construct($src, $versao);
        $this->media = $media;
    }

    /**
     * @return string
     */
    public function getMedia(): string
    {
        return $this->media;
    }

    /**
     * @inheritDoc
     */
    function getTagHtml(): string
    {
        $tag_link = '<link rel="stylesheet" %s>';

        $src_com_versao = $this->getSrc();

        if (!empty($this->getVersao())) {
            $src_com_versao .= "?{$this->getVersao()}";
        }

        $atributos = ['href' => $src_com_versao];

        if (!empty($this->getMedia())) {
            $atributos['media'] = $this->getMedia();
        }

        $attr_html = (new Array2AtributosHtml())->execute($atributos);

        return sprintf($tag_link, $attr_html);
    }
}