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

namespace Vilex\Templates;


use Psr\Http\Message\ResponseInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;

/**
 * Class PaginaMestra
 * @package Vilex\Templates
 * @covers PaginaMestraTest
 */
class PaginaMestra extends AbstractTemplate
{
    /** @var array */
    private $areas_layout = [];

    /**
     * PaginaMestra constructor.
     * @param string $arquivo
     * @param array $contexto
     * @throws PaginaMestraInvalidaException
     */
    public function __construct(string $arquivo, array $contexto = [])
    {
        try {
            parent::__construct($arquivo, $contexto);
        } catch (TemplateInvalidoException $e) {
            throw PaginaMestraInvalidaException::naoEncontrada($this->getArquivo());
        }

        $this->identificarAreasLayout();
    }

    /**
     * @return array
     */
    public function getAreasLayout(): array
    {
        return $this->areas_layout;
    }

    /**
     * Identifica e armazena as áreas de layout contidas na página mestra
     * @return $this
     */
    private function identificarAreasLayout(): self
    {
        $conteudo_pagina_mestra = $this->getConteudo();
        preg_match_all('~\[[\w\-]+/]~', $conteudo_pagina_mestra, $areas_layout);

        $this->areas_layout = array_map(function (string $area) {
            return preg_replace('~(^\[|/\]$)~', '', $area);
        }, $areas_layout[0]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        ob_start();
        include $this->getArquivo();
        $pagina_mestra_renderizada = ob_get_contents();
        ob_end_clean();

        return $pagina_mestra_renderizada;
    }
}