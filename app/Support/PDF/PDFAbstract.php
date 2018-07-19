<?php

/**

 *
 
 


 
 *

 */

namespace FI\Support\PDF;

abstract class PDFAbstract implements PDFInterface
{
    protected $paperSize;

    protected $paperOrientation;

    public function __construct()
    {
        $this->paperSize = config('fi.paperSize') ?: 'letter';
        $this->paperOrientation = config('fi.paperOrientation') ?: 'portrait';
    }

    public function setPaperSize($paperSize)
    {
        $this->paperSize = $paperSize;
    }

    public function setPaperOrientation($paperOrientation)
    {
        $this->paperOrientation = $paperOrientation;
    }
}