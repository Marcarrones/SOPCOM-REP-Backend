<?php

class MethodElementView {

    public function buildMethodElement($methodElement, $relationsTo, $relationsFrom) {
        $result = $methodElement[0];
        $result['to'] = $relationsTo;
        $result['from'] = $relationsFrom;
        return $result;
    }
}