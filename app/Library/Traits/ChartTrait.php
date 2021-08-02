<?php

namespace App\Library\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait ChartTrait {


    /**
     * @param \Illuminate\Http\Request $request
     */
    private function prepareRequestForSql(Request $request): string
    {
        switch ( $request->input('type') )
        {
            case 'cost|earned':
                $query = "TRUNCATE((sum(cost) / 1000000),2) as cost,TRUNCATE((sum(earned) / 1000000),2) as earned";
                break;
            case 'pl':
                $query = "TRUNCATE(( (sum(earned) / 1000000) - ( sum(cost) / 1000000) ),2) as pl";
                break;
            case 'profiles|upgrades':
                $query = "sum(profile) as profiles,sum(upgrade) as upgrades";
                break;
            case 'ur':
                $query = "IFNULL(ROUND( (sum(profile) / sum(upgrade)) ),0) as ur";
                break;
            case 'cpa':
                $query = "TRUNCATE(( sum(cost) / 1000000 ) / sum(profile),2) as cpa";
                break;
            case 'ctr':
                $query = "IFNULL(TRUNCATE( (sum(clicks) / sum(impressions) * 100 ),2),0) as ctr";
                break;
            case 'cr':
                $query = "IFNULL(TRUNCATE((sum(profile) / sum(clicks) * 100),2),0) as cr";

                break;
            default:
                $query = '';
                break;

        }
        return $query;
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Support\Collection $data
     * @return array
     */
    private function prepareResponse(Request $request, Collection $data): array
    {
        if (strpos($request->input('type'), '|'))
        {
            $dataWithLabels = [];
            $labels = explode('|', $request->input('type'));
            foreach ( $labels as $label )
            {
                $dataWithLabels[] = [
                    'label' => $label,
                    'data'  => $data->map->{$label}
                ];
            }
        } else
        {
            $dataWithLabels[] = [
                'label' => $request->input('type'),

                'data' => $data->map->{$request->input('type')}
            ];
        }

        return [
            'datasets' => $dataWithLabels,
            'labels'   => $data->map->created_at
        ];
    }

}
