<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CorrectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = [1, 2, 3, 4, 5, 6];
        $date = Carbon::create(2025, 5, 2);

        foreach ($userIds as $userId) {
            $attendanceId = DB::table('attendances')->insertGetId([
                'user_id'    => $userId,
                'date'       => $date->format('Y-m-d'),
                'work_start' => '09:30:00',
                'work_end'   => '18:00:00',
                'work_time'  => '08:00:00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('rests')->insert([
                'attendance_id' => $attendanceId,
                'break_start'   => '12:30:00',
                'break_end'     => '13:00:00',
                'break_time'    => '00:30:00',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);

            DB::table('corrects')->insert([
                'attendance_id' => $attendanceId,
                'comment'       => '遅延のため',
                'approved_at'   => null,
                'created_at'    => Carbon::create(2025, 5, 3)->addDays(rand(0, 3)),
                'updated_at'    => Carbon::now(),
            ]);
        }
    }
}
