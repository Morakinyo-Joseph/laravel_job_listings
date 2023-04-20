<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index () {

        // dd(Listing::latest()->filter(request(['tag', 'search']))->paginate(2));

        return view('listings.index', [
            "listings" => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }


    public function show (Listing $listing) {
        return view('listings.show', ["listing" => $listing]);
    }


    public function create () {
        return view('listings.create');
    }
    

    public function store (Request $request) {

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'website' => 'required',
            'location' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo'))
        {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Listing::create($formFields);
        
        return redirect('/')->with('message', 'Listing Created Successfully');
    }


    public function edit (Listing $listing) {
        return view("listings.edit", ["listing" => $listing]);
    }


    public function update (Request $request, Listing $listing) {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'website' => 'required',
            'location' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo'))
        {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);
        
        return back()->with('message', 'Listing Updated Successfully');
    }


    public function destroy (Listing $listing, Request $request) {
        $listing->delete();
        return redirect('/')->with('message', "Listing Deleted Successfully");
    }
}
