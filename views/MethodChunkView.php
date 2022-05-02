<?php

    class MethodChunkView {
        
        public function buildMethodChunk($methodChunk, $intention, $tools, $artefacts, $activity) {
            $response = $methodChunk;
            $response['abstract'] = $activity['abstract'];
            $response['intention'] = $intention;
            $response['tools'] = $tools;
            $response['situation'] = $artefacts['consumed'];
            $response['Product part'] = $artefacts['produced'];
            $response['Process part'] = $activity;
            return $response;
        }

    }

?>