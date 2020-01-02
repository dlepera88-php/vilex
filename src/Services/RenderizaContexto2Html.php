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

namespace Vilex\Services;

/**
 * Class RenderizarContexto2Html
 * @package Vilex\Services
 * @experimental Esse é um recurso experimental que ainda não é certeza de ser mantido
 * @covers RenderizaContexto2HtmlTest
 */
class RenderizaContexto2Html
{
    /**
     * @param string $html_original
     * @param array $contexto
     * @return string
     */
    public function execute(string $html_original, array $contexto): string
    {
        $html_renderizado = $html_original;

        preg_match_all('~{{ ([\w\d-]+) }}~', $html_original, $contextos_presentes);
        $nomes_contextos = array_combine($contextos_presentes[0], $contextos_presentes[1]);

        foreach ($nomes_contextos as $contexto_html => $nome) {
            if (!array_key_exists($nome, $contexto)) {
                continue;
            }

            $html_renderizado = str_replace($contexto_html, $contexto[$nome], $html_renderizado);
        }

        return $html_renderizado;
    }
}