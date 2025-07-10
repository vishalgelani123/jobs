<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubmitApplicationDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubmitApplicationController extends Controller
{
    public function index(SubmitApplicationDataTable $dataTable){
        return $dataTable->render('admin.submit-application.index');
    }


}
