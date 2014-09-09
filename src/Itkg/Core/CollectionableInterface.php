<?php

namespace Itkg\Core;

interface CollectionableInterface
{
    /**
     * Id getter
     *
     * Needed to compute key for collections
     *
     * @return mixed
     */
    public function getId();
}
