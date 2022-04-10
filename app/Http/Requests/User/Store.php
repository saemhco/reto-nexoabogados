<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6',

            'document_number'   => 'nullable|string|max:255',
            'document_type'     => 'nullable|string|in:Cédula de Identidad,NIE,DNI,Pasaporte,Otro',
            'avatar'            => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address'           => 'nullable|string|max:255',
            'birth_date'        => 'nullable|date',
            'nationality'       => 'nullable|string|in:Chilena,Mexicano,Otro',
            'other_nationality' => 'nullable|string|max:50',
            'civil_status'      => 'nullable|string|in:Solero(a),Casado(a),Divorsiado(a),Viudo(a),Separado(a),Unión Libre,No definido',
            'sex'               => 'nullable|string|in:Masculino,Femenino',
        ];
    }
}
