<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/16
 * Time: 5:59 PM
 */

namespace admin\services;


use Carbon\Carbon;
use common\consts\OrderConst;
use common\models\Order;
use common\models\User;

class DashboardService extends BaseService
{
    const DAY = 6;

    public function index()
    {
        $user = $this->user();
        $order = $this->order();
        return compact('user', 'order');
    }

    /**
     * @return array
     */
    private function user()
    {
        $query = User::find();
        $query->select('count(1) as num, date(user_create_at) as date');
        $query->where(['between', 'user_create_at', Carbon::now()->subDays(self::DAY)->toDateString(), Carbon::tomorrow()->toDateTimeString()]);
        $query->groupBy('date(user_create_at)');
        $query->asArray();
        $data = $query->all();
        $res = $this->handleData($data);
        return $res;
    }

    /**
     * @return array
     */
    private function order()
    {
        $query = Order::find();
        $query->select('sum(order_amount) as num, date(order_create_at) as date');
        $query->where(['between', 'order_create_at', Carbon::now()->subDays(6)->toDateString(), Carbon::tomorrow()->toDateTimeString()]);
        $query->where(['order_status' => OrderConst::STATUS_SUCCESS]);
        $query->groupBy('date(order_create_at)');
        $query->asArray();
        $data = $query->all();
        foreach ($data as $key => $val) {
            $data[$key]['num'] = round($val['num'] / 100, 2);
        }
        $res = $this->handleData($data);
        return $res;
    }

    private function handleData($data)
    {
        $date = array_column($data, 'date');
        $total = 0;
        for ($i = self::DAY; $i >= 0; $i--) {
            $dateRange[] = $currDate = Carbon::now()->subDays($i)->toDateString();
            $key = array_search($currDate, $date);
            $total += $expectedData[] = ($key === 0 || $key > 0) ? $data[$key]['num']: 1000;
        }
        return [
            'expectedData' => $expectedData,
            'dateRange' => $dateRange,
            'total' => $total,
        ];
    }
}