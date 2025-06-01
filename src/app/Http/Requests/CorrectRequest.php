<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class CorrectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'work_start' => 'required|date_format:H:i',
            'work_end' => 'required|date_format:H:i',
            'break_start' => 'array',
            'break_start.*' => 'nullable|date_format:H:i',
            'break_end' => 'array',
            'break_end.*' => 'nullable|date_format:H:i',
            'comment' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'work_start.required' => '出勤時間を入力してください。',
            'work_start.date_format' => '出勤時間の形式が正しくありません（例: 09:00）。',
            'work_end.required' => '退勤時間を入力してください。',
            'work_end.date_format' => '退勤時間の形式が正しくありません（例: 18:00）。',
            'break_start.*.date_format' => '休憩開始時間の形式が正しくありません。',
            'break_end.*.date_format' => '休憩終了時間の形式が正しくありません。',
            'comment.required' => '備考を記入してください。',
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $workStart = $this->input('work_start');
            $workEnd = $this->input('work_end');

            if ($this->passesFormat($workStart) && $this->passesFormat($workEnd)) {
                $start = Carbon::createFromFormat('H:i', $workStart);
                $end = Carbon::createFromFormat('H:i', $workEnd);

                if ($start->gte($end)) {
                    $validator->errors()->add('work_start', '出勤時間もしくは退勤時間が不適切な値です。');
                }
            }

            $breakStarts = $this->input('break_start', []);
            $breakEnds = $this->input('break_end', []);

            foreach ($breakStarts as $i => $start) {
                $end = $breakEnds[$i] ?? null;

                if (!$start || !$end || !$this->passesFormat($start) || !$this->passesFormat($end)) {
                    continue;
                }

                $breakStart = Carbon::createFromFormat('H:i', $start);
                $breakEnd = Carbon::createFromFormat('H:i', $end);

                if (isset($start, $end, $workStart, $workEnd)
                    && $this->passesFormat($workStart) && $this->passesFormat($workEnd)) {

                    $startWork = Carbon::createFromFormat('H:i', $workStart);
                    $endWork = Carbon::createFromFormat('H:i', $workEnd);

                    if ($breakStart->lt($startWork) || $breakEnd->gt($endWork)) {
                        $validator->errors()->add("break_start.$i", '休憩時間が勤務時間外です。');
                    }
                }
            }
        });
    }

    private function passesFormat($value)
    {
        return preg_match('/^\d{2}:\d{2}$/', $value);
    }
}
