<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Services\Widget\CurrentVisitorsService;
use App\Services\Widget\DeviceService;
use App\Services\Widget\LocationService;
use App\Services\Widget\PageService;
use App\Services\Widget\SourceService;
use App\Services\Widget\MainStatService;
use App\Services\Widget\VisitorService;
use App\Services\Widget\NotFoundService;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $domain)
    {
        $sites =  Auth::user()->sites()->orderBy('id')->get()->toArray();
        if ($domain != Auth::user()->last_visited_site) {
            $user = Auth::user();
            $user->last_visited_site = $domain;
            $user->save();
        }
        $site = Site::where('id', $request->site_id)->first();
        return view('my_dashboard', [
            "domain" => $domain,
            "sites" => $sites,
            "show_404_widget" => empty($site->siteSetting->page_not_found_enabled) ? false : true,
            "show_external_links_widget" => empty($site->siteSetting->external_tracking_enabled) ? false : true,
        ]);
    }

    /**
     * Display the visitors data needed for cahrtjs to load based on the specified filter date
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function visitors(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new VisitorService())->handle($request->all());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }

    /**
     * Display top stat widgets specified filter date
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function mainStat(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new MainStatService())->handle($request->all());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }

    /**
     * Display the visitors data needed for cahrtjs to load based on the specified filter date
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function currentVisitors(Request $request, $domain)
    {
        $countCurrentVisitors = 0;
        try {
            $countCurrentVisitors = (new CurrentVisitorsService())->handle($request->all());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json(['count' => $countCurrentVisitors], 201);
    }
    /**
     * Display the Top sources data needed for Top Sources Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function sources(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new SourceService())->handle($request->all());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }
    /**
     * Display the Top countries data needed for Top Countries Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function countries(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new LocationService())->handle(array_merge($request->all(), ['location_category' => 'countries']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }
    /**
     * Display the Top cities data needed for Top Cities Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function cities(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new LocationService())->handle(array_merge($request->all(), ['location_category' => 'cities']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }
    /**
     * Display the Top regions data needed for Top Regions Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function regions(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new LocationService())->handle(array_merge($request->all(), ['location_category' => 'regions']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }


    /**
     * Display the Top browsers data needed for Top browsers Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function browsers(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new DeviceService())->handle(array_merge($request->all(), ['device_category' => 'browsers']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }

    /**
     * Display the OS data needed for Top OS Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function operatingSystems(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new DeviceService())->handle(array_merge($request->all(), ['device_category' => 'os']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }

    /**
     * Display the Top device sizes data needed for Top device sizes Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function deviceSizes(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new DeviceService())->handle(array_merge($request->all(), ['device_category' => 'sizes']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }


    /**
     * Display the Top pages visitos Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function topPages(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new PageService())->handle(array_merge($request->all(), ['page_category' => 'top_pages']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }
    /**
     * Display the Top pages visitos Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function entryPages(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new PageService())->handle(array_merge($request->all(), ['page_category' => 'entry_pages']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }
    /**
     * Display the Top pages visitos Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function exitPages(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new PageService())->handle(array_merge($request->all(), ['page_category' => 'exit_pages']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }
    /**
     * Display the 404 pages Widget
     *
     * @param  \Illuminate\Http\Request $request The incoming request containing the filter_date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data based on the filter.
     */
    public function notFound(Request $request, $domain)
    {
        $data = [];
        try {
            $data = (new NotFoundService())->handle(array_merge($request->all(), ['page_category' => 'exit_pages']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data, 201);
    }
}
