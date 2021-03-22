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

use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Vilex\Recursos\Javascript;
use Vilex\Recursos\Stylesheet;
use Vilex\Services\CaminhoCompletoRecurso;
use Vilex\Services\MergePaginaMestraComConteudo;
use Vilex\Templates\PaginaMestra;
use Vilex\Templates\Template;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Class VileX
 * @package Vilex
 * @covers VilexTest
 */
class VileX
{
    /** @var VileXConfiguracao */
    private $configuracao;
    /** @var string|null */
    private $pagina_mestra;
    /** @var array */
    private $templates = [];
    /** @var array */
    private $arquivos_js = [];
    /** @var array */
    private $arquivos_css = [];
    /** @var array */
    private $atributos = [];
    /** @var CaminhoCompletoRecurso */
    private $caminho_completo_recurso;

    /**
     * VileX constructor.
     * @param VileXConfiguracao|null $configuracao
     */
    public function __construct(?VileXConfiguracao $configuracao = null)
    {
        $this->configuracao = $configuracao ?? new VileXConfiguracao();
        $this->caminho_completo_recurso = new CaminhoCompletoRecurso();
    }

    /**
     * @return string
     * @deprecated Utilize a classe VileXConfiguracao através do constructor
     */
    public function getViewRoot(): string
    {
        return $this->configuracao->getRoot();
    }

    /**
     * @param string $view_root
     * @return VileX
     * @deprecated Utilize a classe VileXConfiguracao através do constructor
     */
    public function setViewRoot(string $view_root): VileX
    {
        $this->configuracao->setRoot($view_root);
        return $this;
    }

    /**
     * @return string
     * @deprecated Utilize a classe VileXConfiguracao através do constructor
     */
    public function getBaseHtml(): string
    {
        return $this->configuracao->getBaseHtml();
    }

    /**
     * @param string|null $base_html
     * @return VileX
     * @deprecated Utilize a classe VileXConfiguracao através do constructor
     */
    public function setBaseHtml(?string $base_html): VileX
    {
        $this->configuracao->setBaseHtml($base_html);
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
     * @throws TemplateInvalidoException
     */
    public function addTemplate(string $arquivo, array $atributos = []): VileX
    {
        $nome_arquivo_completo = "{$this->configuracao->getRoot()}{$arquivo}.{$this->configuracao->getExtensaoTemplate()}";
        $template = new Template($nome_arquivo_completo, $atributos);
        $this->templates[$arquivo] = $template;
        return $this;
    }

    /**
     * Excluir um template
     * @param string $template
     * @return VileX
     * @deprecated
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
     */
    public function setAtributo(string $nome, $valor, ?string $contexto = null): Vilex
    {
        $this->atributos[$nome] = $valor;
        return $this;
    }

    /**
     * Obter o valor de um determinado parâmetro
     * @param string $nome
     * @return null
     * @deprecated Essa responsabilidade será passada para o Template/Página Mestra
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
     * @return array|null
     * @deprecated
     */
    private function getAtributosAcessiveis(): ?array
    {
        return null;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getContextoAtual(): ?string
    {
        return null;
    }

    /**
     * @param string $contexto_atual
     * @return VileX
     * @deprecated
     */
    public function setContextoAtual(string $contexto_atual): VileX
    {
        return $this;
    }

    /**
     * Identificar o contexto de um template expecífico
     * @param string $arquivo
     * @return string
     * @deprecated
     */
    public function getContextoByTemplate(string $arquivo): ?string
    {
        return null;
    }

    /**
     * Setar o contexto atual de acordo com o nome de um template
     * @param string $arquivo
     * @deprecated
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
        $pagina_mestra = "{$this->configuracao->getRoot()}{$this->pagina_mestra}";

        if (preg_match('~\.[a-z0-9]{2,4}$~', $pagina_mestra) === 0) {
            $pagina_mestra .= ".{$this->configuracao->getExtensaoTemplate()}";
        }

        return $pagina_mestra;
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
     * @param bool $absoluto @deprecated
     * @param string|null $versao
     * @param string|null $type Tipo de script a ser gerado. Ex: module, javascript
     * @return VileX
     * @deprecated O parâmetro $absoluto não é mais utilizado.
     * @see adicionarJS
     */
    public function addArquivoJS(string $arquivo_js, bool $absoluto = false, ?string $versao = null, ?string $type = null): VileX
    {
        $arquivo_js = $this->caminho_completo_recurso->execute($arquivo_js, $this->getBaseHtml());
        $this->arquivos_js[] = new Javascript($arquivo_js, $versao, $type);
        return $this;
    }

    /**
     * Adicionar um arquivo JS
     * @param string $arquivo_js
     * @param string|null $versao
     * @param string|null $type
     * @return VileX
     */
    public function adicionarJS(string $arquivo_js, ?string $versao = null, ?string $type = null): VileX
    {
        $arquivo_js = $this->caminho_completo_recurso->execute($arquivo_js, $this->configuracao->getBaseHtml());
        $this->arquivos_js[] = new Javascript($arquivo_js, $versao, $type);
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

            /** @var Javascript $javascript */
            foreach ($this->getArquivosJs() as $javascript) {
                $html .= $javascript->getTagHtml();
            }

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
     * @param bool $absoluto @deprecated
     * @param string|null $versao
     * @param string $media
     * @return VileX
     * @deprecated O parâmetro $absoluto não é mais utilizado.
     * @see adicionarCss
     */
    public function addArquivoCss(string $arquivo_css, bool $absoluto = false, ?string $versao = null, string $media = 'all'): VileX
    {
        $arquivo_css = $this->caminho_completo_recurso->execute($arquivo_css, $this->getBaseHtml());
        $this->arquivos_css[] = new Stylesheet($arquivo_css, $versao, $media);
        return $this;
    }

    /**
     * Adicionar um arquivo CSS
     * @param string $arquivo_css
     * @param string|null $versao
     * @param string $media
     * @return VileX
     */
    public function adicionarCss(string $arquivo_css, ?string $versao = null, string $media = 'all'): VileX
    {
        $arquivo_css = $this->caminho_completo_recurso->execute($arquivo_css, $this->configuracao->getBaseHtml());
        $this->arquivos_css[] = new Stylesheet($arquivo_css, $versao, $media);
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

            /** @var Stylesheet $stylesheet */
            foreach ($this->arquivos_css as $stylesheet) {
                $html .= $stylesheet->getTagHtml();
            }

            $html .= "\n[/ARQUIVOS-CSS]";
        }

        return $html;
    }

    /**
     * Renderizar o conteúdo HTML
     * @param string|null $arquivo_pagina_mestra
     * @return HtmlResponse
     * @throws PaginaMestraInvalidaException
     */
    public function render(?string $arquivo_pagina_mestra = null): HtmlResponse
    {
        $html = '';
        $html .= $this->getTagsCss();
        $html .= $this->getTagsJs();

        /** @var Template $template */
        foreach ($this->templates as $template) {
            $template->addContextoGlobal($this->atributos);
            $html .= $template->render();
        }

        $arquivo_pagina_mestra = $arquivo_pagina_mestra ?? $this->getPaginaMestra();
        if (!empty($arquivo_pagina_mestra)) {
            $pagina_mestra = new PaginaMestra($arquivo_pagina_mestra);
            $pagina_mestra->addContextoGlobal($this->atributos);
            $html = (new MergePaginaMestraComConteudo($pagina_mestra))->merge($html);
        }

        return new HtmlResponse($html);
    }
}