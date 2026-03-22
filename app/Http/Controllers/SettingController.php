<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function editHomeSlider()
    {
        $title = 'Cài đặt slider trang chủ';
        $slides = Setting::getValue('home_slider', $this->defaultSlides());

        return view('admin.settings.home-slider', compact('title', 'slides'));
    }

    public function updateHomeSlider(Request $request)
    {
        $validated = $request->validate([
            'slides' => 'required|array|min:1|max:6',
            'slides.*.image' => 'required|string|max:2048',
            'slides.*.title' => 'nullable|string|max:255',
            'slides.*.subtitle' => 'nullable|string|max:255',
            'slides.*.button_text' => 'nullable|string|max:100',
            'slides.*.button_link' => 'nullable|string|max:255',
        ], [
            'slides.*.image.required' => 'Vui lòng nhập ảnh cho từng slide.',
        ]);

        $slides = collect($validated['slides'])
            ->map(function (array $slide) {
                return [
                    'image' => trim($slide['image']),
                    'title' => trim($slide['title'] ?? ''),
                    'subtitle' => trim($slide['subtitle'] ?? ''),
                    'button_text' => trim($slide['button_text'] ?? ''),
                    'button_link' => trim($slide['button_link'] ?? ''),
                ];
            })
            ->filter(fn(array $slide) => $slide['image'] !== '')
            ->values()
            ->all();

        Setting::updateOrCreate(
            ['key' => 'home_slider'],
            ['value' => $slides]
        );

        return redirect()
            ->route('admin.settings.home-slider.edit')
            ->with('success', 'Đã cập nhật slider trang chủ.');
    }

    private function defaultSlides(): array
    {
        return [
            [
                'image' => 'https://placehold.co/1400x520?text=Kicap+Hero+1',
                'title' => 'KEYCAP PBT DYESUB',
                'subtitle' => 'Bo keycap dep cho setup ban phim co hang ngay.',
                'button_text' => 'Xem san pham',
                'button_link' => '/san-pham',
            ],
            [
                'image' => 'https://placehold.co/1400x520?text=Kicap+Hero+2',
                'title' => 'SWITCH VA GEAR BUILD',
                'subtitle' => 'Tong hop switch, keycap va kit cho nguoi moi choi.',
                'button_text' => 'Kham pha ngay',
                'button_link' => '/san-pham',
            ],
            [
                'image' => 'https://placehold.co/1400x520?text=Kicap+Hero+3',
                'title' => 'TIN TUC KEYBOARD',
                'subtitle' => 'Cap nhat bai viet moi ve custom keyboard va phu kien.',
                'button_text' => 'Doc bai viet',
                'button_link' => '/tin-tuc',
            ],
        ];
    }
}
