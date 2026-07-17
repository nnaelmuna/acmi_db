<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SponsoredBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SponsoredBannerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
        ]);
    }

    public function test_can_access_sponsored_banner_page()
    {
        $response = $this->actingAs($this->user)->get('/sponsored-banner');
        $response->assertStatus(200);
        $response->assertSee('Banner Sponsored');
    }

    public function test_can_store_sponsored_banner()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('banner.jpg');

        $response = $this->actingAs($this->user)->post('/sponsored-banner', [
            'title' => 'Test Banner',
            'link_sponsored' => 'https://example.com',
            'image' => $image,
            'start_date' => '2026-07-17',
            'end_date' => '2026-07-24',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sponsored_banners', [
            'title' => 'Test Banner',
            'link_sponsored' => 'https://example.com',
            'start_date' => '2026-07-17',
            'end_date' => '2026-07-24',
            'is_forever' => false,
            'status' => 'published',
        ]);
    }

    public function test_can_store_sponsored_banner_forever()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('banner.jpg');

        $response = $this->actingAs($this->user)->post('/sponsored-banner', [
            'title' => 'Test Forever Banner',
            'link_sponsored' => 'https://example.com',
            'image' => $image,
            'start_date' => '2026-07-17',
            'is_forever' => 'on',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sponsored_banners', [
            'title' => 'Test Forever Banner',
            'link_sponsored' => 'https://example.com',
            'start_date' => '2026-07-17',
            'end_date' => null,
            'is_forever' => true,
            'status' => 'published',
        ]);
    }

    public function test_can_store_sponsored_banner_pdf()
    {
        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->user)->post('/sponsored-banner', [
            'title' => 'Test PDF Banner',
            'link_sponsored' => 'https://example.com',
            'image' => $pdf,
            'start_date' => '2026-07-17',
            'end_date' => '2026-07-24',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sponsored_banners', [
            'title' => 'Test PDF Banner',
            'link_sponsored' => 'https://example.com',
            'start_date' => '2026-07-17',
            'end_date' => '2026-07-24',
            'is_forever' => false,
            'status' => 'published',
        ]);
    }

    public function test_can_update_sponsored_banner_excluding_dates_and_impressions()
    {
        Storage::fake('public');

        $banner = SponsoredBanner::create([
            'title' => 'Original Title',
            'link_sponsored' => 'https://original.com',
            'image' => 'sponsored-banners/old.jpg',
            'start_date' => '2026-07-17',
            'end_date' => '2026-07-24',
            'is_forever' => false,
            'status' => 'published',
            'impressions' => 10,
        ]);

        $newImage = UploadedFile::fake()->image('new_banner.jpg');

        $response = $this->actingAs($this->user)->put("/sponsored-banner/{$banner->id}", [
            'title' => 'Updated Title',
            'link_sponsored' => 'https://updated.com',
            'image' => $newImage,
            'status' => 'draft',
            // Try to pass these values; they should be ignored by update
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-08',
            'is_forever' => true,
            'impressions' => 999,
        ]);

        $response->assertRedirect();

        // Check DB to make sure editable fields changed, but dates & impressions did not
        $this->assertDatabaseHas('sponsored_banners', [
            'id' => $banner->id,
            'title' => 'Updated Title',
            'link_sponsored' => 'https://updated.com',
            'start_date' => '2026-07-17', // Unchanged
            'end_date' => '2026-07-24',   // Unchanged
            'is_forever' => false,        // Unchanged
            'impressions' => 10,           // Unchanged
            'status' => 'draft',
        ]);
    }

    public function test_can_delete_restore_force_delete_banner()
    {
        $banner = SponsoredBanner::create([
            'title' => 'Banner to delete',
            'link_sponsored' => 'https://delete-me.com',
            'image' => 'sponsored-banners/del.jpg',
            'start_date' => '2026-07-17',
            'status' => 'published',
        ]);

        // Destroy
        $response = $this->actingAs($this->user)->delete("/sponsored-banner/{$banner->id}");
        $response->assertRedirect('/sponsored-banner?status=trash');
        $this->assertSoftDeleted('sponsored_banners', ['id' => $banner->id]);

        // Restore
        $response = $this->actingAs($this->user)->post("/sponsored-banner/{$banner->id}/restore");
        $response->assertRedirect('/sponsored-banner?status=published');
        $this->assertDatabaseHas('sponsored_banners', ['id' => $banner->id, 'deleted_at' => null]);

        // Force delete
        $banner->delete(); // Soft delete again
        $response = $this->actingAs($this->user)->delete("/sponsored-banner/{$banner->id}/force-delete");
        $response->assertRedirect('/sponsored-banner?status=trash');
        $this->assertDatabaseMissing('sponsored_banners', ['id' => $banner->id]);
    }
}
