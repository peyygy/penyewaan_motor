<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'motor_id' => ['required', 'integer', 'exists:motors,id'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'tipe_durasi' => ['required', 'string', 'in:harian,mingguan,bulanan'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'motor_id.required' => 'Motor wajib dipilih.',
            'motor_id.exists' => 'Motor yang dipilih tidak valid.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'tipe_durasi.required' => 'Tipe durasi wajib dipilih.',
            'tipe_durasi.in' => 'Tipe durasi tidak valid.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $tanggalMulai = Carbon::parse($this->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($this->tanggal_selesai);
            $days = $tanggalMulai->diffInDays($tanggalSelesai);

            // Validate duration based on type
            match($this->tipe_durasi) {
                'harian' => $days > 30 && $validator->errors()->add('tanggal_selesai', 'Rental harian maksimal 30 hari.'),
                'mingguan' => ($days < 7 || $days > 84) && $validator->errors()->add('tanggal_selesai', 'Rental mingguan minimal 7 hari dan maksimal 12 minggu.'),
                'bulanan' => ($days < 30 || $days > 365) && $validator->errors()->add('tanggal_selesai', 'Rental bulanan minimal 30 hari dan maksimal 12 bulan.'),
                default => null,
            };
        });
    }
}