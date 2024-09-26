<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;

class UniqueMultipleTable implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $tables;
    protected $exceptId;
     
    public function __construct(array $tables = [], $exceptId = 0)
    {   
        //
        $this->tables = $tables;
        $this->exceptId = $exceptId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        foreach($this->tables as $key => $table) {
            $query = DB::table($table)->where($attribute, $value);
            if($this->exceptId) {
                if($table == 'routers') {
                    $query->where('module_id', '<>', (int) $this->exceptId);
                }else {
                    $query->where('id', '<>', (int) $this->exceptId);
                }
            }
            if($query->exists()) {
                return false;
            }   
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Canonical đã tồn tại. Vui lòng chọn canonical khác!';
    }
}
