<?php

namespace App\Http\Controllers;

//import Model Artikel
use App\Models\Artikel;
//return type View
use Illuminate\Support\Facades\Storage;
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

        //render view with artikel
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

        //create artikel
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
    public function edit(string $id): View
    {
        //get artikel by ID
        $artikel = Artikel::findOrFail($id);

        //render view with artikel
        return view('artikels.edit', compact('artikel'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //get artikel by ID
        $artikel = Artikel::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/artikels', $image->hashName());

            //delete old image
            Storage::delete('public/artikels/'.$artikel->image);

            //update artikel with new image
            $artikel->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);

        } else {

            //update artikel without image
            $artikel->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        //redirect to index
        return redirect()->route('artikels.index');
    }

    public function destroy($id): RedirectResponse
    {
        //get artikel by ID
        $artikel = Artikel::findOrFail($id);

        //delete image
        Storage::delete('public/artikels/'. $artikel->image);

        //delete artikel
        $artikel->delete();

        //redirect to index
        return redirect()->route('artikels.index');
    }
}