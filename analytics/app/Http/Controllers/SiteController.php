<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Models\SiteSetting;
use App\Services\Site\SiteCreatorService;
use App\Services\Site\SiteUpdatorService;
use Exception;
use Illuminate\Http\Request;
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
    public function show(Request $request, $domain)
    {
        //
        return view('site_managment', ["domain" => $domain, "timezone" => $request->site_timezone]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteRequest $request, $domain)
    {
        //
        $data = [];
        try {
            $data = (new SiteUpdatorService())->update($request->all());
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $domain)
    {
        //
        try {
            $site = Site::find($request->site_id);
            // $site->active = 0; // for a soft delete (deactivate site)
            // $site->save();
            $site->users()->detach();
            $site->delete();
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 400);
        }
        return response()->json(['message' => 'deleted sucessfully '], 201);
    }
    /**
     * Display a listing of the resource.
     */
    public function getSettings(Request $request, $domain)
    {
        $siteSetting = SiteSetting::where('site_id', $request->site_id)->first();

        if ($siteSetting) {
            return response()->json([
                'page_not_found_enabled' => empty($siteSetting->page_not_found_enabled) ? false : true,
                'page_not_found_titles' => explode(',', $siteSetting->page_not_found_titles),
                'external_tracking_enabled' => empty($siteSetting->external_tracking_enabled) ? false : true,
            ]);
        }

        return response()->json([
            'page_not_found_enabled' =>  false,
            'page_not_found_titles' => [],
            'external_tracking_enabled' => false,
        ]);
    }
    /**
     * Update the Site 404 settings.
     */
    public function updatePageNotFoundTitles(Request $request, $domain)
    {
        try {
            $siteSetting = SiteSetting::where('site_id', $request->site_id)->first();

            if (!$siteSetting) {
                // Create a new SiteSetting if it doesn't exist
                $siteSetting = new SiteSetting();
                $siteSetting->site_id = $request->site_id;
            }

            $pageNotFoundEnabled = $request->input('page_not_found_enabled');
            $pageNotFoundTitles = $request->input('page_not_found_titles');
            $pageNotFoundTitlesArray = array_map('trim', $pageNotFoundTitles);
            $siteSetting->page_not_found_enabled = $pageNotFoundEnabled;
            $siteSetting->page_not_found_titles = implode(",", $pageNotFoundTitlesArray);
            $siteSetting->save();
            return response()->json(['message' => '404 titles updated successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update page not found titles'], 400);
        }
    }

    /**
     * Update the Site external links settings.
     */
    public function updateExternalLinkSetting(Request $request, $domain)
    {
        try {
            $siteSetting = SiteSetting::where('site_id', $request->site_id)->first();

            if (!$siteSetting) {
                // Create a new SiteSetting if it doesn't exist
                $siteSetting = new SiteSetting();
                $siteSetting->site_id = $request->site_id;
            }

            $externalLinksEnabled = $request->input('external_links_enabled');
            $siteSetting->external_tracking_enabled = $externalLinksEnabled;
            $siteSetting->save();
            return response()->json(['message' => 'updated successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update'], 400);
        }
    }
}
