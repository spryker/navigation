<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Bundles\Wishlist\tests\Unit\Spryker\Zed\Wishlist\Business\Storage;

use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Wishlist\Business\Storage\InMemory;

class InMemoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAddItemToExisting()
    {
        $wishlistTransfer = new WishlistTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistTransfer->addItem($wishlistItem);

        $productFacadeMock = $this->createProductFacadeConcreteProductMock();

        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->addItems($wishlistChangeTransfer);

        $wishlistItem = $wishlist->getItems()[0];

        $this->assertEquals(2, $wishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testAddNewItem()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
        $wishlistTransfer = new WishlistTransfer();
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->addItems($wishlistChangeTransfer);

        $wishlistItem = $wishlist->getItems()[0];

        $this->assertEquals(1, $wishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testReduceExistingItem()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
        $wishlistTransfer = new WishlistTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(10);
        $wishlistTransfer->addItem($wishlistItem);
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->decreaseItems($wishlistChangeTransfer);

        $wishlistItem = $wishlist->getItems()[0];

        $this->assertEquals(9, $wishlistItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testReduceIfLastExisting()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
        $wishlistTransfer = new WishlistTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistTransfer->addItem($wishlistItem);
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->decreaseItems($wishlistChangeTransfer);

        $this->assertCount(0, $wishlist->getItems());
    }

    /**
     * @return void
     */
    public function testRemoveItem()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
        $wishlistTransfer = new WishlistTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(10);
        $wishlistTransfer->addItem($wishlistItem);
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(0);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->decreaseItems($wishlistChangeTransfer);

        $this->assertCount(0, $wishlist->getItems());
    }

    /**
     * @return void
     */
    public function testIncreaseItem()
    {
        $productFacadeMock = $this->createProductFacadeConcreteProductMock();
        $wishlistTransfer = new WishlistTransfer();
        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistTransfer->addItem($wishlistItem);
        $inMemory = new InMemory($wishlistTransfer, $productFacadeMock);

        $wishlistChangeTransfer = new WishlistChangeTransfer();

        $wishlistItem = new ItemTransfer();
        $wishlistItem->setGroupKey(123);
        $wishlistItem->setQuantity(1);
        $wishlistChangeTransfer->addItem($wishlistItem);

        $wishlist = $inMemory->increaseItems($wishlistChangeTransfer);

        $wishlistItem = $wishlist->getItems()[0];

        $this->assertEquals(2, $wishlistItem->getQuantity());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createProductFacadeConcreteProductMock()
    {
        $concreateProductTransfer = new ConcreteProductTransfer();
        $concreateProductTransfer->setIdProductAbstract(1);

        $productFacadeMock = $this
            ->getMockBuilder('Spryker\Zed\Product\Business\ProductFacade')
            ->disableOriginalConstructor()
            ->getMock();

        $productFacadeMock->expects($this->any())->method('getConcreteProduct')
            ->will($this->returnValue($concreateProductTransfer));

        return $productFacadeMock;
    }

}
