<?php

namespace Osem\Commenist\Contracts;

/**
 * Preprocessor class Interface
 *
 * Interface ICommentPreprocessor
 * @package Osem\Commenist\Contracts
 */
interface ICommentPreprocessor
{
    public function process($object);
}