<?php
namespace DOTW\Repositories;

interface ICityRepository {

	public function addCity(int $code, string $name, int $country_code): bool;

	public function addBulkOfCities(array $cities): bool;

	public function getCityByID(int $id);

	public function getCityByCode(int $code);

	public function getCitiesByName(string $name): array;

	public function getCitiesByCountryCode(int $country_code): array;

	public function getAllCities(): array;

}