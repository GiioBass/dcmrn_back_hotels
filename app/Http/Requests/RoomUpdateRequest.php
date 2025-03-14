<?php

namespace App\Http\Requests;

use App\Models\Hotel;
use App\Models\Room;
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
            'rooms' => ['required', 'array'],
            'rooms.*.id' => ['required', 'exists:rooms,id'],
            'rooms.*.qty_rooms' => ['required', 'integer', 'min:1'],
            'rooms.*.type' => ['required', 'in:standard,junior_suite,suite'],
            'rooms.*.accommodation' => ['required', function ($attribute, $value, $fail) {
                $validAccommodations = [
                    'standard' => ['sencilla', 'doble'],
                    'junior_suite' => ['doble', 'triple'],
                    'suite' => ['sencilla', 'doble', 'triple']
                ];

                // Extraer el índice del array (ejemplo: rooms.0.type)
                preg_match('/rooms\.(\d+)\.accommodation/', $attribute, $matches);
                $index = $matches[1] ?? null;

                if ($index !== null) {
                    $roomType = $this->input("rooms.$index.type");

                    if (!isset($validAccommodations[$roomType]) || !in_array($value, $validAccommodations[$roomType])) {
                        $fail("The accommodation '$value' is invalid for the room type '$roomType'.");
                    }
                }
            }],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hotel = Hotel::find($this->hotel_id);

            if ($hotel) {
                $currentRooms = Room::where('hotel_id', $this->hotel_id)
                    ->whereNotIn('id', collect($this->input('rooms'))->pluck('id'))
                    ->sum('qty_rooms');

                $updatedRooms = collect($this->input('rooms'))->sum('qty_rooms');

                $newTotal = $currentRooms + $updatedRooms;

                if ($newTotal > $hotel->qty_rooms) {
                    $validator->errors()->add('rooms', 'The total number of rooms exceeds the hotel capacity.');
                }

                // Validar que no haya tipos y acomodaciones repetidas en la petición
                $roomCombinations = collect($this->input('rooms'))->map(fn ($room) => $room['type'] . '-' . $room['accommodation']);

                if ($roomCombinations->duplicates()->isNotEmpty()) {
                    $validator->errors()->add('rooms', 'There are duplicate room types and accommodations in the request.');
                }

                // Validar que no existan combinaciones repetidas en la base de datos
                foreach ($this->input('rooms') as $room) {
                    if (Room::where('hotel_id', $this->hotel_id)
                        ->where('type', $room['type'])
                        ->where('accommodation', $room['accommodation'])
                        ->where('id', '!=', $room['id'])
                        ->exists()) {
                        $validator->errors()->add('rooms', "The room type '{$room['type']}' with accommodation '{$room['accommodation']}' already exists in this hotel.");
                    }
                }
            }
        });
    }
}
