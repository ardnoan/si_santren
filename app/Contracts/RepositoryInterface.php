<?php

namespace App\Contracts;

/**
 * Interface RepositoryInterface
 * Implementasi: Interface Segregation Principle
 * Kontrak untuk semua repository
 */
interface RepositoryInterface
{
    /**
     * Get all records
     */
    public function getAll();

    /**
     * Find record by ID
     */
    public function findById(int $id);

    /**
     * Create new record
     */
    public function create(array $data);

    /**
     * Update existing record
     */
    public function update(int $id, array $data);

    /**
     * Delete record
     */
    public function delete(int $id): bool;

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15);
}