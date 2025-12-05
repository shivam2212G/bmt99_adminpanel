<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiSettings;
use App\Models\Slider;
use Illuminate\Http\Request;

class SLiderController extends Controller
{
    // 🟢 Index
    public function index()
    {
        $settings = ApiSettings::first();
        // return $settings;
        $sliders = Slider::all();
        return view('pages.settings.index', compact('sliders', 'settings'));
    }

    // 🟢 Create Form
    public function create()
    {
        return view('pages.settings.slider.create');
    }

    // 🟢 Store
    public function store(Request $request)
    {
        $request->validate([
            'slider_title' => 'required|string|max:255',
            'slider_description' => 'nullable|string',
            'slider_image' => 'required|image|mimes:jpg,png,jpeg,webp,svg',
        ]);

        $imagePath = null;
        if ($request->hasFile('slider_image')) {
            $image = $request->file('slider_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            if (!file_exists(public_path('sliders'))) {
                mkdir(public_path('sliders'), 0777, true);
            }
            $image->move(public_path('sliders'), $imageName);
            $imagePath = 'sliders/' . $imageName;
        }

        Slider::create([
            'slider_title' => $request->slider_title,
            'slider_description' => $request->slider_description,
            'slider_image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('settings.index')->with('success', 'Slider added successfully!');
    }

    // 🟢 Edit Form
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('pages.settings.slider.edit', compact('slider'));
    }

    // 🟢 Update
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'slider_title' => 'required|string|max:255',
            'slider_description' => 'nullable|string',
            'slider_image' => 'nullable|image|mimes:jpg,png,jpeg,webp,svg',
        ]);

        $imagePath = $slider->slider_image;

        if ($request->hasFile('slider_image')) {
            if ($slider->slider_image && file_exists(public_path($slider->slider_image))) {
                unlink(public_path($slider->slider_image));
            }
            $image = $request->file('slider_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('sliders'), $imageName);
            $imagePath = 'sliders/' . $imageName;
        }

        $slider->update([
            'slider_title' => $request->slider_title,
            'slider_description' => $request->slider_description,
            'slider_image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('settings.index')->with('success', 'Slider updated successfully!');
    }

    // 🟢 Delete
    public function delete($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->slider_image && file_exists(public_path($slider->slider_image))) {
            unlink(public_path($slider->slider_image));
        }
        $slider->delete();
        return redirect()->route('settings.index')->with('success', 'Slider deleted successfully!');
    }

    public function getAllSliders()
    {
        $sliders = Slider::where('is_active', true)->get();
        return response()->json($sliders);
    }
    public function updateSettings(Request $request)
    {
        $settings = ApiSettings::first();

        $settings->update([
            'new_products'   => $request->new_products,
            'best_offers'    => $request->best_offers,
            'less_in_stock'  => $request->less_in_stock,
            'shop_address'   => $request->shop_address,
            'shop_phone'     => $request->shop_phone,
            'shop_email'     => $request->shop_email,
            'facebook_link'  => $request->facebook_link,
            'twitter_link'   => $request->twitter_link,
            'instagram_link' => $request->instagram_link,
            'privacy_policy' => $request->privacy_policy,
            'discamer'       => $request->discamer,
        ]);

        return back()->with('success', 'Settings updated successfully');
    }

    public function getSettings()
    {
        $settings = ApiSettings::first();
        return response()->json($settings);
    }
}
