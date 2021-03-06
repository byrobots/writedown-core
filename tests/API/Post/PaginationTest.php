<?php

namespace Tests\API\Post;

use Tests\TestCase;

class PaginationTest extends TestCase
{
    /**
     * The index request for posts should be paginated.
     */
    public function testIsPaginated()
    {
        // Create 20 posts
        for ($i = 0; $i < 20; $i++) {
            $this->resources->post();
        }

        // Request an index, with 10 per index
        $result = $this->writedown->getService('api')->post()->index(['pagination' => [
            'per_page'     => 10,
            'current_page' => 1,
        ]]);

        // We should have received 10 posts
        $this->assertTrue($result['success']);
        $this->assertEquals(10, count($result['data']));

        // And meta data should be available telling us more information
        $this->assertArrayHasKey('meta', $result);

        $meta = $result['meta'];
        $this->assertEquals(10, $meta['per_page']);
        $this->assertEquals(1, $meta['current_page']);
        $this->assertEquals(2, $meta['total_pages']);
    }

    /**
     * Subsequent pages can be requested.
     */
    public function testPageRequests()
    {
        // Create 15 posts
        for ($i = 0; $i < 15; $i++) {
            $this->resources->post();
        }

        // Request an index, with 10 per index
        $result = $this->writedown->getService('api')->post()->index(['pagination' => [
            'per_page'     => 10,
            'current_page' => 2,
        ]]);

        $this->assertTrue($result['success']);
        $this->assertEquals(5, count($result['data']));
        $this->assertEquals(10, $result['meta']['per_page']);
        $this->assertEquals(2, $result['meta']['current_page']);
        $this->assertEquals(2, $result['meta']['total_pages']);
    }

    /**
     * It should be possible to disable pagination.
     */
    public function testDisablePagination()
    {
        // Create the the max item of posts, plus one extra.
        $postCount = env('MAX_ITEMS') + 1;
        for ($i = 0; $i < $postCount; $i++) {
            $this->resources->post();
        }

        // Request an index with empty pagination data
        $result = $this->writedown->getService('api')->post()->index(['pagination' => []]);

        // Check the response - all 15 tags should be returned
        $this->assertTrue($result['success']);
        $this->assertEquals($postCount, count($result['data']));
        $this->assertEquals($postCount, $result['meta']['per_page']);
        $this->assertEquals(1, $result['meta']['current_page']);
        $this->assertEquals(1, $result['meta']['total_pages']);
    }
}
