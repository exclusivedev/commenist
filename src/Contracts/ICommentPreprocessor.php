<?php

namespace ExclusiveDev\Commenist\Contracts;

/**
 * Preprocessor class Interface
 *
 * Interface ICommentPreprocessor
 * @package ExclusiveDev\Commenist\Contracts
 */
interface ICommentPreprocessor
{
    public function process($object);
}