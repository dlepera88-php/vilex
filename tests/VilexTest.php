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

namespace Vilex\Tests;


use PHPUnit\Framework\TestCase;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\TemplateInvalidoException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;

/**
 * Class VilexTest
 * @package Vilex\Tests
 * @coversDefaultClass \Vilex\VileX
 */
class VilexTest extends TestCase
{
    /** @var VileX */
    private static $vilex;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$vilex = new VileX();
    }

    /**
     * @test
     */
    public function getContextoByTemplate(): void
    {
        $this->markTestSkipped('O método Vilex::getContextoByTemplate está depreciado.');
        $contexto = self::$vilex->getContextoByTemplate('path/to/template.phtml');
        $this->assertEquals('template', $contexto);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function adicionarTemplateQueNaoExiste(): void
    {
        self::$vilex->setViewRoot('path/to/');

        $this->expectException(TemplateInvalidoException::class);
        $this->expectExceptionCode(10);
        self::$vilex->addTemplate('template');
    }

    /**
     * @covers ::getTagsJs
     */
    public function test_getTagsJs()
    {
        self::$vilex->addArquivoJS('Recursos/arquivos/arquivo1.js');
        self::$vilex->addArquivoJS('Recursos/arquivos/arquivo2.js');

        $tags_js = self::$vilex->getTagsJs();

        $this->assertContains('<script', $tags_js);
        $this->assertContains('</script>', $tags_js);
        $this->assertContains('[ARQUIVOS-JAVASCRIPT]', $tags_js);
        $this->assertContains('[/ARQUIVOS-JAVASCRIPT]', $tags_js);
    }

    /**
     * @covers ::addArquivoJS
     */
    public function test_AddArquivoJS()
    {
        $include_path = get_include_path();
        $novo_include_path = '../../../painel-dlx/reservas-dlx/:teste/';
        set_include_path($include_path . PATH_SEPARATOR . $novo_include_path);

        $arquivo_css = '/vendor/autoload.php';
        self::$vilex->addArquivoJS($arquivo_css);

        set_include_path($include_path);

        $this->assertTrue(true);
    }
}