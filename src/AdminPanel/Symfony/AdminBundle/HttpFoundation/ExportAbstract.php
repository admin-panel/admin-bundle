<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\HttpFundation;

use AdminPanel\Component\DataGrid\DataGridViewInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

abstract class ExportAbstract extends Response
{
    /**
     * @var \AdminPanel\Component\DataGrid\DataGridViewInterface
     */
    protected $datagrid;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var TranslatorInterface|null
     */
    protected $translator;

    /**
     * @param \AdminPanel\Component\DataGrid\DataGridViewInterface $datagrid
     * @param $filename
     * @param int $status
     * @param array $headers
     * @param Translator $translator
     */
    public function __construct(
        DataGridViewInterface $datagrid,
        $filename,
        $status = 200,
        $headers = [],
        TranslatorInterface $translator = null
    ) {
        parent::__construct('', $status, $headers);

        $this->translator = $translator;
        $this->filename = $filename;
        $this->datagrid = $datagrid;
        $this->setData();
    }

    /**
     * @return \AdminPanel\Component\DataGrid\DataGridViewInterface
     */
    public function getDataGrid()
    {
        return $this->datagrid;
    }

    /**
     * Return filename without file extension.
     * File extension should be determined by class that extends ExportAbstract.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * @return ExportAbstract
     */
    abstract public function setData();
}
