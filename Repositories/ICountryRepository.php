<?php
namespace DOTW\Repositories;

interface ICountryRepository {

	public function addCountry(int $code, string $name): bool;

	public function addBulkOfCountries(array $countries): bool;

	public function getCountryByID(int $id);

	public function getCountryByCode(int $code);

	public function getCountryByName(string $name): array;

	public function getAllCountries(): array;

}