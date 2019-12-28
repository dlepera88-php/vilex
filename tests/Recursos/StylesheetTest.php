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

namespace Vilex\Tests\Recursos;

use Vilex\Recursos\Stylesheet;
use PHPUnit\Framework\TestCase;

/**
 * Class StylesheetTest
 * @package Vilex\Tests\Recursos
 * @coversDefaultClass \Vilex\Recursos\Stylesheet
 */
class StylesheetTest extends TestCase
{
    /**
     * @return array
     */
    public function providerAtributosCss(): array
    {
        return [
            ['teste.css', null, 'all'],
            ['teste.css', '1.2.3', 'print'],
            ['teste.css', '1.2.3', 'screen']
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getSrc
     * @covers ::getVersao
     * @covers ::getMedia
     * @dataProvider providerAtributosCss
     * @param string $arquivo
     * @param string|null $versao
     * @param string|null $media
     */
    public function test__construct(string $arquivo, ?string $versao, ?string $media)
    {
        $stylesheet = new Stylesheet($arquivo, $versao, $media);

        $this->assertEquals($arquivo, $stylesheet->getSrc());
        $this->assertEquals($versao, $stylesheet->getVersao());
        $this->assertEquals($media, $stylesheet->getMedia());
    }

    /**
     * @covers ::getTagHtml
     * @param string $arquivo
     * @param string|null $versao
     * @param string $media
     * @dataProvider providerAtributosCss
     */
    public function test_GetTagHtml_deve_montar_e_retornar_tag_html(string $arquivo, ?string $versao, string $media)
    {
        $stylesheet = new Stylesheet($arquivo, $versao, $media);
        $tag_html = $stylesheet->getTagHtml();

        $this->assertIsString($tag_html);
        $this->assertRegExp('~^<link~', $tag_html);
    }
}
