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

namespace Vilex;


use Vilex\Exceptions\PaginaMestraNaoEncontradaException;

class PaginaMestra
{
    /** @var string */
    private $pagina_mestra;
    /** @var array */
    private $areas_layout = [];

    /**
     * @return string
     */
    public function getPaginaMestra(): string
    {
        return $this->pagina_mestra;
    }

    /**
     * @param string $pagina_mestra
     * @return PaginaMestra
     */
    public function setPaginaMestra(string $pagina_mestra): PaginaMestra
    {
        $this->pagina_mestra = $pagina_mestra;
        return $this;
    }

    /**
     * @return array
     */
    public function getAreasLayout(): array
    {
        return $this->areas_layout;
    }

    /**
     * PaginaMestra constructor.
     * @param string $pagina_mestra
     * @throws PaginaMestraNaoEncontradaException
     */
    public function __construct(string $pagina_mestra)
    {
        $this->setPaginaMestra($pagina_mestra);
        $this->identificarAreasLayout();
    }

    /**
     * Verifica se a página mestra existe
     * @return bool
     */
    public function existsPaginaMestra(): bool
    {
        return file_exists($this->pagina_mestra);
    }

    /**
     * Retorna o conteúdo da página mestra
     * @return string
     * @throws PaginaMestraNaoEncontradaException
     */
    public function getConteudo(): string
    {
        if (!$this->existsPaginaMestra()) {
            throw new PaginaMestraNaoEncontradaException($this->getPaginaMestra());
        }

        // Dessa maneira, o retorno conterá os comentários do PHP, mas eles não são necessários
        // para carregar o conteúdo. Portanto, preferenciamente, utilizo o buffer para não obter
        // conteúdo desnecessário.
        // return file_get_contents($this->pagina_mestra);

        ob_start();
        include $this->getPaginaMestra();
        $conteudo = ob_get_contents();
        ob_end_clean();

        return $conteudo;
    }

    /**
     * Identifica e armazena as áreas de layout contidas na página mestra
     * @throws PaginaMestraNaoEncontradaException
     */
    public function identificarAreasLayout(): void
    {
        $conteudo_pagina_mestra = $this->getConteudo();
        preg_match_all('~\[[\w\-]+\/]~', $conteudo_pagina_mestra, $areas_layout);

        $this->areas_layout = array_map(function (string $area) {
            return preg_replace('~(^\[|/\]$)~', '', $area);
        }, $areas_layout[0]);
    }
}