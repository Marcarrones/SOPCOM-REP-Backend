<?php

    class MethodChunkView {
        
        public function buildMethodChunk($methodChunk, $intention, $tools) {
            $response = $methodChunk;
            $response['intention'] = $intention;
            $response['tools'] = $tools;
            return $response;
        }

    }

?>