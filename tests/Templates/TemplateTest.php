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

namespace Vilex\Tests\Templates;


use Vilex\Exceptions\TemplateInvalidoException;
use Vilex\Templates\Template;
use Vilex\Tests\TestCase\VileXTestCase;

/**
 * Class TemplateTest
 * @package Vilex\Tests
 * @coversDefaultClass \Vilex\Templates\Template
 */
class TemplateTest extends VileXTestCase
{
    public function providerAtributos(): array
    {
        return [
            ['attr1', 'teste1'],
            ['attr2', 'teste2'],
            ['attr3', 'teste3'],
            ['attr4', 'teste4'],
            ['attr5', 'teste5']
        ];
    }

    /**
     * @covers ::__construct
     */
    public function test_instanciar_Template_com_arquivo_que_nao_existe()
    {
        $this->expectException(TemplateInvalidoException::class);
        new Template('arquivo/nao/existe');
    }

    /**
     * @covers ::__construct
     */
    public function test_instanciar_Template_com_arquivo_valido()
    {
        $template = new Template('../../exemplos/template.phtml');
        $this->assertInstanceOf(Template::class, $template);
    }

    /**
     * @param string $nome
     * @param mixed $valor
     * @dataProvider providerAtributos
     * @throws TemplateInvalidoException
     * @covers ::__construct
     */
    public function test__construct_deve_definir_contexto_do_template(string $nome, $valor)
    {
        $template = new Template('../../exemplos/template.phtml', [
            $nome => $valor
        ]);

        $this->assertEquals($valor, $template->getAtributo($nome));
    }

    /**
     * @covers ::getAtributo
     */
    public function test_getAtributo_nao_existe()
    {
        $template = new Template('../../exemplos/template.phtml');
        $this->assertNull($template->getAtributo('bla_bla'));
    }
}