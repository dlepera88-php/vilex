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

use Vilex\MergePaginaMestraComConteudo;
use PHPUnit\Framework\TestCase;
use Vilex\PaginaMestra;

class MergePaginaMestraComConteudoTest extends TestCase
{
    /**
     * @return array
     */
    public function providerConteudos(): array
    {
        return [
            ['
                [CABECALHO]<title>Título da Página</title>[/CABECALHO]
                [CORPO]<div>Teste com mais de 1 bloco de CORPO</div>[/CORPO]
                [CORPO]<div>Teste com mais de 1 bloco de CORPO</div>[/CORPO]
            '],

            ['
                [HTML-HEAD]<meta name="Content-type" content="text/html">[/HTML-HEAD]
                [CABECALHO]<title>Título da Página</title>[/CABECALHO]
                [CORPO]<div>Teste com mais de 1 bloco de CORPO</div>[/CORPO]
                [CORPO]<div>Teste com mais de 1 bloco de CORPO</div>[/CORPO]
                [RODAPE]Tel para contato: (61) 9 8350-0000[/RODAPE]
            '],

            ['
                [CORPO]
                    <form id="form-usuario" method="post" action="?task=usuarios/cadastrar-novo-usuario">
                    </form>
                [/CORPO]
            ']
        ];
    }

    /**
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @dataProvider providerConteudos
     */
    public function test_extrairAreasLayoutConteudo_com_pagina_mestra_exemplo(string $conteudo)
    {
        $pagina_mestra = new PaginaMestra(PaginaMestraTest::PAGINA_MESTRA_EXEMPLO);
        $areas_layout = $pagina_mestra->getAreasLayout();

        $areas_layout_conteudo = (new MergePaginaMestraComConteudo($pagina_mestra))
            ->extrairAreasLayoutConteudo($conteudo);

        // Independente de não ter conteúdo para todas as áreas configuradas na página mestra,
        // a extração sempre vai retornar um array associativo com todas as áreas de layout identificadas.
        $this->assertCount(count($areas_layout), $areas_layout_conteudo);

        foreach ($areas_layout as $area) {
            $this->assertArrayHasKey($area, $areas_layout_conteudo);
        }
    }

    public function test_Merge()
    {
        // TODO: implementar esse teste
        $this->markTestSkipped();
    }
}
