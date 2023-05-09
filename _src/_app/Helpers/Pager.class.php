<?php

/**
 * Pager.class [ Helper ]
 * Realização e gestão e a paginação dos resultados do sistema!
 * @copyright (c) 2020, Renato S. Botacim - Infire Soluções Digitais
 */
class Pager {

    /**
     *  Define o pager
     */
    private $Page;
    private $Limit;
    private $Offset;

    /**
     * Realiza a leitura
     */
    private $Tablela;
    private $Termos;
    private $Places;

    /**
     * Define a paginator
     */
    private $Rows;
    private $Link;
    private $MaxLinks;
    private $Frist;
    private $Last;

    /**
     * Rendereiza o paginator
     */
    private $Paginator;

    function __construct($Link, $Frist = null, $Last = null, $MaxLinks = null) {
        $this->Link = (string) $Link;
        $this->Frist = ((string) $Frist ? $Frist : 'Primeira Página');
        $this->Last = ((string) $Last ? $Last : 'Última Página');
        $this->MaxLinks = ((int) $MaxLinks ? $MaxLinks : 5);
    }

    public function ExePager($Page, $Limit) {
        $this->Page = ((int) $Page ? $Page : 1);
        $this->Limit = (int) $Limit;
        $this->Offset = ($this->Page * $this->Limit) - $this->Limit;
    }

    public function ReturnPage() {
        if ($this->Page > 1):
            $nPage = $this->Page - 1;
//            header("Location: {$this->Link}{$nPage}");
            return $nPage;
        endif;
    }

    function getPage() {
        return $this->Page;
    }

    function getLimit() {
        return $this->Limit;
    }

    function getOffset() {
        return $this->Offset;
    }

    public function ExePaginator($Tabela, $Termos = null, $ParseString = null) {
        $this->Tablela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        $this->Places = (string) $ParseString;
        $this->getSyntax();
    }

    public function ExePaginatorRows($rows) {
        $this->getSyntax($rows);
    }

    public function getPaginator() {
        return $this->Paginator;
    }

    //PRIVATe
    private function getSyntax($rows = null) {
        if (!isset($rows)):
            $read = new Read;
            $read->ExeRead($this->Tablela, $this->Termos, $this->Places);
            $this->Rows = $read->getRowCount();
        else :
            $this->Rows = $rows;
        endif;

        if ($this->Rows > $this->Limit):
            $Paginas = ceil($this->Rows / $this->Limit);
            $MaxLinks = $this->MaxLinks;
            $this->Paginator = "<ul class=\"paginator pagination pagination-md justify-content-center mt-3\">";
            $this->Paginator .= "<li class=\"page-item\"><a title=\"{$this->Frist}\" class=\"page-link\" href=\"{$this->Link}1\">{$this->Frist}</a></li>";
            for ($IPag = $this->Page - $MaxLinks; $IPag <= $this->Page - 1; $IPag ++):
                if ($IPag >= 1):
                    $this->Paginator .= "<li class=\"page-item\" ><a title=\"Página{$IPag}\" class=\"page-link\" href=\"{$this->Link}{$IPag}\">{$IPag}</a></li>";
                endif;
            endfor;
            $this->Paginator .= "<li class=\"page-item active\"><span class=\"page-link\">{$this->Page}</span></li>";
            for ($DPag = $this->Page + 1; $DPag <= $this->Page + $MaxLinks; $DPag ++):
                if ($DPag <= $Paginas):
                    $this->Paginator .= "<li class=\"page-item\"><a title=\"Página{$DPag}\" class=\"page-link\" href=\"{$this->Link}{$DPag}\">{$DPag}</a></li>";
                endif;
            endfor;
            $this->Paginator .= "<li class=\"page-item\"><a title=\"{$this->Last}\" class=\"page-link\" href=\"{$this->Link}{$Paginas}\">{$this->Last}</a></li>";
            $this->Paginator .= "</ul>";
        endif;
    }

}
