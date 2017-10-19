<?php
namespace DOTW\Repositories;

interface ICurrencyRepository {

	public function addCurrency(int $code, string $shortcut, string $name): bool;

	public function addBulkOfCurrencies(array $currencies): bool;

	public function getCurrenciesByID(int $id);

	public function getCurrencyByCode(int $code);

	public function getCurrenciesByShortcut(string $shortcut): array;

	public function getCurrenciesByName(string $name): array;

	public function getAllCurrencies(): array;

}