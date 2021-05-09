<?php

namespace App\Tests;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostTest extends KernelTestCase
{
    protected $validator;
    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $this->validator = self::$container->get('validator');
    }

    public function getEntityPost(): Post
    {
        $user = (new User)
            ->setUsername('aaaaa')
            ->setPassword('aaaaa');

        $category = (new Category)
                    ->setName('aaa')
                    ->setSlug('aaa');
        return (new Post)
                ->setTitle('zezrzr')
                ->setArticle('zzeazeaz')
                ->setCategory($category)
                ->setUser($user);

    }
    public function testPostEntity(): void
    {

        $error = $this->validator->validate($this->getEntityPost());

        $this->assertCount(0,$error);
    }
    public function testTitlePostIsBlank(): void
    {

        $error = $this->validator->validate($this->getEntityPost()->setTitle(''));

        $this->assertCount(1,$error);
    }
    public function testArticlePostIsBlank(): void
    {

        $error = $this->validator->validate($this->getEntityPost()->setArticle(''));

        $this->assertCount(1,$error);
    }
}