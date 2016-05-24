<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddNoteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /*
         * Anybody can add notes. We'll only limit adding notes by who is allowed to
         * view the subject.
         */
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
            'content' => 'required'
        ];
    }
}
