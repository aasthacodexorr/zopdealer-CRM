<?php

namespace App\Repositories;

/***
 *  CRUDRepositoryInterface This Interface defines the CRUD methods in the repository.
 */
interface CRUDRepositoryInterface
{
    
    /**
     * create This method creates new entry for the entity.
     *
     * @param  mixed $data
     * @return array
     */
    public function create($data): array;
    
    /**
     * update This method updates the entry for the entity.
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return array
     */
    public function update($id, $data): array;
    
    /**
     * delete This method deletes the entity.
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id);
    
    /**
     * show This method shows the entity.
     *
     * @param  mixed $id
     * @return array
     */
    public function show($id): ?array;
}
