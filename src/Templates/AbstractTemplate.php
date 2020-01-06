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


use Vilex\Exceptions\TemplateInvalidoException;
use Vilex\Services\RenderizaContexto2Html;

abstract class AbstractTemplate
{
    /** @var string */
    private $arquivo;
    /** @var array */
    private $contexto = [];
    /** @var string */
    private $conteudo;
    /** @var RenderizaContexto2Html */
    protected $renderiza_contexto_html;

    /**
     * AbstractTemplate constructor.
     * @param string $arquivo
     * @param array $contexto
     * @throws TemplateInvalidoException
     */
    public function __construct(string $arquivo, array $contexto = [])
    {
        $this->arquivo = $arquivo;
        $this->contexto = $contexto;
        $this->renderiza_contexto_html = new RenderizaContexto2Html();
        $this->existsArquivo(true);
        $this->extrairConteudoArquivo();
    }

    /**
     * @return string
     */
    public function getArquivo(): string
    {
        return $this->arquivo;
    }

    /**
     * @return array
     */
    public function getContexto(): array
    {
        return $this->contexto;
    }

    /**
     * @return string
     */
    public function getConteudo(): string
    {
        return $this->conteudo;
    }

    /**
     * Extrair conteúdo do arquivo
     * @return $this
     */
    private function extrairConteudoArquivo(): self
    {
        $arquivo = stream_resolve_include_path($this->getArquivo());
        $this->conteudo = file_get_contents($arquivo);
        return $this;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws TemplateInvalidoException
     */
    public function existsArquivo(bool $throw = false): bool
    {
        $exists_arquivo = stream_resolve_include_path($this->getArquivo()) !== false;

        if ($throw && !$exists_arquivo) {
            throw TemplateInvalidoException::naoEncontrado($this->getArquivo());
        }

        return $exists_arquivo;
    }

    /**
     * @return string
     */
    abstract public function render(): string;

    /**
     * @param array $contexto_global
     * @return $this
     */
    public function addContextoGlobal(array $contexto_global): self
    {
        $this->contexto = array_merge($contexto_global, $this->getContexto());
        return $this;
    }

    /**
     * @param string $nome_atributo Nome do atributo desejado
     * @return mixed|null
     */
    public function getAtributo(string $nome_atributo)
    {
        if (!array_key_exists($nome_atributo, $this->getContexto())) {
            return null;
        }

        return $this->getContexto()[$nome_atributo];
    }
}