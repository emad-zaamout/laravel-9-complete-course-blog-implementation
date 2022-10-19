<?php

declare(strict_types=1);

namespace App\Modules\Datatable;

use Illuminate\Support\Facades\DB;

class DatatableRepository
{
    protected string $table = "";
    protected array $orderColumns = [];
    protected array $searchColumns = [];
    protected array $selectColumns = [];
    protected array $exceptionColumns = [];
    protected string $joinQuery = "";
    protected string $where = "";

    public function index(
        array $columns,
        int $start,
        int $length,
        array $order,
        array $search
    ) : array
    {
        $query = $this->buildSQL($columns, $start, $length, $order, $search);

        $result = json_decode(json_encode(DB::select($query["sql"], $query["bindings"])), true);
        return [
            "recordsFiltered" => json_decode(json_encode(DB::selectOne($query["countSql"], $query["bindings"])), true)["total"],
            "recordsTotal" =>$this->getTotalRecords(),
            "data" => $result
        ];
    }

    public function getTotalRecords() : int {
        return 0;
    }

    private function buildSQL(
        array $columns,
        int $start,
        int $length,
        array $order,
        array $search
    ) : array {
        $selectColumns = implode(",", $this->selectColumns);
        $orderQuery = $this->buildOrderByQuery($columns, $order);
        $searchQueryObject = $this->buildSearchQuery($columns, $search);

        $whereQuery = ($this->where !== "")
            ? "WHERE $this->where"
            : "";

        if ($searchQueryObject["sql"] !== "") {
            $whereQuery = "$whereQuery AND ({$searchQueryObject['sql']})";
        }

        return [
            "sql" => "SELECT $selectColumns
                FROM $this->table
                $whereQuery
                $orderQuery
                LIMIT $length OFFSET $start",
            "countSql" => "SELECT COUNT(*) as total
                FROM $this->table
                $whereQuery",
            "bindings" => $searchQueryObject["bindings"]
        ];
    }

    private function buildOrderByQuery(array $columnsList, array $orderList): string
    {
        $orderByQueryList = [];

        foreach($orderList as $toOrderElement) {
            $orderBy = $toOrderElement["dir"];
            if ($columnsList[$toOrderElement["column"]]["orderable"] === true) {
                $columnName = $columnsList[$toOrderElement["column"]]["data"];
                if (in_array($columnName, $this->orderColumns)) {
                    $orderByQueryList [] = "{$this->exceptionColumns[$columnName]} $orderBy";
                }
            }
        }

        if (count($orderByQueryList) > 0) {
            return "ORDER BY " . implode(",", $orderByQueryList);
        }

        return "";
    }

    private function buildSearchQuery(array $columnsList, array $searchList): array
    {
        $bindingsList = [];
        $queryList = [];

        $searchValue = $searchList["value"];

        foreach($columnsList as $column) {
            $columnName = $column["data"];
            if (in_array($columnName, $this->searchColumns))  {
                $queryList [] = "{$this->exceptionColumns[$columnName]} LIKE ?";
                $bindingsList [] = "%$searchValue%";
            }
        }

        return [
            "sql" => implode(" OR ", $queryList),
            "bindings" => $bindingsList
        ];
    }
}
