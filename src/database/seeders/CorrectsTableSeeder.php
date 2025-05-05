<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CorrectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($userId = 1; $userId <= 6; $userId++) {
            $attendance = DB::table('attendances')
                ->where('user_id', $userId)
                ->where('date', '2025-05-01')
                ->first();

            if ($attendance) {
                DB::table('corrects')->insert([
                    'attendance_id' => $attendance->id,
                    'comment'       => '遅延のため',
                    'created_at'    => Carbon::now()->addDays(rand(0, 9)),
                    'updated_at'    => Carbon::now(),
                ]);
            }
        }
    }
}
