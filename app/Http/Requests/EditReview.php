<?php

namespace App\Http\Requests;

use App\Review;
use Illuminate\Foundation\Http\FormRequest;

class EditReview extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return Review::where('id', $this->review_id)->where('user_id', $this->user()->id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'stars' => 'required',
			'body' => 'required',
        ];
    }
}
