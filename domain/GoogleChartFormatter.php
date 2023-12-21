<?php

class GoogleChartFormatter {

    /**
    * format for pie chart with @param result:
    *{
    *  "cols": [
    *        {"id":"","label":"Topping","pattern":"","type":"string"},
    *        {"id":"","label":"Slices","pattern":"","type":"number"}
    *      ],
    *  "rows": [
    *        {"c":[{"v":"Mushrooms","f":null},{"v":3,"f":null}]},
    *        {"c":[{"v":"Onions","f":null},{"v":1,"f":null}]},
    *        {"c":[{"v":"Olives","f":null},{"v":1,"f":null}]},
    *        {"c":[{"v":"Zucchini","f":null},{"v":1,"f":null}]},
    *        {"c":[{"v":"Pepperoni","f":null},{"v":2,"f":null}]}
    *      ]
    *}
    */
    function formatForPie($result) {
        $cols = [];
        $rows = [];

        foreach ($result as $index => $row) {
            $rows[$index] = ["c" => []];

            foreach ($row as $k => $v) {

                if ($index == 0) {
                    $cols[] = ["id" => "", "label" => ucfirst($k), "pattern" => "", "type" => is_numeric($v) ? "number" : gettype($v)];
                }

                $rows[$index]["c"][] = ["v" => is_numeric($v) ? (int)$v : $v, "f" => null];
            }

        }

        return json_encode(["cols" => $cols, "rows" => $rows]);
    }

    /**
    * format for Bar with @param result, xaxis, yaxis like this:
    *[
    *    ['City', '2010 Population', '2000 Population'],
    *    ['New York City, NY', 8175000, 8008000],
    *    ['Los Angeles, CA', 3792000, 3694000],
    *    ['Chicago, IL', 2695000, 2896000],
    *    ['Houston, TX', 2099000, 1953000],
    *    ['Philadelphia, PA', 1526000, 1517000]
    *]
    */
    function formatForBars($result, $xaxis, $yaxis) {
        $formatted = [];
        $categories = [];
        $names = [];

        foreach ($result as $index => $row) {
            $categories[] = $row->{$xaxis};
            $names[] = $row->{$yaxis};

        }

        $categories = array_unique($categories);
        $names = array_unique($names);
        $formatted[] = array_merge([$xaxis], $names);

        $restRows = [];
        $rows = [];

        foreach ($result as $index => $row) {
            $initialRow = null;

            foreach ($row as $k => $v) {
                if ($k == $xaxis) {
                    $initialRow = [$v];

                } else if ($k != $yaxis) {
                    $restRows[] = (float)$v;

                }
            }

            if ($initialRow && count($restRows) == count($names)) {
                $rows[] = array_merge($initialRow, $restRows);
                $restRows = [];

            }

        }

        $formatted = array_merge($formatted, $rows);

        return json_encode($formatted);
    }

}
