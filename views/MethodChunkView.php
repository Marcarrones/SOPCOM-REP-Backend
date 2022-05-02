<?php

    class MethodChunkView {
        
        public function buildMethodChunk($methodChunk, $intention) {
            $response = $methodChunk;
            $response['intention'] = $intention;
            return $response;
        }

    }

?>