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
        $motorId = $this->route('motor') ? $this->route('motor')->id : null;
        
        return [
            // Original fields (for backward compatibility)
            'merk' => ['sometimes', 'string', 'max:50'],
            'tipe_cc' => ['sometimes', 'string', Rule::in(MotorType::values())],
            'no_plat' => ['sometimes', 'string', 'max:20', Rule::unique('motors')->ignore($motorId)],
            
            // New fields for admin form
            'brand' => ['sometimes', 'string', 'max:50'],
            'model' => ['sometimes', 'string', 'max:50'],
            'license_plate' => ['sometimes', 'string', 'max:20', Rule::unique('motors', 'no_plat')->ignore($motorId)],
            'type' => ['sometimes', 'string', Rule::in(MotorType::values())],
            'year' => ['nullable', 'integer', 'min:1990', 'max:' . date('Y')],
            'owner_id' => ['sometimes', 'integer', 'exists:users,id'],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'verified', 'available', 'rented', 'maintenance'])],
            'description' => ['nullable', 'string', 'max:1000'],
            
            // File uploads
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'dokumen_kepemilikan' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
            
            // Tarif fields
            'harga_per_hari' => ['sometimes', 'numeric', 'min:0'],
            'harga_per_minggu' => ['nullable', 'numeric', 'min:0'],
            'harga_per_bulan' => ['nullable', 'numeric', 'min:0'],
            'deposit' => ['nullable', 'numeric', 'min:0'],
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
            
            // New field messages
            'brand.required' => 'Merek motor wajib diisi.',
            'model.required' => 'Model motor wajib diisi.',
            'license_plate.required' => 'Plat nomor wajib diisi.',
            'license_plate.unique' => 'Plat nomor sudah terdaftar.',
            'type.required' => 'Tipe motor wajib dipilih.',
            'type.in' => 'Tipe motor tidak valid.',
            'year.integer' => 'Tahun harus berupa angka.',
            'year.min' => 'Tahun minimal 1990.',
            'year.max' => 'Tahun tidak boleh lebih dari tahun sekarang.',
            'owner_id.required' => 'Pemilik motor wajib dipilih.',
            'owner_id.exists' => 'Pemilik motor tidak ditemukan.',
            'status.in' => 'Status motor tidak valid.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            
            // File messages
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPEG, PNG, atau JPG.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'dokumen_kepemilikan.file' => 'Dokumen kepemilikan harus berupa file.',
            'dokumen_kepemilikan.mimes' => 'Format dokumen harus PDF, JPEG, PNG, atau JPG.',
            'dokumen_kepemilikan.max' => 'Ukuran dokumen maksimal 5MB.',
            
            // Tarif messages
            'harga_per_hari.required' => 'Harga per hari wajib diisi.',
            'harga_per_hari.numeric' => 'Harga per hari harus berupa angka.',
            'harga_per_hari.min' => 'Harga per hari tidak boleh negatif.',
            'harga_per_minggu.numeric' => 'Harga per minggu harus berupa angka.',
            'harga_per_minggu.min' => 'Harga per minggu tidak boleh negatif.',
            'harga_per_bulan.numeric' => 'Harga per bulan harus berupa angka.',
            'harga_per_bulan.min' => 'Harga per bulan tidak boleh negatif.',
            'deposit.numeric' => 'Deposit harus berupa angka.',
            'deposit.min' => 'Deposit tidak boleh negatif.',
        ];
    }
}