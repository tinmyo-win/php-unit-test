<?php

namespace Tests\Unit;

use App\Order;
use App\Product;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{

    /** @test */
    public function an_order_consists_of_products()
    {
        $order = $this->createOrderWithProducts();

        $this->assertCount(2, $order->products());
    }

    /** @test */
    public function an_order_can_determine_the_total_cost_of_all_its_products()
    {
        $order = $this->createOrderWithProducts();

        $this->assertEquals(64, $order->total());
    }

    protected function createOrderWithProducts()
    {
        $order = new Order;

        $product = new Product("Fallout 4", 59);
        $product2 = new Product("Pillow", 5);

        $order->add($product);
        $order->add($product2);

        return $order;
    }
}
