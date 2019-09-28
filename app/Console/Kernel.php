<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Tools\Tools;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            //功能 业务逻辑
            //$tools = new Tools();
            $user_url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->tools->get_access_token().'&next_openid=';
            $openid_info = file_get_contents($user_url);
            $user_result = json_decode($openid_info,1);
            foreach($user_result['data']['openid'] as $v){
                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_access_token().'&openid='.$v.'&lang=zh_CN';
                $user_re = file_get_contents($url);
                $user_info = json_decode($user_re,1);
                $db_user = DB::connection('mysql_cart')->table("wechat_openid")->where(['openid'=>$v])->first();
                if(empty($db_user)){
                    //没有数据，存入
                    DB::connection('mysql_cart')->table("wechat_openid")->insert([
                        'openid'=>$v,
                        'add_time'=>time()
                    ]);
                    //就是未签到
                    $openid = 'oPi8KuHGfvHjhL4u9BnIL4upIaJE';
                    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_access_token();
                    $data = [
                        'touser'=>$openid,
                        'template_id'=>'O0fFvgd-spPBGqQ_FrMt2zUgqxgZMz0fgZDquvswt14',
                        'url'=>'http://www.shopdemo.com',
                        'data'=>[
                            'first'=>[
                                'value'=>'first',
                                'color'=>''
                            ],
                            'keyword1'=>[
                                'value'=>'keyword1',
                                'color'=>''
                            ],
                            'keyword2'=>[
                                'value'=>'keyword2',
                                'color'=>''
                            ]
                        ]
                    ];
                    $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
                }else{
                    //判断是否签到
                    $today = date('Y-m-d',time());
                    if($db_user->sign_day == $today){
                        $openid = 'oPi8KuHGfvHjhL4u9BnIL4upIaJE';
                        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_access_token();
                        $data = [
                            'touser'=>$openid,
                            'template_id'=>'O0fFvgd-spPBGqQ_FrMt2zUgqxgZMz0fgZDquvswt14',
                            'url'=>'http://www.shopdemo.com',
                            'data'=>[
                                'first'=>[
                                    'value'=>'first',
                                    'color'=>''
                                ],
                                'keyword1'=>[
                                    'value'=>'keyword1',
                                    'color'=>''
                                ],
                                'keyword2'=>[
                                    'value'=>'keyword2',
                                    'color'=>''
                                ]
                            ]
                        ];
                        $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
                    }else{
                        $openid = 'oPi8KuHGfvHjhL4u9BnIL4upIaJE';
                        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_access_token();
                        $data = [
                            'touser'=>$openid,
                            'template_id'=>'O0fFvgd-spPBGqQ_FrMt2zUgqxgZMz0fgZDquvswt14',
                            'url'=>'http://www.shopdemo.com',
                            'data'=>[
                                'first'=>[
                                    'value'=>'first',
                                    'color'=>''
                                ],
                                'keyword1'=>[
                                    'value'=>'keyword1',
                                    'color'=>''
                                ],
                                'keyword2'=>[
                                    'value'=>'keyword2',
                                    'color'=>''
                                ]
                            ]
                        ];
                        $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            // })->daily();
            //})->everyMinute();
        })->dailyAt('20:00');
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
