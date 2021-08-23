<?php

namespace App\Http\Controllers;

use App\Library\Traits\StatsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdGroupController extends Controller {

    use StatsTrait;

    public function index(string $adPlatform, Request $request)
    {
        $data = DB::connection($adPlatform)->table('ad_groups')->selectRaw(
            "
             ad_groups.name as name,
             ad_groups.id as id,
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
             sum(upgrade) as upgrades,
             status")
            ->join('stats_ad_groups', 'ad_groups.id', '=', 'stats_ad_groups.ad_group_id')
            ->when(!empty($request->input('name')), function ($q) use ($request) {
                $q->where('name', 'LIKE', "%" . $request->input('name') . "%");
            })
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->input('startDate'));
            })
            ->when(!empty($request->input('endDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->input('endDate'));
            })
            ->orderBy(!empty($request->input('sortBy')) ? $request->input('sortBy') : 'name', $request->input('sortDesc') == 'true' ? 'ASC' : 'DESC')
            ->groupBy('ad_groups.id')
            ->paginate(50);

        $total = DB::connection($adPlatform)->table('ad_groups')->selectRaw(
            "
             ad_groups.name as name,
             ad_groups.id as id,
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
             sum(upgrade) as upgrades,
             status")
            ->join('stats_ad_groups', 'ad_groups.id', '=', 'stats_ad_groups.ad_group_id')
            ->when(!empty($request->input('name')), function ($q) use ($request) {
                $q->where('name', 'LIKE', "%" . $request->input('name') . "%");
            })
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->input('startDate'));
            })
            ->when(!empty($request->input('endDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->input('endDate'));
            })
            ->orderBy(!empty($request->input('sortBy')) ? $request->input('sortBy') : 'name', $request->input('sortDesc') == 'true' ? 'ASC' : 'DESC')
            ->groupBy('ad_groups.id')
            ->get();


        $total = $this->prepareTotals(collect($total));


        return response()->json(
            [
                'data' =>
                    [
                        'total'      => $total,
                        'pagination' => $data,
                    ]

            ], JsonResponse::HTTP_OK);
    }
}
