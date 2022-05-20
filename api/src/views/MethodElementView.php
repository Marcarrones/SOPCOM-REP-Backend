<?php

class MethodElementView {

    public function buildMethodElement($methodElement, $relationsTo, $relationsFrom) {
        $result = $methodElement;
        $result['to'] = $relationsTo;
        $result['from'] = $relationsFrom;
        return $result;
    }
}