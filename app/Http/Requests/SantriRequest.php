<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SantriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:124',
            'nama_panggilan' => 'nullable|string|max:64',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:64',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'kelas_id' => 'nullable|exists:kelas,id',
            'nama_wali' => 'required|string|max:124',
            'no_telp_wali' => 'required|string|max:20',
            'r_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // Only validate user fields on create
        if ($this->isMethod('post')) {
            $rules['username'] = 'required|string|max:64|unique:users,username';
            $rules['email'] = 'nullable|email|unique:users,email';
            $rules['password'] = 'required|string|min:6';
        }

        // On update, username and email are optional
        if ($this->isMethod('put') || $this->isMethod('patch')) {

            // Ambil ID santri dari route parameter
            $santriId = $this->route('id');
            $userId = null;

            if ($santriId) {
                $santri = \App\Models\Santri::find($santriId);
                if ($santri) {
                    $userId = $santri->user_id;
                }
            }

            $rules['username'] = 'sometimes|string|max:64|unique:users,username,' . $userId;
            $rules['email'] = 'nullable|email|unique:users,email,' . $userId;
            $rules['password'] = 'nullable|string|min:6';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'alamat.required' => 'Alamat wajib diisi',
            'nama_wali.required' => 'Nama wali wajib diisi',
            'no_telp_wali.required' => 'No telepon wali wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }
}
