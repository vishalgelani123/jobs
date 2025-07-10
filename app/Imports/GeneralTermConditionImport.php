<?php

namespace App\Imports;

use App\Models\GeneralTermCondition;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GeneralTermConditionImport implements ToModel, WithHeadingRow
{
    public $termConditionCategory;

    public function __construct($termConditionCategory)
    {
        $this->termConditionCategory = $termConditionCategory;
    }

    public function model($row)
    {
        if (isset($row['description']) && $row['description'] != "") {
            $generalTermCondition = new GeneralTermCondition;
            $generalTermCondition->user_id = Auth::id();
            $generalTermCondition->category_id = $this->termConditionCategory;
            $generalTermCondition->description = $row['description'];
            $generalTermCondition->save();
        }
    }
}
