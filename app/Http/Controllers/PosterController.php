<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use Illuminate\Support\Facades\Log;
// use App\Models\Poster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PosterController extends Controller
{
    // Store the Poster
    public function store(Request $request)
    {

        $request->validate([
            'image'=>'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        Log::info($request->file("image"));

         // Check if the file exists
    if ($request->hasFile("image")) {
        $image = $request->file("image");
        $imageName = time().'.'.$image->extension();
        // Move the image to the 'images' folder inside the 'public' directory
        $image->move(public_path('profile'), $imageName);
        Log::info("this is inside the  if statement");
    } else {
        return response()->json(['error' => 'Image not found'], 400);
    }

        $Poster = Poster::create([
            'file_path' => $imageName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Poster uploaded successfully',
            'data' => $Poster,
        ]);
    }

    // Get all Posters
    public function index()
    {
        $Posters = Poster::all();
        return response()->json($Posters);
    }

    // Get a specific Poster
    public function show($id)
    {
        $Poster = Poster::find($id);
        if ($Poster) {
            return response()->json($Poster);
        } else {
            return response()->json(['message' => 'Poster not found'], 404);
        }
    }

    // Update an Poster
    public function update(Request $request, $id)
    {
        $Poster = Poster::find($id);
        if (!$Poster) {
            return response()->json(['message' => 'Poster not found'], 404);
        }

        $request->validate([
            'Poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old Poster
        Storage::disk('public')->delete($Poster->file_path);

        // Store new Poster
        $newPoster = $request->file('file_path');
        $filePath = $newPoster->store('Posters', 'public');

        // Update Poster record
        $Poster->update([
            'file_path' => $filePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Poster updated successfully',
            'data' => $Poster,
        ]);
    }

    // Delete an Poster
    public function destroy($id)
    {
        $Poster = Poster::find($id);
        if (!$Poster) {
            return response()->json(['message' => 'Poster not found'], 404);
        }

        // Delete Poster from storage
        Storage::disk('public')->delete($Poster->file_path);

        // Delete Poster record from database
        $Poster->delete();

        return response()->json([
            'success' => true,
            'message' => 'Poster deleted successfully',
        ]);
    }
}
