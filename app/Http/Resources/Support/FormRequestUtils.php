<?php

namespace App\Http\Requests\Support;

use Illuminate\Support\Arr;

trait FormRequestUtils
{
    /**
    ** To normalize any request data "after validation is complete", you may use the "passedValidation" method.
    **
    ** The following methods apply "passedValidation" before returning the data:
    **     - input: Regardless of the HTTP verb, it retrieves the user input. Without any arguments, it retrieves all of the input values as an associative array.
    **     - all: Retrieve all of the incoming request's input data as an array.
    **     - collect: Retrieve all of the incoming request's input data as a collection.
    **     - only, except: Retrieve a subset of the input data as an array, accept a single array or a dynamic list of arguments.
    **
    ** The following methods DON'T apply "passedValidation" before returning the data, but "filters" the result based on the "rules":
    **     - validated: Returns an array of all data that successfully passed the validation rules.
    **     - safe: Returns an instance of Illuminate\Support\ValidatedInput, which allows for chaining methods like only(), except(), and all(). By default, it includes only the "safe" input, which typically refers to fields that are intended to be directly used or stored after validation.
    **  */
    public function onlyValidated() : array
    {
        return $this->only(array_keys($this->rules()));
    }

    public function inputJsonDecode(string|array $keys) : array
    {
        $result = [];

        foreach (Arr::wrap($keys) as $key) {
            if ($this->has($key)) {
                $result[$key] = json_decode($this->input($key), true);
            }
        }

        return $result;
    }
}
