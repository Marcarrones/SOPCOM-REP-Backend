<?php

class MapView {

    public function buildMapList($maps) {
        $result = [];
        foreach($maps as $map) {
            $pos = array_search($map['id'], array_column($result, 'id'));
            if($pos !== false) {
                $result[$pos][] = Array('id' => $map['id'], 'name' => $map['name']);
            } else {
                $result[] = Array('id' => $map['id'], 
                            'name' => $map['name']);
            }
        }
        return $result;
    }

    public function buildMap($map) {
        $result = $map[0];
        return $result;
    }





    public function buildGoalList($goals) {
        $result = [];
        foreach($goals as $goal) {
            $pos = array_search($goal['id'], array_column($result, 'id'));
            if($pos !== false) {
                $result[$pos][] = Array('id' => $goal['id'], 'name' => $goal['name'], 'x' => $goal['x'], 'y' => $goal['y'], 'map' => $goal['map']);
            } else {
                $result[] = Array('id' => $goal['id'], 
                            'name' => $goal['name'], 'x' => $goal['x'], 'y' => $goal['y'], 'map' => $goal['map']);
            }
        }
        return $result;
    }
}