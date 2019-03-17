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


use Vilex\Exceptions\ViewNaoEncontradaException;

class Template
{
    /** @var string */
    private $extensao_template = 'phtml';
    /** @var string */
    private $arquivo;
    /** @var array */
    private $atributos = [];

    /**
     * @return string
     */
    public function getExtensaoTemplate(): string
    {
        return $this->extensao_template;
    }

    /**
     * @param string $extensao_template
     * @return VileX
     */
    public function setExtensaoTemplate(string $extensao_template): Template
    {
        $this->extensao_template = $extensao_template;
        return $this;
    }

    /**
     * @return string
     */
    public function getArquivo(): string
    {
        return $this->arquivo;
    }

    /**
     * @param string $arquivo
     * @return Template
     * @throws ViewNaoEncontradaException
     */
    public function setArquivo(string $arquivo): Template
    {
        $caminho_template = $this->getCaminhoCompletoTemplate($arquivo);

        if (stream_resolve_include_path($caminho_template) === false) {
            throw new ViewNaoEncontradaException($caminho_template);
        }

        $this->arquivo = $caminho_template;
        return $this;
    }

    /**
     * Montar o caminho completo do template.
     * @param string $nome_template
     * @return string
     */
    public function getCaminhoCompletoTemplate(string $nome_template): string
    {
        return "{$nome_template}.{$this->extensao_template}";
    }

    /**
     * @return array
     */
    public function getAtributos(): array
    {
        return $this->atributos;
    }

    /**
     * @param string $nome
     * @param $valor
     * @return Template
     */
    public function setAtributo(string $nome, $valor): Template
    {
        $this->atributos[$nome] = $valor;
        return $this;
    }

    /**
     * Obter o valor de um determinado atributo
     * @param string $nome
     * @return mixed|null
     */
    public function getAtributo(string $nome)
    {
        return array_key_exists($nome, $this->atributos)
            ? $this->atributos[$nome]
            : null;
    }

    /**
     * @param string $nome
     * @return $this
     */
    public function unsetAtributo(string $nome): Template
    {
        unset($this->atributos[$nome]);
        return $this;
    }

    /**
     * Excluir todos os atributos desse template
     * @return Template
     */
    public function unsetTodosAtributos(): Template
    {
        $this->atributos = [];
        return $this;
    }

    /**
     * Template constructor.
     * @param string $arquivo
     * @throws ViewNaoEncontradaException
     */
    public function __construct(string $arquivo)
    {
        $this->setArquivo($arquivo);
    }

    /**
     * Renderizar o conteúdo do template
     * @return void
     * @deprecated
     * Quando esse método é usado, o contexto $this do template passa a ser
     * a classe Template. Por enquanto, é necessário que o contexto $this seja a classe
     * VileX
     */
    public function render(): void
    {
        include_once $this->getArquivo();
    }
}