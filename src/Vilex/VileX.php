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
use Zend\Diactoros\Response\HtmlResponse;

class VileX
{
    /** @var string */
    private $view_root = './';
    /** @var string */
    private $extensao_template = 'phtml';
    /** @var array */
    private $templates = [];
    /** @var array */
    private $atributos = [
        'global' => []
    ];
    /** @var string */
    private $contexto_atual;

    /**
     * @return string
     */
    public function getViewRoot(): string
    {
        return $this->view_root;
    }

    /**
     * @param string $view_root
     * @return VileX
     */
    public function setViewRoot(string $view_root): VileX
    {
        $this->view_root = trim($view_root, '/') . '/';
        return $this;
    }

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
    public function setExtensaoTemplate(string $extensao_template): VileX
    {
        $this->extensao_template = $extensao_template;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @param string $atributos
     * @return VileX
     * @throws \Exception
     */
    public function addTemplate(string $template, array $atributos = []): VileX
    {
        $caminho_template = $this->getCaminhoCompletoTemplate($template);

        if (!file_exists($caminho_template)) {
            throw new ViewNaoEncontradaException("O template {$caminho_template} não foi encontrado.");
        }

        $this->templates[] = $caminho_template;

        if (count($atributos) > 0) {
            $contexto = $this->getContextoByTemplate($caminho_template);
            $this->setMultiAtributos($atributos, $contexto);
        }

        return $this;
    }

    /**
     * Excluir um template
     * @param string $template
     * @return VileX
     */
    public function excluirTemplate(string $template): VileX
    {
        $caminho_template = $this->getCaminhoCompletoTemplate($template);
        $key = array_search($caminho_template, $this->templates);
        unset($this->templates[$key]);
        return $this;
    }

    /**
     * @param string $nome
     * @param $valor
     * @return VileX
     */
    public function setAtributo(string $nome, $valor, string $contexto = 'global'): Vilex
    {
        $this->atributos[$contexto][$nome] = $valor;
        return $this;
    }

    /**
     * Setar vários parâmetros com base em um array
     * @param array $params
     * @param string $contexto
     * @return VileX
     */
    public function setMultiAtributos(array $params, ?string $contexto = null): Vilex
    {
        $contexto = $contexto ?? $this->contexto_atual ?? 'global';

        foreach ($params as $nome => $valor) {
            $this->setAtributo($nome, $valor, $contexto);
        }

        return $this;
    }

    /**
     * Excluir um parâmetro.
     * @param string $nome
     * @return bool
     */
    public function unsetAtributo(string $nome, ?string $contexto = null): bool
    {
        $contexto = $contexto ?? $this->contexto_atual ?? 'global';

        if (!array_key_exists($contexto, $this->atributos) && !array_key_exists($nome, $this->atributos[$contexto])) {
            return false;
        }
        unset($this->atributos[$contexto][$nome]);
        return true;
    }

    /**
     * Excluir todos os atributos de um determinado contexto.
     * @param null|string $contexto
     * @return bool
     */
    public function unsetAtributosContexto(?string $contexto = null): bool
    {
        $contexto = $contexto ?? $this->contexto_atual;

        unset($this->atributos[$contexto]);
        return true;
    }

    /**
     * Obter o valor de um determinado parâmetro
     * @param string $nome
     * @param null|string $contexto
     * @return null
     */
    public function getAtributo(string $nome)
    {
        $atributos_acessiveis = $this->getAtributosAcessiveis();

        return !array_key_exists($nome, $atributos_acessiveis)
            ? null
            : $atributos_acessiveis[$nome];
    }

    /**
     * Filtrar todos os atributos para retornar apenas os atributos acessíveis em um determinado
     * contexto.
     * @param string $contexto
     * @return array|null
     */
    private function getAtributosAcessiveis(): ?array
    {
        $atributos_acessiveis = $this->atributos['global'];

        if (array_key_exists($this->contexto_atual, $this->atributos)) {
            $atributos_acessiveis = array_merge($atributos_acessiveis, $this->atributos[$this->contexto_atual]);
        }

        return $atributos_acessiveis;
    }

    /**
     * @return string
     */
    public function getContextoAtual(): string
    {
        return $this->contexto_atual;
    }

    /**
     * @param string $contexto_atual
     */
    public function setContextoAtual(string $contexto_atual): VileX
    {
        $this->contexto_atual = $contexto_atual;
        return $this;
    }

    /**
     * Identificar o contexto de um template expecífico
     * @param string $template
     * @return string
     */
    public function getContextoByTemplate(string $template): string
    {
        $nome = basename($template);
        return substr($nome, 0, strrpos($nome, '.'));
    }

    /**
     * Setar o contexto atual de acordo com o nome de um template
     * @param string $template
     */
    public function setContextoByTemplate(string $template)
    {
        $this->setContextoAtual($this->getContextoByTemplate($template));
    }

    /**
     * Montar o caminho completo do template.
     * @param string $nome_template
     * @return string
     */
    public function getCaminhoCompletoTemplate(string $nome_template): string
    {
        return "{$this->view_root}{$nome_template}.{$this->extensao_template}";
    }

    /**
     * @return HtmlResponse
     */
    public function render()
    {
        ob_start();
        foreach ($this->templates as $template) {
            $this->setContextoByTemplate($template);
            include $template;
            $this->unsetAtributosContexto($this->getContextoAtual());
        }

        $html = ob_get_contents();
        ob_end_flush();

        return new HtmlResponse($html);
    }
}