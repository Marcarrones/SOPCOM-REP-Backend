<?php

    class MethodChunkView {
        
        public function buildMethodChunk($methodChunk, $intention, $tools, $artefacts) {
            $response = $methodChunk;
            $response['intention'] = $intention;
            $response['tools'] = $tools;
            $response['situation'] = $artefacts['consumed'];
            $response['Product part'] = $artefacts['produced'];
            return $response;
        }

    }

?>