<?php

namespace Tests;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use ByRobots\WriteDown\Database\Entities\Post;
use ByRobots\WriteDown\Database\Entities\User;
use ByRobots\WriteDown\Slugs\Slugger;

class CreatesResources
{
    /**
     * Fake data generator.
     *
     * @var \Faker\Generator
     */
    public $faker;

    /**
     * The database.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $db;

    /**
     * Set-up.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $db
     * @param \Faker\Generator                     $faker;
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $db, Generator $faker)
    {
        $this->db    = $db;
        $this->faker = $faker;
    }

    /**
     * Persist an object.
     *
     * @param mixed $entity
     *
     * @return void
     */
    public function persist($entity)
    {
        $this->db->persist($entity);
    }

    /**
     * Perform a flush operation on the EntityManager.
     *
     * @return void
     */
    public function flush()
    {
        $this->db->flush();
    }

    /**
     * Create a test post.
     *
     * @return \ByRobots\WriteDown\Database\Entities\Post
     */
    public function post()
    {
        $post    = new Post;
        $slugger = new Slugger;

        $post->title      = $this->faker->sentence;
        $post->slug       = $slugger->slug($post->title);
        $post->body       = $this->faker->paragraph;
        $post->publish_at = new \DateTime('now');
        $this->persist($post);
        $this->flush();

        return $post;
    }

    /**
     * Create a test user.
     *
     * @return \ByRobots\WriteDown\Database\Entities\User
     */
    public function user()
    {
        $user = new User;

        $user->email    = $this->faker->email;
        $user->password = $this->faker->word;
        $this->persist($user);
        $this->flush();

        return $user;
    }
}
