<?php

namespace App\Tests;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    protected $validator;
    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $this->validator = self::$container->get('validator');
    }

    public function getEntiyCategory(): Category
    {
        return (new Category)
                    ->setName('aaa')
                    ->setSlug('aaa');
    }
    public function testNameCategory(): void
    {

        $category = (new Category)
                    ->setName('aaa')
                    ->setSlug('aaa');

        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $validator = self::$container->get('validator');

        $error = $validator->validate($category);

        $this->assertCount(0,$error);
    }
    public function testNameCategoryIsBlank(): void
    {
        $error = $this->validator->validate($this->getEntiyCategory()->setName(''));

        $this->assertCount(1,$error);
    }
}
