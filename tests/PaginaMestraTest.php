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
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Componentes\PaginaMestra;

class PaginaMestraTest extends TestCase
{
    const PAGINA_MESTRA_NAO_EXISTE = 'pagina_mestra_nao_existe.phtml';
    const PAGINA_MESTRA_EXEMPLO = '../exemplos/pagina_mestra.phtml';

    /**
     * @throws PaginaMestraNaoEncontradaException
     */
    public function test_existsPaginaMestra_com_pagina_mestra_invalida(): void
    {
        $this->expectException(PaginaMestraNaoEncontradaException::class);
        new PaginaMestra(self::PAGINA_MESTRA_NAO_EXISTE);
    }

    /**
     * @throws PaginaMestraNaoEncontradaException
     */
    public function test_existsPaginaMestra_com_pagina_mestra_exemplo()
    {
        $pagina_mestra = new PaginaMestra(self::PAGINA_MESTRA_EXEMPLO);
        $this->assertTrue($pagina_mestra->existsPaginaMestra());
    }

    /**
     * @throws PaginaMestraNaoEncontradaException
     */
    public function test_getConteudo_pagina_mestra(): void
    {
        $pagina_mestra = new PaginaMestra(self::PAGINA_MESTRA_EXEMPLO);
        $conteudo = $pagina_mestra->getConteudo();
        $this->assertContains($conteudo, file_get_contents(self::PAGINA_MESTRA_EXEMPLO));

        $pagina_mestra->setPaginaMestra(self::PAGINA_MESTRA_NAO_EXISTE);
        $this->expectException(PaginaMestraNaoEncontradaException::class);
        $pagina_mestra->getConteudo();
    }

    /**
     * @throws PaginaMestraNaoEncontradaException
     */
    public function test_indentificaAreasLayout_com_pagina_mestra_valida(): void
    {
        $pagina_mestra = new PaginaMestra(self::PAGINA_MESTRA_EXEMPLO);
        $pagina_mestra->identificarAreasLayout();
        $areas_layout = $pagina_mestra->getAreasLayout();

        $this->assertCount(4, $areas_layout);
        $this->assertContains('HTML-HEAD', $areas_layout);
        $this->assertContains('CABECALHO', $areas_layout);
        $this->assertContains('CORPO', $areas_layout);
        $this->assertContains('RODAPE', $areas_layout);
    }
}