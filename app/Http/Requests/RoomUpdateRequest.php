<?php

namespace App\Http\Requests;

use App\Models\Hotel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoomUpdateRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }


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


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hotel = Hotel::find($this->hotel_id);

            if ($hotel) {
                $currentRooms = Room::where('hotel_id', $this->hotel_id)->sum('qty_rooms');
                $newTotal = $currentRooms + $this->qty_rooms;

                if ($newTotal > $hotel->qty_rooms) {
                    $validator->errors()->add('qty_rooms', 'The total number of rooms exceeds the hotel capacity.');
                }
            }
        });
    }
}
