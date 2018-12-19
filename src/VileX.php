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


use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Zend\Diactoros\Response\HtmlResponse;

class VileX
{
    /** @var string */
    private $view_root = './';
    /** @var array */
    private $templates = [];
    /** @var array */
    private $atributos = [];
    /** @var string */
    private $contexto_atual;
    /** @var string|null */
    private $pagina_mestra;
    /** @var array */
    private $arquivos_js = [];
    /** @var array */
    private $arquivos_css = [];

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
     * @return array
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @param string $arquivo
     * @param array $atributos
     * @return VileX
     * @throws ViewNaoEncontradaException
     */
    public function addTemplate(string $arquivo, array $atributos = []): VileX
    {
        $template = new Template("{$this->view_root}{$arquivo}");

        foreach ($atributos as $nome => $valor) {
            $template->setAtributo($nome, $valor);
        }

        $this->templates[$arquivo] = $template;
        return $this;
    }

    /**
     * Excluir um template
     * @param string $template
     * @return VileX
     */
    public function excluirTemplate(string $template): VileX
    {
        $key = array_search($template, $this->templates);
        unset($this->templates[$key]);
        return $this;
    }

    /**
     * @param string $nome
     * @param $valor
     * @param null|string $contexto
     * @return VileX
     * @throws ContextoInvalidoException
     */
    public function setAtributo(string $nome, $valor, ?string $contexto = null): Vilex
    {
        if (is_null($contexto)) {
            $this->atributos[$nome] = $valor;
        } else {
            if (!array_key_exists($contexto, $this->templates)) {
                throw new ContextoInvalidoException($contexto, 'contexto não encontrado');
            }

            /** @var Template $template */
            $template = $this->templates[$contexto];
            $template->setAtributo($nome, $valor);
        }

        return $this;
    }

    /**
     * Excluir um parâmetro.
     * @param string $nome
     * @param null|string $contexto
     * @return VileX
     * @throws ContextoInvalidoException
     */
    public function unsetAtributo(string $nome, ?string $contexto = null): VileX
    {
        if (is_null($contexto)) {
            unset($this->atributos[$nome]);
        } else {
            if (!array_key_exists($contexto, $this->templates)) {
                throw new ContextoInvalidoException($contexto, 'contexto não encontrado');
            }

            /** @var Template $template */
            $template = $this->templates[$contexto];
            $template->unsetAtributo($nome);
        }

        return $this;
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
        $atributos_acessiveis = $this->atributos;

        if (array_key_exists($this->contexto_atual, $this->templates)) {
            /** @var Template $template */
            $template = $this->templates[$this->contexto_atual];
            $atributos_acessiveis = array_merge($atributos_acessiveis, $template->getAtributos());
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
     * @param string $arquivo
     * @return string
     */
    public function getContextoByTemplate(string $arquivo): string
    {
        $nome = basename($arquivo);
        return substr($nome, 0, strrpos($nome, '.'));
    }

    /**
     * Setar o contexto atual de acordo com o nome de um template
     * @param string $arquivo
     */
    public function setContextoByTemplate(string $arquivo)
    {
        $this->setContextoAtual($this->getContextoByTemplate($arquivo));
    }

    /**
     * @return null|string
     */
    public function getPaginaMestra(): ?string
    {
        return $this->pagina_mestra;
    }

    /**
     * @param null|string $pagina_mestra
     * @return VileX
     */
    public function setPaginaMestra(?string $pagina_mestra): VileX
    {
        $this->pagina_mestra = $pagina_mestra;
        return $this;
    }

    /**
     * Todos os arquivos JS
     * @return array
     */
    public function getArquivosJs(): array
    {
        return $this->arquivos_js;
    }

    /**
     * Adicionar um arquivo JS
     * @param string $arquivo_js
     * @return VileX
     */
    public function addArquivoJS(string $arquivo_js): VileX
    {
        $this->arquivos_js[] = $arquivo_js;
        return $this;
    }

    /**
     * Gerar tags HTML para incluir arquivos JS
     * @return string
     */
    public function getTagsJs(): string
    {
        $html = '';

        if (count($this->getArquivosJs()) > 0) {
            $html .= "[ARQUIVOS-JAVASCRIPT]\n";
            $html .= "\t<script src=\"" . implode("\">\n\t<script src=\"", $this->getArquivosJs()) . '">';
            $html .= "\n[/ARQUIVOS-JAVASCRIPT]";
        }

        return $html;
    }

    /**
     * Todos os arquivos CSS
     * @return array
     */
    public function getArquivosCss(): array
    {
        return $this->arquivos_css;
    }

    /**
     * Adicionar um arquivo CSS
     * @param string $arquivo_css
     * @return VileX
     */
    public function addArquivoCss(string $arquivo_css): VileX
    {
        $this->arquivos_css[] = $arquivo_css;
        return $this;
    }

    /**
     * Gerar tags HTML para incluir CSS
     * @return string
     */
    public function getTagsCss(): string
    {
        $html = '';

        if (count($this->getArquivosCss()) > 0) {
            $html .= "[ARQUIVOS-CSS]\n";
            $html .= "\t<link rel=\"stylesheet\" href=\"" . implode($this->getArquivosCss()) . '"></link>';
            $html .= "\n[/ARQUIVOS-CSS]";
        }

        return $html;
    }

    /**
     * Renderizar o conteúdo HTML
     * @return HtmlResponse
     * @throws Exceptions\PaginaMestraNaoEncontradaException
     */
    public function render(?string $arquivo_pagina_mestra = null): HtmlResponse
    {
        $html = '';
        $html .= $this->getTagsCss();
        $html .= $this->getTagsJs();


        ob_start();
        /** @var Template $template */
        foreach ($this->templates as $template) {
            $this->setContextoByTemplate($template->getArquivo());
            include $template->getArquivo();
            // $template->render();
        }

        $html .= ob_get_contents();
        ob_end_clean();

        $arquivo_pagina_mestra = $arquivo_pagina_mestra ?? $this->getPaginaMestra();
        if (!empty($arquivo_pagina_mestra)) {
            $pagina_mestra = new PaginaMestra($arquivo_pagina_mestra);
            $html = (new MergePaginaMestraComConteudo($pagina_mestra))->merge($html);
        }

        return new HtmlResponse($html);
    }
}