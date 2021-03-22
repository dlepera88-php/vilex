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

namespace Vilex\Tests\Services;

use Vilex\Services\RenderizaContexto2Html;
use PHPUnit\Framework\TestCase;

/**
 * Class RenderizaContexto2HtmlTest
 * @package Vilex\Tests\Services
 * @coversDefaultClass \Vilex\Services\RenderizaContexto2Html
 */
class RenderizaContexto2HtmlTest extends TestCase
{
    /**
     * @covers ::execute
     */
    public function test_Execute()
    {
        $html_original = '
            <h1>{{ titulo-pagina }}</h1>
            
            <p>{{ conteudo }}</p>
        ';
        $contexto = [
            'titulo-pagina' => 'Página de Teste',
            'conteudo' => 'Hello World!'
        ];
        $html_renderizado_esperado = "
            <h1>{$contexto['titulo-pagina']}</h1>
            
            <p>{$contexto['conteudo']}</p>
        ";

        $renderiza_contexto_html = new RenderizaContexto2Html();
        $html_renderizado = $renderiza_contexto_html->execute($html_original, $contexto);

        $this->assertIsString($html_renderizado);
        $this->assertEquals($html_renderizado_esperado, $html_renderizado);
    }
}
