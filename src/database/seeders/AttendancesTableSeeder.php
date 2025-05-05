<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = [1, 2, 3, 4, 5, 6];

        $targetMonths = [
            ['year' => 2025, 'month' => 4],
            ['year' => 2025, 'month' => 5],
            ['year' => 2025, 'month' => 6],
        ];

        foreach ($userIds as $userId) {
            foreach ($targetMonths as $target) {
                $year = $target['year'];
                $month = $target['month'];
                $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($year, $month, $day)->format('Y-m-d');

                    $exists = DB::table('attendances')
                        ->where('user_id', $userId)
                        ->where('date', $date)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $attendanceId = DB::table('attendances')->insertGetId([
                        'user_id'    => $userId,
                        'date'       => $date,
                        'work_start' => '09:00:00',
                        'work_end'   => '18:00:00',
                        'work_time'  => '08:00:00',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    DB::table('rests')->insert([
                        'attendance_id' => $attendanceId,
                        'break_start'   => '12:00:00',
                        'break_end'     => '13:00:00',
                        'break_time'    => '01:00:00',
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    ]);
                }
            }
        }
    }
}
