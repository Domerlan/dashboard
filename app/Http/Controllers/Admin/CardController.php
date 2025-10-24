<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CardController extends Controller
{
    public function index()
    {
        $cards = DashboardCard::latest('created_at')->paginate(12)->through(function ($card) {
            return [
                'id' => $card->id,
                'title' => $card->title,
                'description' => $card->description,
                'link' => $card->link,
                'active' => $card->active,
                'image_url' => $card->image_url,
                'created_at' => $card->created_at,
            ];
        });

        return Inertia::render('Admin/Cards/Index', [
            'cards' => $cards,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:2048'],
            'active' => ['boolean'],
            'audience' => ['required', 'in:all,support,technical'],
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('image')->store('cards', 'public');

        DashboardCard::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'link' => $data['link'] ?? null,
            'active' => $data['active'] ?? false,
            'audience' => $data['audience'],
            'image_path' => $path,
        ]);

        return redirect()->route('admin.cards.index')->with('success', 'Карточка создана');
    }

    public function update(Request $request, DashboardCard $card)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:2048'],
            'active' => ['boolean'],
            'audience' => ['required', 'in:all,support,technical'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($card->image_path) {
                Storage::disk('public')->delete($card->image_path);
            }
            $card->image_path = $request->file('image')->store('cards', 'public');
        }

        $card->fill([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'link' => $data['link'] ?? null,
            'active' => $data['active'] ?? false,
            'audience' => $data['audience'],
        ])->save();

        return redirect()->route('admin.cards.index')->with('success', 'Карточка обновлена');
    }

    public function destroy(DashboardCard $card)
    {
        if ($card->image_path) {
            Storage::delete($card->image_path);
        }
        $card->delete();
        return redirect()->route('admin.cards.index')->with('success', 'Карточка удалена');
    }
}
