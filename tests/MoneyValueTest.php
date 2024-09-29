<?php
/**
 * Created for money-value
 * Date: 09.12.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Components\MoneyValue;

use InvalidArgumentException;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyValueTest extends TestCase
{

    private MoneyValue $moneyValue;

    protected function setUp(): void
    {
        $this->moneyValue = new MoneyValue(1000);
    }

    public function testGetAmount()
    {
        $this->assertEquals(1000, $this->moneyValue->getAmount());
    }

    public function testEqual()
    {
        $money_1000 = new MoneyValue(1000);
        $money_2000 = new MoneyValue(2000);
        $this->assertTrue($this->moneyValue->equals($money_1000));
        $this->assertFalse($this->moneyValue->equals($money_2000));
    }

    public function testEqualWithMoney()
    {
        $money_1000 = new Money(1000, new Currency('USD'));
        $money_2000 = new Money(2000, new Currency('USD'));
        $this->assertTrue($this->moneyValue->equals($money_1000));
        $this->assertFalse($this->moneyValue->equals($money_2000));
    }

    public function testNotMoneyOrMoneyValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->moneyValue->equals($this);
    }

    public function testGreatThan()
    {
        $money_100 = new MoneyValue(100);
        $money_1000 = new MoneyValue(1000);
        $money_2000 = new MoneyValue(2000);
        $this->assertTrue($this->moneyValue->greatThan($money_100));
        $this->assertFalse($this->moneyValue->greatThan($money_1000));
        $this->assertFalse($this->moneyValue->greatThan($money_2000));
    }

    public function testGreatThanOrEquals()
    {
        $money_100 = new MoneyValue(100);
        $money_1000 = new MoneyValue(1000);
        $money_2000 = new MoneyValue(2000);
        $this->assertTrue($this->moneyValue->greatThanOrEquals($money_100));
        $this->assertTrue($this->moneyValue->greatThanOrEquals($money_1000));
        $this->assertFalse($this->moneyValue->greatThanOrEquals($money_2000));
    }

    public function testLessThan()
    {
        $money_100 = new MoneyValue(100);
        $money_1000 = new MoneyValue(1000);
        $money_2000 = new MoneyValue(2000);
        $this->assertFalse($this->moneyValue->lessThan($money_100));
        $this->assertFalse($this->moneyValue->lessThan($money_1000));
        $this->assertTrue($this->moneyValue->lessThan($money_2000));
    }

    public function testLessThanOrEquals()
    {
        $money_100 = new MoneyValue(100);
        $money_1000 = new MoneyValue(1000);
        $money_2000 = new MoneyValue(2000);
        $this->assertFalse($this->moneyValue->lessThanOrEquals($money_100));
        $this->assertTrue($this->moneyValue->lessThanOrEquals($money_1000));
        $this->assertTrue($this->moneyValue->lessThanOrEquals($money_2000));
    }

    public function testAdd()
    {
        $add = $this->moneyValue->add(new MoneyValue(100));
        $this->assertEquals(1000, $this->moneyValue->getAmount());
        $this->assertEquals(1100, $add->getAmount());
    }

    public function testSubtract()
    {
        $subtract = $this->moneyValue->subtract(new MoneyValue(100));
        $this->assertEquals(1000, $this->moneyValue->getAmount());
        $this->assertEquals(900, $subtract->getAmount());
    }

    public function testMultiply()
    {
        $multiply = $this->moneyValue->multiply(5);
        $this->assertEquals(1000, $this->moneyValue->getAmount());
        $this->assertEquals(5000, $multiply->getAmount());
    }

    public function testMultiplyFloat()
    {
        $multiply = $this->moneyValue->multiply(5.5);
        $this->assertEquals(1000, $this->moneyValue->getAmount());
        $this->assertEquals(5500, $multiply->getAmount());
    }

    public function testDivide()
    {
        $divide = $this->moneyValue->divide(5);
        $this->assertEquals(1000, $this->moneyValue->getAmount());
        $this->assertEquals(200, $divide->getAmount());
    }

    public function testDivideFloat()
    {
        $divide = $this->moneyValue->divide(2.5);
        $this->assertEquals(1000, $this->moneyValue->getAmount());
        $this->assertEquals(400, $divide->getAmount());
    }

    public function testIsZero()
    {
        $this->assertFalse($this->moneyValue->isZero());
        $this->assertTrue((new MoneyValue(0))->isZero());
    }

    public function testIsPositive()
    {
        $this->assertFalse($this->moneyValue->isNegative());
        $this->assertFalse((new MoneyValue(0))->isNegative());
        $this->assertTrue((new MoneyValue(-1))->isNegative());
    }

    public function testIsNegative()
    {
        $this->assertTrue($this->moneyValue->isPositive());
        $this->assertFalse((new MoneyValue(0))->isPositive());
        $this->assertFalse((new MoneyValue(-1))->isPositive());
    }

    public function testToString()
    {
        $this->assertEquals('1000', $this->moneyValue->getAmount());
    }

    public function testConvertToMoney()
    {
        $currency = new Currency('USD');
        $money = $this->moneyValue->convertToMoney($currency);
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(1000, $money->getAmount());
    }

    public function testToFloat()
    {
        $this->assertSame((new MoneyValue(101))->toFloat(), 1.01);
        $this->assertSame((new MoneyValue(101))->toFloat(1), 1.0);
    }

    public function test__ToString()
    {
        $this->assertSame('1000', $this->moneyValue->__toString());
    }

    public function testFromMoney()
    {
        $money = new Money(100500, new Currency('USD'));
        $moneyValue = MoneyValue::fromMoney($money);
        $this->assertInstanceOf(MoneyValue::class, $moneyValue);
        $this->assertEquals(100500, $moneyValue->getAmount());
    }

    public function testJsonSerialize()
    {
        $this->assertSame('{"money":100500}', json_encode(['money' => new MoneyValue(100500)]));
    }

}
