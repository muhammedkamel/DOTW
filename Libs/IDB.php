<?php
namespace DOTW\Libs;

interface IDB {

	// public function table(string $table): string;

	public function selectOne(string $query, array $bindings = []): object;

	public function select(string $query, array $bindings = []): array;

	public function insert(string $query, array $bindings = []): boolean;

	public function update(string $query, array $bindings = []): boolean;

	public function delete(string $query, array $bindings = []): boolean;

	public function affectedRows(): int;

	// public function groupBy(array $fields): string;

	// public function orderBy(array $fields, $type = 'ASC'): string;

}