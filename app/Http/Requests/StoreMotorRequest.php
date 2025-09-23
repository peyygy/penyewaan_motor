<?php

namespace App\Http\Requests;

use App\Enums\MotorType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMotorRequest extends FormRequest
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
            'merk' => ['required', 'string', 'max:50'],
            'tipe_cc' => ['required', 'string', Rule::in(MotorType::values())],
            'no_plat' => ['required', 'string', 'max:20', 'unique:motors'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'dokumen_kepemilikan' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'merk.required' => 'Merk motor wajib diisi.',
            'tipe_cc.required' => 'Tipe CC motor wajib dipilih.',
            'tipe_cc.in' => 'Tipe CC motor tidak valid.',
            'no_plat.required' => 'Nomor plat wajib diisi.',
            'no_plat.unique' => 'Nomor plat sudah terdaftar.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPEG, PNG, atau JPG.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'dokumen_kepemilikan.file' => 'Dokumen kepemilikan harus berupa file.',
            'dokumen_kepemilikan.mimes' => 'Format dokumen harus PDF, JPEG, PNG, atau JPG.',
            'dokumen_kepemilikan.max' => 'Ukuran dokumen maksimal 5MB.',
        ];
    }
}