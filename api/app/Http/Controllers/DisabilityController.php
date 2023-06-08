<?php

namespace App\Http\Controllers;

use App\Models\Disability;
use Illuminate\Database\Eloquent\Collection;

class DisabilityController extends Controller
{
    public function index(): Collection
    {
        return Disability::all();
    }

    public function show(string $id)
    {
        return Disability::findOrFail($id);
    }
}
