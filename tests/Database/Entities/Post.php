<?php

namespace Tests\Entities\Database;

use Tests\TestCase;

class Post extends TestCase
{
    /**
     * created_at and updated_at should both be set before the entity is
     * persisted.
     */
    public function testTimestampsSetOnPersist()
    {
        // Create a new post
        $post = $this->resources->post();

        // Make sure dates have been set correctly
        $this->assertNotNull($post->created_at);
        $this->assertNotNull($post->updated_at);
        $this->assertEquals(
            $post->created_at->format('Y-m-d H:i:s'),
            $post->updated_at->format('Y-m-d H:i:s')
        );
    }

    /**
     * The updated_at timestamp should be updated when the entity is updated.
     */
    public function testTimestampsOnUpdate()
    {
        // Create a new post
        $post = $this->resources->post();
        usleep(1100000); // Allow one and a bit seconds to pass so the updated
        // timestamp will be different to created_at.

        // Update it
        $post->title = $this->resources->faker->sentence();
        $this->resources->flush();

        // Make sure dates have been set and only updated_at has been changed
        $this->assertGreaterThan(
            $post->created_at->format('Y-m-d H:i:s'),
            $post->updated_at->format('Y-m-d H:i:s')
        );
    }
}
