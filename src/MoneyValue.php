<?php
/**
 * Created for money-value
 * Date: 09.12.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Components\MoneyValue;


use JsonSerializable;
use Money\Currency;
use Money\Money;

final class MoneyValue implements JsonSerializable
{

    private int $amount;

    public function __construct(int|float $amount)
    {
        $this->amount = intval(round($amount));
    }

    /**
     * Return amount in minimal atomic units (for 1 USD it will be 100 cents)
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function equals(Money|MoneyValue $moneyOrValue): bool
    {
        return $this->getMoneyOrValueAmount($moneyOrValue) == $this->amount;
    }

    public function greatThan(Money|MoneyValue $moneyOrValue): bool
    {
        return $this->amount > $this->getMoneyOrValueAmount($moneyOrValue);
    }

    public function greatThanOrEquals(Money|MoneyValue $moneyOrValue): bool
    {
        return $this->amount >= $this->getMoneyOrValueAmount($moneyOrValue);
    }

    public function lessThan(Money|MoneyValue $moneyOrValue): bool
    {
        return $this->amount < $this->getMoneyOrValueAmount($moneyOrValue);
    }

    public function lessThanOrEquals(Money|MoneyValue $moneyOrValue): bool
    {
        return $this->amount <= $this->getMoneyOrValueAmount($moneyOrValue);
    }

    public function add(Money|MoneyValue $moneyOrValue): MoneyValue
    {
        return new self($this->amount + $this->getMoneyOrValueAmount($moneyOrValue));
    }

    public function subtract(Money|MoneyValue $moneyOrValue): MoneyValue
    {
        return new self($this->amount - $this->getMoneyOrValueAmount($moneyOrValue));
    }

    public function multiply(int|float $multiplier): MoneyValue
    {
        return new self(round($this->amount * $multiplier));
    }

    public function divide(int|float $divisor): MoneyValue
    {
        return new self(round($this->amount / $divisor));
    }

    public function isZero(): bool
    {
        return $this->amount == 0;
    }

    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    public function __toString(): string
    {
        return (string)$this->amount;
    }

    public function convertToMoney(Currency $currency): Money
    {
        return new Money($this->amount, $currency);
    }

    public function toFloat(int $precision = 2): float
    {
        return round($this->amount / 100, $precision);
    }

    private function getMoneyOrValueAmount(Money|MoneyValue $moneyOrValue): int
    {
        return (int)$moneyOrValue->getAmount();
    }

    public static function fromMoney(Money $money): self
    {
        return new self((int)$money->getAmount());
    }

    public static function fromIntOrNull($value): ?self
    {
        if (is_null($value) || $value === '') {
            return null;
        }
        return new self($value);
    }

    public function jsonSerialize(): int
    {
        return $this->amount;
    }
}