<?php

namespace App\Http\Controllers;

//import Model Artikel
use App\Models\Artikel;
//return type View
use Illuminate\View\View;
//return type redirectResponse
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;


class ArtikelController extends Controller
{
    public function index(): View
    {

        //get artikels
        $artikels = Artikel::latest()->paginate(5);

        //render view with artikels
        return view('artikels.index', compact('artikels'));
    }

    public function create(): View
    {
        return view('artikels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/artikels', $image->hashName());

        //create artikels
        Artikel::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        //redirect to index
        return redirect()->route('artikels.index');
    }
    public function show(string $id): View
    {
        //get artikel by ID
        $artikel = Artikel::findOrFail($id);

        //render view with artikel
        return view('artikels.show', compact('artikel'));
    }
}
