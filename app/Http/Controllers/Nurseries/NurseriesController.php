<?php

namespace App\Http\Controllers\Nurseries;

use App\Http\Services\PaginateService;
use Database\DB;
use Views\View;

class NurseriesController
{
    private PaginateService $paginate;

    const LIMIT_ITEM_PAGE = 8;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)((int)($_GET['page'] ?? 1));
    }

    public function index()
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('nurseries');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $nurseries = DB::select("SELECT * FROM nurseries OFFSET ? LIMIT ?", [$offset, self::LIMIT_ITEM_PAGE])->fetchAll();
        return new View('nurseries.nurseries', ['nurseries' => $nurseries, 'paginate' => $paginate]);
    }
}