<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GuluRentalsController extends Controller
{
    public function index(): View
    {
        $purpose = request()->string('purpose')->toString();

        return $this->catalogue($purpose);
    }

    public function buy(): View
    {
        return $this->catalogue('sale');
    }

    public function rentals(): View
    {
        return $this->catalogue('rent');
    }

    public function about(): View
    {
        return view('admin.page', [
            'eyebrow' => 'Local property expertise',
            'title' => 'A trusted property partner for Gulu City.',
            'description' => 'Homeland Estates connects buyers, renters, landlords, and property owners with verified opportunities across Northern Uganda.',
            'highlights' => ['Locally focused listings', 'Clear property information', 'Secure enquiries and payments'],
        ]);
    }

    public function contact(): View
    {
        return view('admin.page', [
            'eyebrow' => 'Talk to our team',
            'title' => 'Let us help with your next property move.',
            'description' => 'Ask about a listing, request a viewing, or speak with our Gulu City property team.',
            'highlights' => ['Phone: 0393 217263', 'Email: ogeof49@gmail.com', 'Location: Gulu City, Uganda'],
        ]);
    }

    public function listProperty(): View
    {
        return view('admin.page', [
            'eyebrow' => 'For property owners',
            'title' => 'List your land, home, or rental.',
            'description' => 'Reach serious buyers and renters with a professionally presented, locally promoted Homeland Estates listing.',
            'highlights' => ['Property verification', 'Professional listing support', 'Buyer and renter enquiries'],
        ]);
    }

    private function catalogue(string $purpose): View
    {
        $properties = collect($this->properties())
            ->when(in_array($purpose, ['sale', 'rent'], true), function ($properties) use ($purpose) {
                return $properties->where('purpose', $purpose);
            });

        return view('admin.v1', [
            'properties' => $properties,
            'purpose' => $purpose,
        ]);
    }

    public function show(int $property): View
    {
        $listing = collect($this->properties())->firstWhere('id', $property);

        if ($listing === null) {
            throw new NotFoundHttpException;
        }

        return view('admin.property', ['property' => $listing]);
    }

    /**
     * @return array<int, array{id: int, title: string, purpose: string, price: string, suffix: string, location: string, size: string, type: string, beds: int|null, description: string, image: string}>
     */
    private function properties(): array
    {
        return [
            ['id' => 1, 'title' => 'Prime Residential Plot', 'purpose' => 'sale', 'price' => 'UGX 28,000,000', 'suffix' => '', 'location' => 'Laroo, Gulu City', 'size' => '50 × 100 ft', 'type' => 'Land', 'beds' => null, 'description' => 'Titled residential land in a fast-growing neighbourhood with road access and utilities nearby.', 'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=85'],
            ['id' => 2, 'title' => 'Modern Family Home', 'purpose' => 'sale', 'price' => 'UGX 185,000,000', 'suffix' => '', 'location' => 'Pece-Laroo, Gulu City', 'size' => '25 decimals', 'type' => 'House', 'beds' => 4, 'description' => 'A spacious contemporary family home with secure parking, a private compound, and convenient access to central Gulu.', 'image' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=85'],
            ['id' => 3, 'title' => 'Furnished City Apartment', 'purpose' => 'rent', 'price' => 'UGX 850,000', 'suffix' => '/ month', 'location' => 'Layibi, Gulu City', 'size' => 'Monthly rent', 'type' => 'Apartment', 'beds' => 2, 'description' => 'A furnished two-bedroom apartment suited to professionals who want comfort, security, and quick access to the city centre.', 'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=85'],
            ['id' => 4, 'title' => 'Commercial Roadside Land', 'purpose' => 'sale', 'price' => 'UGX 95,000,000', 'suffix' => '', 'location' => 'Bardege-Layibi, Gulu City', 'size' => '100 × 100 ft', 'type' => 'Commercial', 'beds' => null, 'description' => 'Visible roadside commercial land with strong potential for shops, offices, apartments, or a mixed-use development.', 'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=85'],
        ];
    }
}
