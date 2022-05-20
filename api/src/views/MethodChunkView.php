<?php

    class MethodChunkView {
        
        public function buildMethodChunk($methodChunk, $intention, $tools, $artefacts, $activity, $roles, $contextCriteria, $relations) {
            $response = $methodChunk;
            $response['abstract'] = $activity[0]['abstract'];
            $response['Intention'] = $intention;
            $response['Tools'] = $tools;
            $response['Situation'] = $artefacts['consumed'];
            $response['Product part'] = $artefacts['produced'];
            $response['Process part'] = $activity;
            $response['Roles'] = $roles;
            $response['Context Criteria'] = $contextCriteria;
            $response['Related chunks'] = $relations;
            return $response;
        }

    }

?>