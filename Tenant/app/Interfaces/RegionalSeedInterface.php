<?php

namespace Tenant\app\Interfaces;

/***
 * RegionalSeedInterface this defines the structure of documents and settings to be seeded on regional basis.
 */
interface RegionalSeedInterface
{   
    /**
     * executeSeed  This selects the documents from the array to push for a particular region. 
     *
     * @param  array $documentMethod List of document methods to seed.
     * @return void
     */
    public function executeSeed(array $documentMethods) : void;
}
