<?php

namespace Tests\Feature;

use Tests\TestCase;

class GuluRentalsPageTest extends TestCase
{
    public function test_gulu_rentals_page_is_publicly_available(): void
    {
        $this->get('/admin/homeland-estates')
            ->assertOk()
            ->assertSee('Homeland Estates')
            ->assertSee('Find your place in Gulu City.')
            ->assertSee('Prime Residential Plot')
            ->assertSee('Furnished City Apartment')
            ->assertSee('Laroo')
            ->assertSee('Pece');
    }

    public function test_marketplace_is_separate_from_the_main_home_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Omney Safari')
            ->assertDontSee('Find your place in Gulu City.');
    }

    public function test_property_list_can_be_filtered_by_purpose(): void
    {
        $this->get('/admin/homeland-estates?purpose=rent')
            ->assertOk()
            ->assertSee('Furnished City Apartment')
            ->assertDontSee('Prime Residential Plot');
    }

    public function test_property_detail_page_is_available(): void
    {
        $this->get('/admin/homeland-estates/properties/1')
            ->assertOk()
            ->assertSee('Prime Residential Plot')
            ->assertSee('UGX 28,000,000')
            ->assertSee('Create account to continue');

        $this->get('/admin/homeland-estates/properties/999')->assertNotFound();
    }

    public function test_gulu_rentals_route_is_named(): void
    {
        $this->assertSame('/admin/homeland-estates', route('gulu-rentals', absolute: false));
    }

    public function test_old_homeland_estates_urls_redirect_to_the_new_location(): void
    {
        $this->get('/admin/v1')
            ->assertRedirect('/admin/homeland-estates');

        $this->get('/admin/v1/properties/1')
            ->assertRedirect('/admin/homeland-estates/properties/1');
    }

    public function test_buy_and_rental_pages_show_the_correct_listings(): void
    {
        $this->get('/admin/homeland-estates/buy')
            ->assertOk()
            ->assertSee('Prime Residential Plot')
            ->assertDontSee('Furnished City Apartment');

        $this->get('/admin/homeland-estates/rentals')
            ->assertOk()
            ->assertSee('Furnished City Apartment')
            ->assertDontSee('Prime Residential Plot');
    }

    public function test_information_pages_are_available(): void
    {
        $this->get('/admin/homeland-estates/about')
            ->assertOk()
            ->assertSee('A trusted property partner for Gulu City.');

        $this->get('/admin/homeland-estates/contact')
            ->assertOk()
            ->assertSee('0393 217263');

        $this->get('/admin/homeland-estates/list-property')
            ->assertOk()
            ->assertSee('List your land, home, or rental.');
    }
}
