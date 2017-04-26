<?php
/**
 * Created by PhpStorm.
 * User: hongzhiyuan
 * Date: 2017/4/26
 * Time: 23:34
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->session()->get('wechat.oauth_user');
        if (isset($user)) {
            $user_item = DB::table('st_user')->where('open_id', $user->getId())->first();
            if (!isset($user_item)) {
                DB::table('st_user')->insert(
                    [
                        'open_id' => $user->id, 'nick_name' => $user->nickname,
                        'name' => $user->name, 'avatar' => $user->avatar,
                        'created' => date('Y-m-d H:i:s'), 'updated' => date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        return $next($request);
    }
}
