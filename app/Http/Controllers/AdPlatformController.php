<?php

namespace App\Http\Controllers;

use App\Library\Traits\ChartTrait;
use App\Library\Traits\StatsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdPlatformController extends Controller {

    use StatsTrait, ChartTrait;

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $adPlatforms = [ 'google', 'bing', 'gemini' ];
        $data = new Collection();
        foreach ( $adPlatforms as $adPlatform )
        {
            $data->push(DB::connection($adPlatform)->table('stats_clients')->selectRaw(
                "
             '{$adPlatform}' as name,
             sum(clicks) as clicks,
             sum(impressions) as impressions,
             TRUNCATE((sum(cost) / 1000000),2) as cost,
             TRUNCATE((sum(earned) / 1000000),2) as earned,
             TRUNCATE(( sum(cost) / 1000000 ) / sum(profile),2) as actual_cpa,
             TRUNCATE(( sum(cost) / 1000000 ) / sum(upgrade),2)   as actual_cps,
             TRUNCATE(((sum(cost) / sum(clicks)) / 1000000),2) as avg_cpc,
             TRUNCATE(( (sum(earned) / 1000000) - ( sum(cost) / 1000000) ),2) as pl,
             IFNULL(TRUNCATE(SUM(`impressions` * `avg_position`) / SUM(impressions),1),0) as avg_position,
             IFNULL(ROUND( (sum(profile) / sum(upgrade)) ),0) as ur,
             IFNULL(TRUNCATE((( (sum(earned) / 1000000) -  ( sum(cost) / 1000000 ) ) / (sum(cost) / 1000000)) * 100,2),0) as roi,
             IFNULL(ROUND((sum(earned) / 1000000) / sum(profile),2),0) as actual_epa,
             IFNULL(ROUND((sum(earned) / 1000000) / sum(upgrade),2),0) as actual_eps,
             IFNULL(ROUND((sum(profile) / sum(clicks) * 100),2),0) as cr,
             IFNULL(ROUND( (sum(clicks) / sum(impressions) * 100 ),2),0) as ctr,
             sum(profile) as profiles,
             sum(upgrade) as upgrades")
                ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->input('startDate'));
                })
                ->when(!empty($request->input('endDate')), function ($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->input('endDate'));
                })->get());
        }

        return response()->json(
            [
                'data'  => $data->flatten(),
                'total' => $this->prepareTotals($data->flatten())
            ],
            JsonResponse::HTTP_OK
        );
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartSingle(string $adPlatform, Request $request)
    {

        $data = DB::connection($adPlatform)->table('stats_clients')->selectRaw($this->prepareRequestForSql($request) . ", DATE_FORMAT(created_at,'%m-%d') as created_at")
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->input('startDate'));
            })
            ->when(!empty($request->input('endDate')), function ($q) use ($request) {

                $q->whereDate('created_at', '<=', $request->input('endDate'));
            })->groupBy(DB::raw('DATE(created_at)'))->get();


        return response()->json($this->prepareResponse($request, $data), JsonResponse::HTTP_OK);
    }
}
