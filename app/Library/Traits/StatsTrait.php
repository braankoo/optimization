<?php

namespace App\Library\Traits;

use Illuminate\Support\Collection;

trait StatsTrait {


    /**
     * @param \Illuminate\Support\Collection $data
     * @return array
     */
    private function prepareTotals(Collection $data): array
    {
        $total = [];
        if (!empty($data))
        {


            $keys = array_keys(get_object_vars($data[0]));
            $total = [];


            foreach ( $keys as $key )
            {
                switch ( $key )
                {
                    case 'clicks':
                        $total[$key] = $data->sum('clicks');
                        break;
                    case 'impressions':
                        $total[$key] = $data->sum('impressions');
                        break;
                    case 'cost':
                        $total[$key] = round($data->sum('cost'), 2);
                        break;
                    case 'earned':
                        $total[$key] = round($data->sum('earned'), 2);
                        break;
                    case 'actual_cpa':
                        $total[$key] = round($data->average('actual_cpa'), 2);
                        break;
                    case 'actual_cps':
                        $total[$key] = round($data->average('actual_cps'), 2);
                        break;
                    case 'avg_cpc':
                        $total[$key] = round($data->average('avg_cpc'), 2);
                        break;
                    case 'pl':
                        $total[$key] = round($data->sum('pl'),2);
                        break;
                    case 'avg_position':
                        $total[$key] = round($data->average('avg_position'), 2);
                        break;
                    case 'ur':
                        $total[$key] = round($data->average('ur'), 2);
                        break;
                    case 'roi':
                        $total[$key] = round($data->average('roi'), 2);
                        break;
                    case 'actual_epa':
                        $total[$key] = round($data->average('actual_epa'), 2);
                        break;
                    case 'actual_eps':
                        $total[$key] = round($data->average('actual_eps'), 2);
                        break;
                    case 'cr':
                        $total[$key] = round($data->average('cr'), 2);
                        break;
                    case 'ctr':
                        $total[$key] = round($data->average('ctr'), 2);
                        break;
                    case 'profiles':
                        $total[$key] = round($data->sum('profiles'), 2);
                        break;
                    case 'upgrades':
                        $total[$key] = $data->sum('upgrades');
                        break;
                    default:
                        break;
                }
            }
        }

        return $total;
    }
}
