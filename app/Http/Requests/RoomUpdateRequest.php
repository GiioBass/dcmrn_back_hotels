<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'qty_rooms' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:standard,junior_suite,suite'],
            'accommodation' => ['required', function ($attribute, $value, $fail) {
                $validAccommodations = [
                    'standard' => ['sencilla', 'doble'],
                    'junior_suite' => ['doble', 'triple'],
                    'suite' => ['sencilla', 'doble', 'triple']
                ];

                $roomType = $this->input('type');
                if (!isset($validAccommodations[$type]) || !in_array($this->accommodation, $validAccommodations[$roomType])) {
                    return $this->fail("The accomodation is invalid for this room type.");
                }
            }],
        ];
    }
}
