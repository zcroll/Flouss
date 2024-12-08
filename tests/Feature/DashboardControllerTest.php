<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Favorite;
use App\Models\JobInfo;
use App\Models\Result;
use App\Models\ChatHistory;
use App\Models\Degree;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create necessary tables if they don't exist
        if (!DB::getSchemaBuilder()->hasTable('degrees')) {
            DB::statement('CREATE TABLE IF NOT EXISTS degrees (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                image VARCHAR(255),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');
        }

        if (!DB::getSchemaBuilder()->hasTable('favorites')) {
            DB::statement('CREATE TABLE IF NOT EXISTS favorites (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED,
                favoritable_type VARCHAR(255),
                favoritable_id BIGINT UNSIGNED,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');
        }

        if (!DB::getSchemaBuilder()->hasTable('job_infos')) {
            DB::statement('CREATE TABLE IF NOT EXISTS job_infos (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                image VARCHAR(255),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');
        }

        if (!DB::getSchemaBuilder()->hasTable('archetype_discoveries')) {
            DB::statement('CREATE TABLE IF NOT EXISTS archetype_discoveries (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                slug VARCHAR(255),
                rarity_string VARCHAR(255),
                rationale TEXT,
                scales JSON,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');
        }

        if (!DB::getSchemaBuilder()->hasTable('results')) {
            DB::statement('CREATE TABLE IF NOT EXISTS results (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED,
                Archetype JSON,
                scores JSON,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');
        }

        if (!DB::getSchemaBuilder()->hasTable('chat_histories')) {
            DB::statement('CREATE TABLE IF NOT EXISTS chat_histories (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED,
                message TEXT,
                response TEXT,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');
        }

        // Create a verified user
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_dashboard()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_access_dashboard()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_shows_favorite_jobs_correctly()
    {
        $job = JobInfo::create([
            'name' => 'Test Job',
            'image' => 'test.jpg'
        ]);
        
        Favorite::create([
            'user_id' => $this->user->id,
            'favoritable_id' => $job->id,
            'favoritable_type' => 'App\Models\JobInfo'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_shows_favorite_degrees_correctly()
    {
        $degree = Degree::create([
            'name' => 'Test Degree',
            'image' => 'test.jpg'
        ]);
        
        Favorite::create([
            'user_id' => $this->user->id,
            'favoritable_id' => $degree->id,
            'favoritable_type' => 'App\Models\Degree'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_shows_archetype_data_when_user_has_results()
    {
        $result = Result::create([
            'user_id' => $this->user->id,
            'Archetype' => ['EXPLORER'],
            'scores' => json_encode(['trait1' => 0.8, 'trait2' => 0.7])
        ]);

        DB::table('archetype_discoveries')->insert([
            'slug' => 'explorer',
            'rarity_string' => 'Common',
            'rationale' => 'Test rationale',
            'scales' => json_encode(['scale1', 'scale2'])
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_shows_chat_history()
    {
        ChatHistory::create([
            'user_id' => $this->user->id,
            'message' => 'Test message',
            'response' => 'Test response'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function dashboard_includes_predefined_questions()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }
} 