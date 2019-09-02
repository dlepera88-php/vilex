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


use Vilex\Componentes\PaginaMestra;
use Vilex\Exceptions;

class MergePaginaMestraComConteudo
{
    /** @var PaginaMestra */
    private $pagina_mestra;

    /**
     * MergePaginaMestraComConteudo constructor.
     * @param PaginaMestra $pagina_mestra
     */
    public function __construct(PaginaMestra $pagina_mestra)
    {
        $this->pagina_mestra = $pagina_mestra;
    }

    /**
     * Extrair o conteúdo de uma determinada área de layout do conteúdo
     * @param string $conteudo
     * @return array
     */
    public function extrairAreasLayoutConteudo(string $conteudo): array
    {
        $areas_layout_conteudo = [];

        foreach ($this->pagina_mestra->getAreasLayout() as $area_layout) {
            preg_match_all("~(?s)\[{$area_layout}\](.*?)\[/{$area_layout}\]~", $conteudo, $conteudo_area);
            $areas_layout_conteudo[$area_layout] = $conteudo_area[1];
        }

        return $areas_layout_conteudo;
    }

    /**
     * Acoplar o conteúdo informado dentro da página mestra.
     * @param string $conteudo
     * @return string
     * @throws Exceptions\PaginaMestraNaoEncontradaException
     */
    public function merge(string $conteudo): string
    {
        $html = $this->pagina_mestra->getConteudo();
        $conteudo_extraido = $this->extrairAreasLayoutConteudo($conteudo);

        foreach ($this->pagina_mestra->getAreasLayout() as $area_layout) {
            $html = str_replace(
                "[{$area_layout}/]",
                implode("\n", $conteudo_extraido[$area_layout]),
                $html
            );
        }

       return $html;
    }
}