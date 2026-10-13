<?php

namespace Tenant\app\Interfaces;

/***
 * ExternalReviewInterface defines the structure for an external reviews to be store and published.
 */
interface ExternalReviewInterface
{

    /**
     * authorize
     *
     * @param  array $data
     * @return array
     */
    function authorize(array $data = []): ?array;

    /**
     * saveConfigurations
     *
     * @param  array $data
     * @return void
     */
    function saveConfigurations(array $data = []): void;

    /**
     * fetch    This method fetchs the reviews from the external sources and saves them in the database.
     *
     * @param  array $options
     * @return void
     */
    function fetch(array $options = []): void;
    
    /**
     * getReviewURI
     *
     * @param  array $options
     * @return string
     */
    function getReviewURI(array $options = []): ?string;
    
    /**
     * revoke
     *
     * @param  mixed $options
     * @return array
     */
    function revoke(array $options = []): array;
}
