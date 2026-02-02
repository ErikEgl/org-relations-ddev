<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $this->createOrganizationRecursive($request->all());
        });

        return response()->json(['status' => 'ok'], 201);
    }

    private function createOrganizationRecursive(array $data, Organization $parent = null)
    {
        $org = Organization::firstOrCreate([
            'org_name' => $data['org_name']
        ]);

        if ($parent) {
            $parent->daughters()->syncWithoutDetaching($org->id);
        }

        if (!empty($data['daughters']) && is_array($data['daughters'])) {
            foreach ($data['daughters'] as $daughter) {
                $this->createOrganizationRecursive($daughter, $org);
            }
        }
    }

public function relations($org_name)
{
    $org = Organization::where('org_name', $org_name)->firstOrFail();

    $parents = $org->parents()->get()->map(fn($n) => [
        'relationship_type' => 'parent',
        'org_name' => $n->org_name
    ]);

    $daughters = $org->daughters()->get()->map(fn($n) => [
        'relationship_type' => 'daughter',
        'org_name' => $n->org_name
    ]);

    $sisters = $org->parents()
        ->with('daughters')
        ->get()
        ->pluck('daughters')
        ->flatten()
        ->unique('id')
        ->where('id', '!=', $org->id)
        ->map(fn($n) => [
            'relationship_type' => 'sister',
            'org_name' => $n->org_name
        ]);

    $result = $parents->concat($daughters)->concat($sisters)
        ->sortBy('org_name')
        ->values();

    return response()->json($result);
}
}