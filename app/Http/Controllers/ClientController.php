<?php

namespace App\Http\Controllers;

use App\Library\Traits\ChartTrait;
use App\Library\Traits\StatsTrait;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ClientController extends Controller {

    use StatsTrait, ChartTrait;

    /**
     * @param string $adPlatform
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(string $adPlatform, Request $request): JsonResponse
    {

        $data = DB::connection($adPlatform)->table('clients')->selectRaw(
            "
             clients.name as name,
             clients.id as id,
             sum(clicks) as clicks,
             sum(impressions) as impressions,
             ROUND(TRUNCATE((sum(cost) / 1000000),2),2) as cost,
             ROUND(TRUNCATE((sum(earned) / 1000000),2),2) as earned,
             ROUND(TRUNCATE(( sum(cost) / 1000000 ) / sum(profile),2),2) as actual_cpa,
             TRUNCATE(( sum(cost) / 1000000 ) / sum(upgrade),2)   as actual_cps,
             IFNULL(TRUNCATE(((sum(cost) / sum(clicks)) / 1000000),2),0) as avg_cpc,
             ROUND(TRUNCATE(( (sum(earned) / 1000000) - ( sum(cost) / 1000000) ),2),2) as pl,
             IFNULL(TRUNCATE(SUM(`impressions` * `avg_position`) / SUM(impressions),1),0) as avg_position,
             IFNULL(ROUND( (sum(profile) / sum(upgrade)) ),0) as ur,
             IFNULL(TRUNCATE((( (sum(earned) / 1000000) -  ( sum(cost) / 1000000 ) ) / (sum(cost) / 1000000)) * 100,2),0) as roi,
             IFNULL(TRUNCATE((sum(earned) / 1000000) / sum(profile),2),0) as actual_epa,
             IFNULL(TRUNCATE((sum(earned) / 1000000) / sum(upgrade),2),0) as actual_eps,
             IFNULL(TRUNCATE((sum(profile) / sum(clicks) * 100),2),0) as cr,
             IFNULL(TRUNCATE( (sum(clicks) / sum(impressions) * 100 ),2),0) as ctr,
             sum(profile) as profiles,
             sum(upgrade) as upgrades")
            ->join('stats_clients', 'clients.id', '=', 'stats_clients.client_id')
            ->when(!empty($request->input('name')), function ($q) use ($request) {
                $q->where('name', 'LIKE', "%" . $request->input('name') . "%");
            })
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->input('startDate'));
            })
            ->when(!empty($request->input('endDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->input('endDate'));
            })
            ->groupBy('client_id')
            ->orderBy(!empty($request->input('sortBy')) ? $request->input('sortBy') : 'name', $request->input('sortDesc') == 'true' ? 'ASC' : 'DESC')
            ->paginate(50);


        $total = DB::connection($adPlatform)->table('clients')->selectRaw(
            "
             clients.name as name,
             clients.id as id,
             sum(clicks) as clicks,
             sum(impressions) as impressions,
             ROUND(TRUNCATE((sum(cost) / 1000000),2),2) as cost,
             ROUND(TRUNCATE((sum(earned) / 1000000),2),2) as earned,
             ROUND(TRUNCATE(( sum(cost) / 1000000 ) / sum(profile),2),2) as actual_cpa,
             TRUNCATE(( sum(cost) / 1000000 ) / sum(upgrade),2)   as actual_cps,
             IFNULL(TRUNCATE(((sum(cost) / sum(clicks)) / 1000000),2),0) as avg_cpc,
             ROUND(TRUNCATE(( (sum(earned) / 1000000) - ( sum(cost) / 1000000) ),2),2) as pl,
             IFNULL(TRUNCATE(SUM(`impressions` * `avg_position`) / SUM(impressions),1),0) as avg_position,
             IFNULL(ROUND( (sum(profile) / sum(upgrade)) ),0) as ur,
             IFNULL(TRUNCATE((( (sum(earned) / 1000000) -  ( sum(cost) / 1000000 ) ) / (sum(cost) / 1000000)) * 100,2),0) as roi,
             IFNULL(TRUNCATE((sum(earned) / 1000000) / sum(profile),2),0) as actual_epa,
             IFNULL(TRUNCATE((sum(earned) / 1000000) / sum(upgrade),2),0) as actual_eps,
             IFNULL(TRUNCATE((sum(profile) / sum(clicks) * 100),2),0) as cr,
             IFNULL(TRUNCATE( (sum(clicks) / sum(impressions) * 100 ),2),0) as ctr,
             sum(profile) as profiles,
             sum(upgrade) as upgrades")
            ->join('stats_clients', 'clients.id', '=', 'stats_clients.client_id')
            ->when(!empty($request->input('name')), function ($q) use ($request) {
                $q->where('name', 'LIKE', "%" . $request->input('name') . "%");
            })
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->input('startDate'));
            })
            ->when(!empty($request->input('endDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->input('endDate'));
            })
            ->groupBy('client_id')
            ->orderBy(!empty($request->input('sortBy')) ? $request->input('sortBy') : 'name', $request->input('sortDesc') == 'true' ? 'ASC' : 'DESC')
            ->get();

        $total = $this->prepareTotals(collect($total));


        return response()->json(
            [
                'data' =>
                    [
                        'total'      => $total,
                        'pagination' => $data
                    ]

            ], JsonResponse::HTTP_OK);

    }


    /**
     * @param string $adPlatform
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $adPlatform, Client $client, Request $request): JsonResponse
    {
        $data = DB::connection($adPlatform)->table('campaigns')->selectRaw(
            "
             campaigns.name as name,
             campaigns.id as id,
             sum(clicks) as clicks,
             sum(impressions) as impressions,
             ROUND(TRUNCATE((sum(cost) / 1000000),2),2) as cost,
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
            ->join('stats_campaigns', 'campaigns.id', '=', 'stats_campaigns.campaign_id')
            ->where('campaigns.client_id', '=', $client->id)
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
            ->groupBy('campaigns.id')
            ->paginate(50);


        $total = DB::connection($adPlatform)->table('campaigns')->selectRaw(
            "
             campaigns.name as name,
             campaigns.id as id,
             sum(clicks) as clicks,
             sum(impressions) as impressions,
             ROUND(TRUNCATE((sum(cost) / 1000000),2),2) as cost,
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
            ->join('stats_campaigns', 'campaigns.id', '=', 'stats_campaigns.campaign_id')
            ->where('campaigns.client_id', '=', $client->id)
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
            ->groupBy('campaigns.id')
            ->get();


        $total = $this->prepareTotals(collect($total));


        return response()->json(
            [
                'data' =>
                    [
                        'total'      => $total,
                        'pagination' => $data,
                        'client'     => $client->name
                    ]

            ], JsonResponse::HTTP_OK);
    }


    /**
     * @param string $adPlatform
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartIndex(string $adPlatform, Request $request): JsonResponse
    {


        $data = DB::connection($adPlatform)->table('stats_clients')->selectRaw($this->prepareRequestForSql($request) . ", DATE_FORMAT(created_at,'%m-%d') as created_at")
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->input('startDate'));
            })
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->input('endDate'));
            })->groupBy(DB::raw('DATE(created_at)'))->get();


        return response()->json($this->prepareResponse($request, $data), JsonResponse::HTTP_OK);
    }


    /**
     * @param string $adPlatform
     * @param \App\Models\Client $client
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartSingle(string $adPlatform, Client $client, Request $request): JsonResponse
    {
        $data = DB::connection($adPlatform)->table('stats_clients')->selectRaw($this->prepareRequestForSql($request) . ", DATE_FORMAT(created_at,'%m-%d') as created_at")
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->input('startDate'));
            })
            ->when(!empty($request->input('startDate')), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->input('endDate'));
            })
            ->where('stats_clients.client_id', '=', $client->id)
            ->groupBy(DB::raw('DATE(created_at)'))->get();

        return response()->json($this->prepareResponse($request, $data), JsonResponse::HTTP_OK);
    }
}
