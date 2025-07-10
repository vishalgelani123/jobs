<?php

namespace App\Traits;

trait ModelActionTrait
{
    public function deleteModel($route, $token, $dataTableId)
    {
        return '<li><a href="#" onclick="deleteRow(`' . $route . '`,`' . $token . '`' . ',`' . $dataTableId . '`' . ')" title="Delete" class="dropdown-item text-danger">Delete</a></li>';
    }

    public function editModal($id)
    {
        return '<li><a href="javascript:void(0);" class="dropdown-item" onclick="showFormModal(`' . $id . '`)">Edit</a><li>';
    }

    public function detailModel($id)
    {
        return '<li><a href="javascript:void(0);" class="dropdown-item" onclick="showModal(`' . $id . '`)">Details</a><li>';
    }

    public function detail($route)
    {
        return '<li><a href="' . $route . '" class="dropdown-item">Details</a><li>';
    }

    public function edit($route)
    {
        return '<li><a href="' . $route . '" class="dropdown-item">Edit</a><li>';
    }
}

