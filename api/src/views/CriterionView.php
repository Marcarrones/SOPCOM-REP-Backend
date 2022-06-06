<?php

class CriterionView {

    public function buildCriterionsList($criterions) {
        $result = [];
        foreach($criterions as $criterion) {
            $pos = array_search($criterion['criterionId'], array_column($result, 'criterionId'));
            if($pos !== false) {
                $result[$pos]['values'][] = Array('id' => $criterion['id'], 'name' => $criterion['name']);
            } else {
                $result[] = Array('criterionId' => $criterion['criterionId'], 
                            'criterionName' => $criterion['criterionName'], 
                            'values' => [Array('id' => $criterion['id'], 
                                                'name' => $criterion['name'])]);
            }
        }
        return $result;
    }

    public function buildCriterion($criterion, $values) {
        $result = $criterion[0];
        $result['values'] = $values;
        return $result;
    }
}