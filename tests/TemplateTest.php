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
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\Template;

class TemplateTest extends TestCase
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
     * @throws ViewNaoEncontradaException
     */
    public function test_instanciar_Template_com_arquivo_que_nao_existe()
    {
        $this->expectException(ViewNaoEncontradaException::class);
        new Template('arquivo/nao/existe');
    }

    /**
     * @throws ViewNaoEncontradaException
     */
    public function test_instanciar_Template_com_arquivo_valido()
    {
        $template = new Template('../exemplos/template');

        $this->assertInstanceOf(Template::class, $template);
    }

    /**
     * @param $nome
     * @param $valor
     * @throws ViewNaoEncontradaException
     * @dataProvider providerAtributos
     */
    public function test_get_set_atributos($nome, $valor)
    {
        $template = new Template('../exemplos/template');
        $template->setAtributo($nome, $valor);

        $this->assertEquals($valor, $template->getAtributo($nome));
    }

    /**
     * @throws ViewNaoEncontradaException
     */
    public function test_getAtributo_nao_existe()
    {
        $template = new Template('../exemplos/template');
        $this->assertNull($template->getAtributo('bla_bla'));
    }

    /**
     * @throws ViewNaoEncontradaException
     */
    public function test_unsetAtributo()
    {
        $template = new Template('../exemplos/template');

        $template->setAtributo('teste', 'teste');
        $this->assertEquals('teste', $template->getAtributo('teste'));

        $template->unsetAtributo('teste');
        $this->assertNull($template->getAtributo('teste'));
    }
}