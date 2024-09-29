<?php
/**
 * Created for money-value
 * Date: 09.12.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Components\MoneyValue;


use InvalidArgumentException;
use JsonSerializable;
use Money\Currency;
use Money\Money;

final class MoneyValue implements JsonSerializable
{

    private int $amount;

    /**
     * @param int|float $amount
     */
    public function __construct($amount)
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

    /**
     * @param Money|MoneyValue $moneyOrValue
     * @return bool
     */
    public function equals($moneyOrValue): bool
    {
        return $this->getMoneyOrValueAmount($moneyOrValue) == $this->amount;
    }

    /**
     * @param Money|MoneyValue $moneyOrValue
     * @return bool
     */
    public function greatThan($moneyOrValue): bool
    {
        return $this->amount > $this->getMoneyOrValueAmount($moneyOrValue);
    }

    /**
     * @param Money|MoneyValue $moneyOrValue
     * @return bool
     */
    public function greatThanOrEquals($moneyOrValue): bool
    {
        return $this->amount >= $this->getMoneyOrValueAmount($moneyOrValue);
    }

    /**
     * @param Money|MoneyValue $moneyOrValue
     * @return bool
     */
    public function lessThan($moneyOrValue): bool
    {
        return $this->amount < $this->getMoneyOrValueAmount($moneyOrValue);
    }

    /**
     * @param Money|MoneyValue $moneyOrValue
     * @return bool
     */
    public function lessThanOrEquals($moneyOrValue): bool
    {
        return $this->amount <= $this->getMoneyOrValueAmount($moneyOrValue);
    }

    /**
     * @param Money|MoneyValue $moneyOrValue
     * @return MoneyValue
     */
    public function add($moneyOrValue): MoneyValue
    {
        return new self($this->amount + $this->getMoneyOrValueAmount($moneyOrValue));
    }

    /**
     * @param Money|MoneyValue $moneyOrValue
     * @return MoneyValue
     */
    public function subtract($moneyOrValue): MoneyValue
    {
        return new self($this->amount - $this->getMoneyOrValueAmount($moneyOrValue));
    }

    /**
     * @param int|float $multiplier
     * @return MoneyValue
     */
    public function multiply(float $multiplier): MoneyValue
    {
        return new self(round($this->amount * $multiplier));
    }

    /**
     * @param int|float $divisor
     * @return MoneyValue
     */
    public function divide($divisor): MoneyValue
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
        return (string) $this->amount;
    }

    public function convertToMoney(Currency $currency): Money
    {
        return new Money($this->amount, $currency);
    }

    public function toFloat(int $precision = 2): float
    {
        return round($this->amount  / 100, $precision);
    }

    private function getMoneyOrValueAmount($moneyOrValue)
    {
        if ($moneyOrValue instanceof Money || $moneyOrValue instanceof MoneyValue) {
            return $moneyOrValue->getAmount();
        }
        throw new InvalidArgumentException('Object is not a Money or MoneyValue');
    }

    public static function fromMoney(Money $money): self
    {
        return new self($money->getAmount());
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