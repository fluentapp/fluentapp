<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Services\Site\SiteCreatorService;
use Exception;
use Illuminate\Support\Facades\Auth;


class SiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*
        $sites = Auth::user()->sites()->get();
        if ($sites->count() === 1) {
            $siteName = $sites->first()->fqdn;
            return redirect()->route('home', ['domain' => $siteName]);
        }
        */
        return view('sites');
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $sites =  Auth::user()->sites()->orderBy('id')->get()->toArray();
        return response()->json($sites, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiteRequest $request)
    {
        $data = [];
        try {
            $data = (new SiteCreatorService())->create($request->all(), Auth::user());
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteRequest $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        //
    }
}
