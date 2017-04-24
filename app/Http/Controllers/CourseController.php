<?php
/**
 * Created by PhpStorm.
 * User: hongzhiyuan
 * Date: 2017/4/25
 * Time: 1:41
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Foundation\Application;

class CourseController extends Controller
{
    public function courseList(Request $request, $all=0)
    {
        $user = $request->session()->get('wechat.oauth_user');
        $course_selection = DB::table('st_course')
            ->join('st_teacher', 'st_course.teacher_id', '=', 'st_teacher.id')
            ->select('st_course.*', 'st_teacher.name as teacher_name',
                'st_teacher.description as teacher_desc', 'st_teacher.skill');
        $courses = array();
        if (intval($all) > 0) {
            $courses = $course_selection->get();
        } else {
            $courses = $course_selection->take(5)->get();
        }
        return view('course.list', ['courses' => $courses]);
    }

    public function item(Request $request, $id) {
        $user = $request->session()->get('wechat.oauth_user');
        $course = DB::table('st_course')
            ->join('st_teacher', 'st_course.teacher_id', '=', 'st_teacher.id')
            ->where('st_course.id', $id)
            ->select('st_course.*', 'st_teacher.name as teacher_name',
                'st_teacher.description as teacher_desc', 'st_teacher.skill')
            ->get();
        return view('course.item', ['course' => $course]);
    }
}
