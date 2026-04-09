<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Service;
use App\Services\SyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class SyncServicePerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_service_performance()
    {
        // Seed 10 categories
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = Category::create([
                'uuid' => (string) Str::uuid(),
                'name' => "Category $i",
            ]);
        }

        // Prepare 50 service records with category_uuid
        $serviceCount = 50;
        $serviceRecords = [];
        for ($i = 0; $i < $serviceCount; $i++) {
            $cat = $categories[$i % 10];
            $serviceRecords[] = [
                'uuid' => (string) Str::uuid(),
                'name' => "Service $i",
                'url' => "https://example.com/$i",
                'category_uuid' => $cat->uuid,
            ];
        }

        $changes = [
            'services' => $serviceRecords,
        ];

        $syncService = new SyncService();

        DB::enableQueryLog();
        $startTime = microtime(true);

        // Accessing protected method for testing purposes
        $reflection = new \ReflectionClass(SyncService::class);
        $method = $reflection->getMethod('processServerChanges');
        $method->setAccessible(true);
        $method->invokeArgs($syncService, [$changes]);

        $endTime = microtime(true);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $executionTime = ($endTime - $startTime) * 1000; // in milliseconds
        $queryCount = count($queries);

        // Calculate expected queries:
        // 1. One query for whereIn('uuid', $categoryUuids)
        // 2. For each service, two queries in upsertLocal:
        //    a. Select existing record by uuid
        //    b. Create new record (since database is refreshed)
        $expectedQueryCount = 1 + (2 * $serviceCount);

        $this->assertLessThanOrEqual($expectedQueryCount, $queryCount, "Query count is higher than expected. N+1 issue might still exist.");

        echo "\nPerformance Results:\n";
        echo "Query Count: $queryCount\n";
        echo "Execution Time: " . round($executionTime, 2) . " ms\n";
    }
}
